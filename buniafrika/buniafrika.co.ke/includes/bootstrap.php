<?php

// Prevent duplicate bootstrap execution
if (defined('BUNIAFRIKA_BOOTSTRAPPED')) {
    return;
}

if (!function_exists('handleBuniafrikaConfigError')) {
    /**
     * Halts execution with a clear error without exposing credentials or internal paths.
     *
     * @param string $logMessage Technical message for error log / CLI.
     */
    function handleBuniafrikaConfigError($logMessage) {
        if (PHP_SAPI === 'cli') {
            fwrite(STDERR, "[Buniafrika Config Error] " . $logMessage . "\n");
        } else {
            error_log("[Buniafrika Config Error] " . $logMessage);
            if (!headers_sent()) {
                http_response_code(500);
            }
            exit('The website is temporarily unavailable. Please try again later.');
        }
        exit(1);
    }
}

if (!function_exists('loadEnv')) {
    /**
     * Loads key-value pairs from an environment file into $_ENV, $_SERVER, and putenv().
     * Does not overwrite existing environment variables unless $overwrite is true.
     *
     * @param string $path Absolute file path.
     * @param bool $overwrite Whether to overwrite existing variables (default: false).
     * @return bool True on success, false on failure.
     */
    function loadEnv($path, $overwrite = false) {
        if (!is_string($path) || $path === '' || !file_exists($path) || !is_readable($path)) {
            return false;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return false;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Strip surrounding single or double quotes
            if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                $value = substr($value, 1, -1);
            }

            // Do not unexpectedly overwrite existing environment variables
            if ($overwrite || !array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
            }
            if ($overwrite || !array_key_exists($key, $_SERVER)) {
                $_SERVER[$key] = $value;
            }
            if ($overwrite || getenv($key) === false) {
                putenv("{$key}={$value}");
            }
        }

        return true;
    }
}

if (!function_exists('startAppSession')) {
    function startAppSession() {
        if (session_status() !== PHP_SESSION_NONE) {
            return;
        }

        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');

        $savePath = session_save_path();
        if (empty($savePath) || !@is_dir($savePath) || !@is_writable($savePath)) {
            @session_save_path(sys_get_temp_dir());
        }

        $isHttps = (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off')
            || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string) $_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
            || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower((string) $_SERVER['HTTP_X_FORWARDED_SSL']) === 'on')
            || (!empty($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);

        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => $isHttps,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_start();
    }
}

// Resolve the private application directory path (buniafrika.co.ke) vs local development root
if (!defined('BUNIAFRIKA_PRIVATE_DIR')) {
    $envDir = getenv('BUNIAFRIKA_PRIVATE_DIR');
    if ($envDir && is_dir($envDir)) {
        define('BUNIAFRIKA_PRIVATE_DIR', rtrim($envDir, '/\\'));
    } elseif (in_array(basename(dirname(__DIR__)), ['buniafrika.co.ke', 'ubuniafrika.co.ke'], true)) {
        define('BUNIAFRIKA_PRIVATE_DIR', dirname(__DIR__));
    } elseif (is_dir(dirname(__DIR__, 2) . '/buniafrika.co.ke')) {
        define('BUNIAFRIKA_PRIVATE_DIR', dirname(__DIR__, 2) . '/buniafrika.co.ke');
    } elseif (is_dir(dirname(__DIR__, 3) . '/buniafrika.co.ke')) {
        define('BUNIAFRIKA_PRIVATE_DIR', dirname(__DIR__, 3) . '/buniafrika.co.ke');
    } elseif (is_dir(dirname(__DIR__) . '/buniafrika.co.ke')) {
        define('BUNIAFRIKA_PRIVATE_DIR', dirname(__DIR__) . '/buniafrika.co.ke');
    } else {
        define('BUNIAFRIKA_PRIVATE_DIR', dirname(__DIR__));
    }
}

// -----------------------------------------------------------------------------
// Deterministic Buniafrika Environment Loader
// Exclusively loads: buniafrika.env
// Never falls back to .env or other applications (portfolio.env, beekay.env, etc.)
// -----------------------------------------------------------------------------
$resolvedEnvFile = null;

// 1. Explicit BUNIAFRIKA_ENV_FILE override (cannot silently fall back if invalid)
$customEnv = getenv('BUNIAFRIKA_ENV_FILE');
if (!empty($customEnv)) {
    if (!file_exists($customEnv) || !is_readable($customEnv)) {
        handleBuniafrikaConfigError('Specified BUNIAFRIKA_ENV_FILE does not exist or is unreadable: ' . basename($customEnv));
    }
    $resolvedEnvFile = $customEnv;
}

// 2. Deterministic location search for buniafrika.env
if ($resolvedEnvFile === null) {
    // 2a. Check cPanel home root directory (/home/CPANEL_USERNAME/secrets/buniafrika.env)
    if (preg_match('#^(/home\d*/[^/]+)#', BUNIAFRIKA_PRIVATE_DIR, $cpanelMatch)) {
        $cpanelSecrets = $cpanelMatch[1] . '/secrets/buniafrika.env';
        if (file_exists($cpanelSecrets) && is_readable($cpanelSecrets)) {
            $resolvedEnvFile = $cpanelSecrets;
        }
    }

    // 2b. If not found in cPanel home root, traverse parent directories from BUNIAFRIKA_PRIVATE_DIR
    // Looking strictly for secrets/buniafrika.env without assuming fixed directory depth
    if ($resolvedEnvFile === null) {
        $searchDir = BUNIAFRIKA_PRIVATE_DIR;
        while ($searchDir && $searchDir !== '/' && dirname($searchDir) !== $searchDir) {
            $candidate = $searchDir . '/secrets/buniafrika.env';
            if (file_exists($candidate) && is_readable($candidate)) {
                $resolvedEnvFile = $candidate;
                break;
            }
            $searchDir = dirname($searchDir);
        }
    }
}

// 3. Ensure configuration file was resolved
if ($resolvedEnvFile === null || !file_exists($resolvedEnvFile) || !is_readable($resolvedEnvFile)) {
    handleBuniafrikaConfigError('Dedicated configuration file (buniafrika.env) was not found or is unreadable.');
}

// 4. Load the configuration
if (!loadEnv($resolvedEnvFile)) {
    handleBuniafrikaConfigError('Failed to read or parse configuration file (buniafrika.env).');
}

// 5. Verify that required configuration is present and non-empty
if (empty($_ENV['DB_NAME']) || empty($_ENV['BACKEND_API_URL'])) {
    handleBuniafrikaConfigError('Incomplete configuration in buniafrika.env (missing required DB_NAME or BACKEND_API_URL).');
}

define('BUNIAFRIKA_BOOTSTRAPPED', true);
