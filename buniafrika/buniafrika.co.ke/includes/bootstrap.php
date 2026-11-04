<?php

if (!function_exists('loadEnv')) {
    function loadEnv($path) {
        if (!file_exists($path) || !is_readable($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Strip surrounding single or double quotes if present
            if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                $value = substr($value, 1, -1);
            }

            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
            putenv("{$key}={$value}");
        }
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
        // When bootstrap.php is located inside /home/ACCOUNT/buniafrika-files/buniafrika/buniafrika.co.ke/includes/
        define('BUNIAFRIKA_PRIVATE_DIR', dirname(__DIR__));
    } elseif (is_dir(dirname(__DIR__, 2) . '/buniafrika.co.ke')) {
        // Unified cPanel deployment: /buniafrika/buniafrika/includes -> /buniafrika/buniafrika.co.ke
        define('BUNIAFRIKA_PRIVATE_DIR', dirname(__DIR__, 2) . '/buniafrika.co.ke');
    } elseif (is_dir(dirname(__DIR__, 3) . '/buniafrika.co.ke')) {
        define('BUNIAFRIKA_PRIVATE_DIR', dirname(__DIR__, 3) . '/buniafrika.co.ke');
    } elseif (is_dir(dirname(__DIR__) . '/buniafrika.co.ke')) {
        define('BUNIAFRIKA_PRIVATE_DIR', dirname(__DIR__) . '/buniafrika.co.ke');
    } else {
        // Local development repository root fallback
        define('BUNIAFRIKA_PRIVATE_DIR', dirname(__DIR__));
    }
}

// Load environment configuration (.env) prioritizing dedicated secrets folder
// Production target: /home/ACCOUNT/buniafrika-files/secrets/.env
$envCandidates = [];

// 1. Explicit environment variable override
$customEnv = getenv('BUNIAFRIKA_ENV_FILE') ?: getenv('ENV_FILE');
if ($customEnv) {
    $envCandidates[] = $customEnv;
}

// 2. Relative to BUNIAFRIKA_PRIVATE_DIR (buniafrika/buniafrika.co.ke -> secrets/)
$envCandidates[] = dirname(BUNIAFRIKA_PRIVATE_DIR, 2) . '/secrets/buniafrika.env';
$envCandidates[] = dirname(BUNIAFRIKA_PRIVATE_DIR, 2) . '/secrets/.env';

// 3. Relative to current file (__DIR__ is .../buniafrika.co.ke/includes -> .../secrets/)
$envCandidates[] = dirname(__DIR__, 3) . '/secrets/buniafrika.env';
$envCandidates[] = dirname(__DIR__, 3) . '/secrets/.env';

// 4. cPanel home pattern detection (/home/ACCOUNT/secrets/...)
if (preg_match('#^(/home/[^/]+)#', __DIR__, $matches)) {
    $envCandidates[] = $matches[1] . '/secrets/buniafrika.env';
    $envCandidates[] = $matches[1] . '/secrets/.env';
    $envCandidates[] = $matches[1] . '/buniafrika-files/secrets/buniafrika.env';
    $envCandidates[] = $matches[1] . '/buniafrika-files/secrets/.env';
}

// 5. Fallback relative and sibling locations
$envCandidates[] = dirname(BUNIAFRIKA_PRIVATE_DIR) . '/secrets/buniafrika.env';
$envCandidates[] = dirname(BUNIAFRIKA_PRIVATE_DIR) . '/secrets/.env';
$envCandidates[] = dirname(__DIR__, 2) . '/secrets/buniafrika.env';
$envCandidates[] = dirname(__DIR__, 2) . '/secrets/.env';
$envCandidates[] = dirname(__DIR__, 4) . '/secrets/buniafrika.env';
$envCandidates[] = dirname(__DIR__, 4) . '/secrets/.env';
$envCandidates[] = BUNIAFRIKA_PRIVATE_DIR . '/secrets/buniafrika.env';
$envCandidates[] = BUNIAFRIKA_PRIVATE_DIR . '/secrets/.env';
$envCandidates[] = BUNIAFRIKA_PRIVATE_DIR . '/buniafrika.env';
$envCandidates[] = BUNIAFRIKA_PRIVATE_DIR . '/.env';
$envCandidates[] = __DIR__ . '/../../secrets/buniafrika.env';
$envCandidates[] = __DIR__ . '/../../secrets/.env';
$envCandidates[] = __DIR__ . '/../secrets/buniafrika.env';
$envCandidates[] = __DIR__ . '/../secrets/.env';
$envCandidates[] = __DIR__ . '/../.env';

foreach ($envCandidates as $envCandidate) {
    if (!empty($envCandidate) && file_exists($envCandidate)) {
        loadEnv($envCandidate);
        break;
    }
}
