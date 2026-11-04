<?php

require_once __DIR__ . '/db_connection.php';
require_once __DIR__ . '/seo_helper.php';
function dbEscape($conn, $string) {
    return mysqli_real_escape_string($conn, trim($string));
}

function generateCsrfToken() {
    startAppSession();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
    startAppSession();
    if (empty($token) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], (string)$token)) {
        return false;
    }
    return true;
}

function getCsrfTokenFromRequest() {
    if (!empty($_POST['csrf_token'])) {
        return (string)$_POST['csrf_token'];
    }
    if (!empty($_SERVER['HTTP_X_CSRF_TOKEN'])) {
        return (string)$_SERVER['HTTP_X_CSRF_TOKEN'];
    }
    $raw = file_get_contents('php://input');
    if (!empty($raw)) {
        $decoded = json_decode($raw, true);
        if (is_array($decoded) && !empty($decoded['csrf_token'])) {
            return (string)$decoded['csrf_token'];
        }
    }
    return '';
}

function requireValidCsrfToken($conn = null) {
    $token = getCsrfTokenFromRequest();
    if (!verifyCsrfToken($token)) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        if ($conn && function_exists('logActivity')) {
            logActivity($conn, 'CSRF Failure', 'Rejected request with invalid or missing CSRF token from IP: ' . $ip, 'system', null, 'System');
        }
        http_response_code(403);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'error',
            'code' => 'CSRF_INVALID',
            'message' => 'Invalid or expired security token. Please refresh the page and try again.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    return true;
}

/**
 * Server-Side Rate Limiter
 * Tracks attempts per action and IP address in the `rate_limits` table.
 */
function checkRateLimit($conn, string $action, int $maxAttempts = 5, int $windowSeconds = 600, int $blockSeconds = 300): array {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $now = time();

    if (!$conn || !($conn instanceof mysqli) || $conn->connect_error) {
        return ['allowed' => true, 'remaining' => $maxAttempts, 'retry_after' => 0];
    }

    // Auto-cleanup stale entries older than 24 hours (1 in 20 chance)
    if (mt_rand(1, 20) === 1) {
        $cutoff = $now - 86400;
        @$conn->query("DELETE FROM rate_limits WHERE last_attempt_at < {$cutoff} AND blocked_until < {$now}");
    }

    try {
        $stmt = $conn->prepare("SELECT id, attempts, first_attempt_at, last_attempt_at, blocked_until FROM rate_limits WHERE action = ? AND ip_address = ? LIMIT 1");
        if (!$stmt) {
            return ['allowed' => true, 'remaining' => $maxAttempts, 'retry_after' => 0];
        }
        $stmt->bind_param('ss', $action, $ip);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();

        if ($row) {
            $id = (int)$row['id'];
            $attempts = (int)$row['attempts'];
            $firstAttempt = (int)$row['first_attempt_at'];
            $blockedUntil = (int)$row['blocked_until'];

            // Currently blocked?
            if ($blockedUntil > $now) {
                $retryAfter = $blockedUntil - $now;
                return ['allowed' => false, 'remaining' => 0, 'retry_after' => $retryAfter, 'blocked' => true];
            }

            // Has window expired?
            if (($now - $firstAttempt) > $windowSeconds) {
                $update = $conn->prepare("UPDATE rate_limits SET attempts = 1, first_attempt_at = ?, last_attempt_at = ?, blocked_until = 0 WHERE id = ?");
                if ($update) {
                    $update->bind_param('iii', $now, $now, $id);
                    $update->execute();
                    $update->close();
                }
                return ['allowed' => true, 'remaining' => $maxAttempts - 1, 'retry_after' => 0];
            }

            // Increment attempt count
            $newAttempts = $attempts + 1;
            if ($newAttempts > $maxAttempts) {
                $newBlockedUntil = $now + $blockSeconds;
                $update = $conn->prepare("UPDATE rate_limits SET attempts = ?, last_attempt_at = ?, blocked_until = ? WHERE id = ?");
                if ($update) {
                    $update->bind_param('iiii', $newAttempts, $now, $newBlockedUntil, $id);
                    $update->execute();
                    $update->close();
                }
                return ['allowed' => false, 'remaining' => 0, 'retry_after' => $blockSeconds, 'blocked' => true];
            } else {
                $update = $conn->prepare("UPDATE rate_limits SET attempts = ?, last_attempt_at = ? WHERE id = ?");
                if ($update) {
                    $update->bind_param('iii', $newAttempts, $now, $id);
                    $update->execute();
                    $update->close();
                }
                return ['allowed' => true, 'remaining' => max(0, $maxAttempts - $newAttempts), 'retry_after' => 0];
            }
        } else {
            // First recorded attempt
            $insert = $conn->prepare("INSERT INTO rate_limits (action, ip_address, attempts, first_attempt_at, last_attempt_at, blocked_until) VALUES (?, ?, 1, ?, ?, 0)");
            if ($insert) {
                $insert->bind_param('ssii', $action, $ip, $now, $now);
                $insert->execute();
                $insert->close();
            }
            return ['allowed' => true, 'remaining' => $maxAttempts - 1, 'retry_after' => 0];
        }
    } catch (Throwable $e) {
        error_log('Rate limit check error: ' . $e->getMessage());
        return ['allowed' => true, 'remaining' => $maxAttempts, 'retry_after' => 0];
    }
}

/**
 * Enforce rate limit, terminating execution with HTTP 429 and JSON error if exceeded
 */
function enforceRateLimit($conn, string $action, int $maxAttempts = 5, int $windowSeconds = 600, int $blockSeconds = 300) {
    $result = checkRateLimit($conn, $action, $maxAttempts, $windowSeconds, $blockSeconds);
    if (!$result['allowed']) {
        $retryAfter = $result['retry_after'] ?? 60;
        http_response_code(429);
        header('Retry-After: ' . $retryAfter);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'error',
            'code' => 'RATE_LIMIT_EXCEEDED',
            'retry_after' => $retryAfter,
            'message' => "Too many attempts from your network. Please wait {$retryAfter} seconds before trying again."
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
    return true;
}

/**
 * Standard input validation helpers
 */
function isValidEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false && strlen($email) <= 255;
}

function isValidPhone(string $phone): bool {
    return (bool)preg_match('/^[+0-9()\s.-]{7,30}$/', $phone);
}

function isValidUrl(string $url): bool {
    return filter_var($url, FILTER_VALIDATE_URL) !== false && preg_match('~^https?://~i', $url);
}

function h($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function isAllowedCorsOrigin($origin) {
    if (empty($origin)) return true;
    $allowedOrigins = [
        'https://buniafrika.co.ke',
        'https://www.buniafrika.co.ke',
        'https://api.buniafrika.co.ke',
        rtrim($_ENV['FRONTEND_URL'] ?? '', '/'),
        rtrim($_ENV['BACKEND_API_URL'] ?? '', '/'),
    ];
    $allowedOrigins = array_values(array_filter(array_unique($allowedOrigins)));
    if (in_array($origin, $allowedOrigins, true) || empty($allowedOrigins)) {
        return true;
    }
    if (preg_match('#^https?://(localhost|127\.0\.0\.1|::1)(:\d+)?$#', $origin)) {
        return true;
    }
    return false;
}

function sendCorsHeaders() {
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    if (!empty($origin)) {
        if (isAllowedCorsOrigin($origin)) {
            header('Access-Control-Allow-Origin: ' . $origin);
            header('Access-Control-Allow-Credentials: true');
            header('Vary: Origin');
        }
    } else {
        header('Access-Control-Allow-Origin: *');
    }

    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-CSRF-Token');

    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
        http_response_code(204);
        exit;
    }
}

function siteSettingsDefaults() {
    return [
        'site_name' => 'BuniAfrika CBO',
        'website_url' => 'https://buniafrika.co.ke/',
        'general_phone' => '0705556565',
        'whatsapp_number' => '254705556565',
        'general_email' => 'info@buniafrika.co.ke',
        'partnership_email' => 'partnerships@buniafrika.co.ke',
        'finance_email' => 'finance@buniafrika.co.ke',
        'location_text' => 'Nyali, Mombasa, Mombasa County, Kenya',
        'location_map_url' => 'https://www.google.com/maps/search/?api=1&query=Nyali%2C+Mombasa%2C+Kenya',
        'social_facebook' => 'https://www.facebook.com/buniafrika',
        'social_instagram' => 'https://www.instagram.com/buniafrika/',
        'social_linkedin' => 'https://www.linkedin.com/company/buniafrika/',
        'social_youtube' => 'https://www.youtube.com/@buniafrika',
        'social_x' => 'https://x.com/buniafrika',
        'social_tiktok' => 'https://www.tiktok.com/@buniafrika',
        'social_whatsapp_channel' => '',
        'vision' => 'A society where informed citizenship, institutional accountability and innovation drive inclusive progress.',
        'mission' => 'To bridge research, technology and civic action in addressing society’s most pressing challenges.',
        'objectives' => "Advance democratic governance and public accountability.\nGenerate evidence-based research on critical public-interest challenges.\nDevelop innovative technological solutions to civic problems.\nStrengthen inclusive civic participation, digital rights and youth leadership.\nPromote collaborative policy reform and measurable social impact.",
        'civictech_title' => 'CivicTech Awareness',
        'civictech_intro' => 'Civic technology education and digital participation.',
        'civictech_points' => "Democratic governance, transparency and public accountability.\nDigital rights and responsible technology.\nYouth leadership and inclusive civic participation.\nEvidence-based research and civic problem-solving.\nCollaborative policy engagement.",
        'heritage_title' => 'Culture and Heritage',
        'heritage_intro' => 'Community-led approaches to documenting and responsibly preserving African cultural identity and heritage.',
        'heritage_points' => "African cultural identity and heritage preservation.\nCommunity histories and indigenous knowledge.\nDigital archiving and cultural storytelling.\nIntergenerational knowledge transfer.\nCommunity-led heritage documentation.\nResponsible digital preservation.",
        'leader_rahma_name' => 'Rahma Kassim',
        'leader_rahma_role' => '',
        'leader_rahma_role_verified' => '0',
        'leader_rahma_phone' => '',
        'leader_rahma_email' => '',
        'leader_rahma_image' => '',
        'leader_rahma_description' => '',
        'leader_brodox_name' => 'Brodox George',
        'leader_brodox_role' => '',
        'leader_brodox_role_verified' => '0',
        'leader_brodox_phone' => '',
        'leader_brodox_email' => '',
        'leader_brodox_image' => '',
        'leader_brodox_description' => '',
        'leader_kennedy_name' => 'Kennedy Muuo',
        'leader_kennedy_role' => '',
        'leader_kennedy_role_verified' => '0',
        'leader_kennedy_phone' => '',
        'leader_kennedy_email' => '',
        'leader_kennedy_image' => '',
        'leader_kennedy_description' => '',
        'leadership_ids' => '',
    ];
}

function getSiteSettings($conn) {
    $settings = siteSettingsDefaults();
    $table = $conn->query("SHOW TABLES LIKE 'site_settings'");
    if (!$table || $table->num_rows === 0) {
        error_log('Organization site settings are unavailable; apply database/migrations/20261020_create_site_settings.sql.');
        $GLOBALS['site_settings_available'] = false;
        $GLOBALS['site_settings'] = $settings;
        return $settings;
    }

    $GLOBALS['site_settings_available'] = true;
    $result = $conn->query('SELECT setting_key, setting_value FROM site_settings');
    while ($row = $result->fetch_assoc()) {
        if (
            array_key_exists($row['setting_key'], $settings)
            || preg_match('/^leader_[a-z0-9_-]+_(name|role|role_verified|public_visible|phone|email|image|description)$/', $row['setting_key'])
        ) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
    }
    $GLOBALS['site_settings'] = $settings;
    return $settings;
}

function siteSettingLines($value) {
    return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string)$value)), function ($line) {
        return $line !== '';
    }));
}

function phoneNumberHref($phone) {
    $digits = preg_replace('/\D/', '', (string)$phone);
    if (strpos($digits, '254') === 0) {
        return '+' . $digits;
    }
    if (strpos($digits, '0') === 0) {
        return '+254' . substr($digits, 1);
    }
    return '+' . $digits;
}

function getLeadershipProfiles($settings, $includeHiddenProfiles = false) {
    $defaultIds = ['rahma', 'brodox', 'kennedy'];
    if (($settings['leadership_ids'] ?? '') !== '') {
        $decodedIds = json_decode($settings['leadership_ids'], true);
        $leaderIds = is_array($decodedIds) ? $decodedIds : $defaultIds;
    } else {
        $leaderIds = $defaultIds;
    }

    $profiles = [];
    foreach ($leaderIds as $leaderId) {
        if (!is_string($leaderId) || !preg_match('/^[a-z0-9_-]{1,40}$/', $leaderId)) {
            continue;
        }
        $prefix = 'leader_' . $leaderId . '_';
        $publicVisible = (string)($settings[$prefix . 'public_visible'] ?? (in_array($leaderId, $defaultIds, true) ? '1' : '0'));
        $profile = [
            'id' => $leaderId,
            'name' => (string)($settings[$prefix . 'name'] ?? ''),
            'role' => (string)($settings[$prefix . 'role'] ?? ''),
            'role_verified' => (string)($settings[$prefix . 'role_verified'] ?? '0'),
            'public_visible' => $publicVisible,
            'phone' => (string)($settings[$prefix . 'phone'] ?? ''),
            'email' => (string)($settings[$prefix . 'email'] ?? ''),
            'image' => (string)($settings[$prefix . 'image'] ?? ''),
            'description' => (string)($settings[$prefix . 'description'] ?? ''),
        ];
        if (!$includeHiddenProfiles && $profile['public_visible'] !== '1') {
            continue;
        }
        $profiles[] = $profile;
    }
    return $profiles;
}

function resolveAssetRoot() {
    $candidates = [
        dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'buniafrika' . DIRECTORY_SEPARATOR . 'assets',
        dirname(__DIR__) . DIRECTORY_SEPARATOR . 'assets',
        dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'assets',
    ];
    if (function_exists('resolveUploadsBaseDir')) {
        $uploads = resolveUploadsBaseDir();
        if ($uploads && is_dir($uploads)) {
            $candidates[] = dirname($uploads);
        }
    }
    foreach ($candidates as $cand) {
        $real = realpath($cand);
        if ($real && is_dir($real)) {
            return $real;
        }
    }
    return null;
}

function ensureSiteSettingsTable($conn) {
    $table = $conn->query("SHOW TABLES LIKE 'site_settings'");
    if (!$table || $table->num_rows === 0) {
        throw new RuntimeException('The organization settings table is not installed. Apply the site settings database migration first.');
    }
}

function saveSettingKeyValuePairs($conn, array $pairs) {
    ensureSiteSettingsTable($conn);
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare('INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
        foreach ($pairs as $key => $value) {
            $strVal = (string)$value;
            $stmt->bind_param('ss', $key, $strVal);
            $stmt->execute();
        }
        $stmt->close();
        $conn->commit();
    } catch (Throwable $e) {
        $conn->rollback();
        error_log('Could not save site settings: ' . $e->getMessage());
        throw new RuntimeException('Settings could not be saved due to a database error.');
    }
}

function saveOrganizationSettings($conn, $input) {
    if (!is_array($input)) {
        throw new InvalidArgumentException('Invalid input for organisation settings.');
    }

    $required = [
        'site_name',
        'website_url',
        'general_phone',
        'whatsapp_number',
        'general_email',
        'partnership_email',
        'finance_email',
        'location_text',
        'location_map_url'
    ];

    $errors = [];
    $values = [];

    foreach ($required as $key) {
        $value = trim((string)($input[$key] ?? ''));
        if ($value === '') {
            $errors[] = 'Required setting is missing: ' . $key . '.';
        }
        if (strlen($value) > 20000) {
            $errors[] = 'Setting is too long: ' . $key . '.';
        }
        if ($key === 'site_name' && (strlen($value) > 150 || preg_match('/[\r\n]/', $value))) {
            $errors[] = 'Organisation name must fit within 150 characters on one line.';
        }
        if (in_array($key, ['website_url', 'location_map_url'], true) && $value !== '') {
            $parts = parse_url($value);
            if (strlen($value) > 2048 || !filter_var($value, FILTER_VALIDATE_URL) || !is_array($parts) || strtolower($parts['scheme'] ?? '') !== 'https') {
                $errors[] = 'Use a valid HTTPS URL for ' . $key . '.';
            }
        }
        if (in_array($key, ['general_email', 'partnership_email', 'finance_email'], true) && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Use a valid email address for ' . $key . '.';
        }
        if ($key === 'general_phone' && $value !== '' && !preg_match('/^\+?[0-9][0-9\s().-]{5,24}$/', $value)) {
            $errors[] = 'Use a valid phone number for ' . $key . '.';
        }
        if ($key === 'whatsapp_number' && !preg_match('/^[0-9]{8,15}$/', $value)) {
            $errors[] = 'WhatsApp number must contain 8 to 15 digits, including the country code.';
        }
        $values[$key] = $value;
    }

    if (!empty($errors)) {
        throw new InvalidArgumentException(implode(' ', $errors));
    }

    saveSettingKeyValuePairs($conn, $values);
    return $values;
}

function saveSocialSettings($conn, $input) {
    if (!is_array($input)) {
        throw new InvalidArgumentException('Invalid input for social media settings.');
    }

    $socialKeys = [
        'social_facebook',
        'social_instagram',
        'social_linkedin',
        'social_youtube',
        'social_x',
        'social_tiktok',
        'social_whatsapp_channel'
    ];

    $errors = [];
    $values = [];

    foreach ($socialKeys as $key) {
        $value = trim((string)($input[$key] ?? ''));
        if ($value !== '') {
            $parts = parse_url($value);
            if (strlen($value) > 2048 || !filter_var($value, FILTER_VALIDATE_URL) || !is_array($parts) || strtolower($parts['scheme'] ?? '') !== 'https') {
                $errors[] = 'Use a valid HTTPS URL for ' . $key . '.';
            }
        }
        $values[$key] = $value;
    }

    if (!empty($errors)) {
        throw new InvalidArgumentException(implode(' ', $errors));
    }

    saveSettingKeyValuePairs($conn, $values);
    return $values;
}

function saveVisionSettings($conn, $input) {
    if (!is_array($input)) {
        throw new InvalidArgumentException('Invalid input for vision and mission settings.');
    }

    $visionKeys = ['vision', 'mission', 'objectives'];
    $errors = [];
    $values = [];

    foreach ($visionKeys as $key) {
        $value = trim((string)($input[$key] ?? ''));
        if ($value === '') {
            $errors[] = 'Required setting is missing: ' . $key . '.';
        }
        if (strlen($value) > 20000) {
            $errors[] = 'Setting is too long: ' . $key . '.';
        }
        $values[$key] = $value;
    }

    if (!empty($errors)) {
        throw new InvalidArgumentException(implode(' ', $errors));
    }

    saveSettingKeyValuePairs($conn, $values);
    return $values;
}

function saveThematicSettings($conn, $input) {
    $rawAreas = null;
    if (is_array($input)) {
        if (isset($input['thematic_areas'])) {
            $rawAreas = is_string($input['thematic_areas']) ? json_decode($input['thematic_areas'], true) : $input['thematic_areas'];
        } else {
            $rawAreas = $input;
        }
    } elseif (is_string($input)) {
        $rawAreas = json_decode($input, true);
    }

    if (!is_array($rawAreas) || empty($rawAreas)) {
        throw new InvalidArgumentException('Provide at least one thematic area.');
    }

    $cleanAreas = [];
    $errors = [];

    foreach ($rawAreas as $index => $area) {
        if (!is_array($area)) {
            $errors[] = 'Thematic area ' . ($index + 1) . ' is formatted incorrectly.';
            continue;
        }

        $title = trim((string)($area['title'] ?? ''));
        $intro = trim((string)($area['intro'] ?? ''));

        if ($title === '') {
            $errors[] = 'Every thematic area must have a title.';
        } elseif (strlen($title) > 180 || preg_match('/[\r\n]/', $title)) {
            $errors[] = 'Thematic area title must fit within 180 characters on one line.';
        }

        if ($intro === '') {
            $errors[] = 'Every thematic area must have an introduction.';
        } elseif (strlen($intro) > 20000) {
            $errors[] = 'Thematic area introduction is too long.';
        }

        $rawPoints = $area['points'] ?? [];
        if (is_string($rawPoints)) {
            $rawPoints = preg_split('/\r\n|\r|\n/', $rawPoints);
        }
        $points = [];
        if (is_array($rawPoints)) {
            foreach ($rawPoints as $point) {
                $p = trim((string)$point);
                if ($p !== '') {
                    $points[] = $p;
                }
            }
        }

        $cleanAreas[] = [
            'title' => $title,
            'intro' => $intro,
            'points' => $points,
        ];
    }

    if (!empty($errors)) {
        throw new InvalidArgumentException(implode(' ', $errors));
    }

    $thematicJson = json_encode($cleanAreas, JSON_UNESCAPED_UNICODE);
    $pairs = [
        'thematic_areas' => $thematicJson,
        'civictech_title' => $cleanAreas[0]['title'] ?? '',
        'civictech_intro' => $cleanAreas[0]['intro'] ?? '',
        'civictech_points' => implode("\n", $cleanAreas[0]['points'] ?? []),
        'heritage_title' => $cleanAreas[1]['title'] ?? '',
        'heritage_intro' => $cleanAreas[1]['intro'] ?? '',
        'heritage_points' => implode("\n", $cleanAreas[1]['points'] ?? []),
    ];

    saveSettingKeyValuePairs($conn, $pairs);
    return $pairs;
}

function saveLeadershipSettings($conn, $leadersInput) {
    if (!is_array($leadersInput)) {
        throw new InvalidArgumentException('Submit the leadership profiles as an array.');
    }
    if (count($leadersInput) > 20) {
        throw new InvalidArgumentException('A maximum of 20 public leadership profiles can be saved.');
    }

    ensureSiteSettingsTable($conn);
    $currentSettings = getSiteSettings($conn);
    $currentLeaders = getLeadershipProfiles($currentSettings, true);
    $currentIds = array_column($currentLeaders, 'id');

    $errors = [];
    $leaders = [];
    $assetRoot = resolveAssetRoot();

    foreach ($leadersInput as $leaderId => $leaderInput) {
        if (!is_string($leaderId) || !preg_match('/^[a-z0-9_-]{1,40}$/', $leaderId) || !is_array($leaderInput)) {
            $errors[] = 'A leadership profile has an invalid identifier.';
            continue;
        }

        $leader = [];
        foreach (['name', 'role', 'role_verified', 'public_visible', 'phone', 'email', 'image', 'description'] as $field) {
            $leader[$field] = trim((string)($leaderInput[$field] ?? ''));
        }

        if ($leader['name'] === '' || strlen($leader['name']) > 150 || preg_match('/[\r\n]/', $leader['name'])) {
            $errors[] = 'Each leader needs a name of no more than 150 characters on one line.';
        }
        if (strlen($leader['role']) > 150 || preg_match('/[\r\n]/', $leader['role'])) {
            $errors[] = 'Leadership roles must fit within 150 characters on one line.';
        }
        if (strlen($leader['description']) > 50000) {
            $errors[] = 'Full description must be under 50,000 characters for ' . $leader['name'] . '.';
        }
        if (!in_array($leader['role_verified'], ['0', '1'], true)) {
            $errors[] = 'Choose whether the role is verified for ' . $leader['name'] . '.';
        }
        if (!in_array($leader['public_visible'], ['0', '1'], true)) {
            $errors[] = 'Choose whether the profile should be displayed publicly for ' . $leader['name'] . '.';
        }
        if ($leader['phone'] !== '' && !preg_match('/^\+?[0-9][0-9\s().-]{5,24}$/', $leader['phone'])) {
            $errors[] = 'Use a valid public phone number for ' . $leader['name'] . '.';
        }
        if ($leader['email'] !== '' && !filter_var($leader['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Use a valid public email address for ' . $leader['name'] . '.';
        }

        if ($leader['image'] !== '') {
            $extension = strtolower(pathinfo($leader['image'], PATHINFO_EXTENSION));
            $isSafePath = strpos($leader['image'], 'assets/') === 0 && strpos($leader['image'], '..') === false;
            $hasValidExt = in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true);

            $isValidFile = false;
            if ($isSafePath && $hasValidExt) {
                if ($assetRoot) {
                    $relativeUnderAssets = substr($leader['image'], 7);
                    $candidate1 = $assetRoot . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativeUnderAssets);
                    $candidate2 = dirname($assetRoot) . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $leader['image']);
                    
                    $real1 = file_exists($candidate1) ? realpath($candidate1) : false;
                    $real2 = file_exists($candidate2) ? realpath($candidate2) : false;
                    
                    if ($real1 && stripos($real1, $assetRoot) === 0) {
                        $isValidFile = true;
                    } elseif ($real2 && stripos($real2, $assetRoot) === 0) {
                        $isValidFile = true;
                    } else {
                        // Allow existing or valid path pattern
                        $isValidFile = true;
                    }
                } else {
                    $isValidFile = true;
                }
            }

            if (!$isSafePath || !$hasValidExt || !$isValidFile) {
                $errors[] = 'Upload a JPG, PNG, or WebP portrait for ' . $leader['name'] . '.';
            }
        }

        $leader['id'] = $leaderId;
        $leaders[] = $leader;
    }

    if (!empty($errors)) {
        throw new InvalidArgumentException(implode(' ', $errors));
    }

    $leaderIds = [];
    $values = [];
    foreach ($leaders as $leader) {
        $leaderIds[] = $leader['id'];
        foreach (['name', 'role', 'role_verified', 'public_visible', 'phone', 'email', 'image', 'description'] as $field) {
            $values['leader_' . $leader['id'] . '_' . $field] = $leader[$field];
        }
    }
    $values['leadership_ids'] = json_encode($leaderIds);

    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare('INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
        foreach ($values as $key => $value) {
            $strVal = (string)$value;
            $stmt->bind_param('ss', $key, $strVal);
            $stmt->execute();
        }
        $stmt->close();

        $deleteStmt = $conn->prepare('DELETE FROM site_settings WHERE setting_key = ?');
        $leaderFields = ['name', 'role', 'role_verified', 'public_visible', 'phone', 'email', 'image', 'description'];
        foreach ($currentIds as $oldId) {
            if (in_array($oldId, $leaderIds, true)) {
                continue;
            }
            foreach ($leaderFields as $field) {
                $key = 'leader_' . $oldId . '_' . $field;
                $deleteStmt->bind_param('s', $key);
                $deleteStmt->execute();
            }
        }
        $deleteStmt->close();
        $conn->commit();
    } catch (Throwable $e) {
        $conn->rollback();
        error_log('Could not save leadership profiles: ' . $e->getMessage());
        throw new RuntimeException('Leadership profiles could not be saved due to a database error.');
    }

    return $values;
}

function saveSiteSettings($conn, $input, $leadersInput = null) {
    $results = [];
    if (is_array($input)) {
        if (isset($input['site_name'])) {
            $results = array_merge($results, saveOrganizationSettings($conn, $input));
        }
        if (isset($input['social_facebook'])) {
            $results = array_merge($results, saveSocialSettings($conn, $input));
        }
        if (isset($input['vision'])) {
            $results = array_merge($results, saveVisionSettings($conn, $input));
        }
        if (isset($input['thematic_areas']) || isset($input['civictech_title'])) {
            $results = array_merge($results, saveThematicSettings($conn, $input));
        }
    }
    if (is_array($leadersInput)) {
        $results = array_merge($results, saveLeadershipSettings($conn, $leadersInput));
    }
    return $results;
}

function safeHtml($html) {
    if (empty($html)) return '';

    $allowed_tags = '<p><br><strong><b><em><i><u><ul><ol><li><blockquote><h2><h3><h4><a>';
    $html = strip_tags((string)$html, $allowed_tags);
    $html = preg_replace_callback('/<a\b([^>]*)>/i', function ($matches) {
        if (!preg_match('/\bhref\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|([^\s>]+))/i', $matches[1], $href_match)) {
            return '<a>';
        }

        $href = html_entity_decode($href_match[1] ?? $href_match[2] ?? $href_match[3], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if (strpos($href, '//') === 0 || !preg_match('~^(?:https?://|mailto:|tel:|/|#)~i', $href)) {
            return '<a>';
        }

        return '<a href="' . htmlspecialchars($href, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '" rel="nofollow noopener noreferrer">';
    }, $html);

    return preg_replace_callback('/<(\/?)(p|br|strong|b|em|i|u|ul|ol|li|blockquote|h[2-4])\b[^>]*>/i', function ($matches) {
        return '<' . $matches[1] . strtolower($matches[2]) . '>';
    }, $html);
}


function getHtmlExcerpt($html, $limit = 140) {
    if (empty($html)) return '';
    $spaced = preg_replace('/<\/?(?:p|h[1-6]|li|ul|ol|br|div|blockquote)[^>]*>/i', ' ', $html);
    $plain = strip_tags($spaced);
    $plain = html_entity_decode($plain, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $plain = str_replace("\xc2\xa0", ' ', $plain);
    $plain = preg_replace('/\s+/', ' ', trim($plain));
    return mb_strimwidth($plain, 0, $limit, '…');
}


function renderFrontendVideo($video_url) {
    if (empty($video_url)) {
        return '';
    }
    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url, $match)) {
        $video_id = $match[1];
        return '<div class="video-wrapper" style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden; border-radius:var(--radius-lg); border:1px solid var(--outline-variant); background:#000; margin-bottom:16px;">
            <iframe src="https://www.youtube.com/embed/' . h($video_id) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position:absolute; top:0; left:0; width:100%; height:100%;"></iframe>
        </div>';
    }
    if (preg_match('%vimeo\.com/(?:channels/(?:\w+\/)?|groups/([^/]*)/videos/|album/(\d+)/video/|video/|)(\d+)(?:$|\/|\?)%i', $video_url, $match)) {
        $video_id = $match[3];
        return '<div class="video-wrapper" style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden; border-radius:var(--radius-lg); border:1px solid var(--outline-variant); background:#000; margin-bottom:16px;">
            <iframe src="https://player.vimeo.com/video/' . h($video_id) . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute; top:0; left:0; width:100%; height:100%;"></iframe>
        </div>';
    }
    return '<div class="video-wrapper" style="margin-bottom:16px;">
        <video src="' . h($video_url) . '" controls style="width:100%; max-height:400px; display:block; border-radius:var(--radius-lg); border:1px solid var(--outline-variant); background:#000;"></video>
    </div>';
}

function renderFeaturedMediaGallery($featured_media_json) {
    if (empty($featured_media_json)) {
        return '';
    }

    $media = json_decode($featured_media_json, true);
    if (empty($media) || !is_array($media)) {
        return '';
    }

    $html = '<div class="featured-media-gallery-wrapper">';
    $html .= '<h4 class="poppins-bold" style="font-size: 14px; margin-bottom: 12px; color: var(--primary); display: flex; align-items: center; gap: 6px;">';
    $html .= '<span class="material-symbols-outlined" style="font-size: 18px; vertical-align: middle;">photo_library</span> Featured Gallery</h4>';
    $html .= '<div class="featured-media-scroll-container">';

    foreach ($media as $item) {
        $html .= '<div class="featured-media-item">';

        $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
        if (in_array($ext, ['mp4', 'webm', 'ogg', 'mov'])) {
            $html .= '<video src="' . h($item) . '" controls></video>';
        } else {
            $html .= '<img src="' . h($item) . '" alt="Featured Media" onclick="openFeaturedMediaLightbox(this.src)"/>';
        }

        $html .= '</div>';
    }

    $html .= '</div>';
    $html .= '</div>';
    
    $html .= '<script>
        if (typeof openFeaturedMediaLightbox !== "function") {
            function openFeaturedMediaLightbox(src) {
                let lightbox = document.getElementById("featured-media-lightbox-modal");
                if (!lightbox) {
                    lightbox = document.createElement("div");
                    lightbox.id = "featured-media-lightbox-modal";
                    lightbox.className = "featured-media-lightbox";
                    lightbox.innerHTML = `
                        <button class="featured-media-lightbox-close" onclick="closeFeaturedMediaLightbox()">&times;</button>
                        <img class="featured-media-lightbox-content" id="featured-media-lightbox-img" src="" alt="Zoomed Media"/>
                    `;
                    document.body.appendChild(lightbox);
                    lightbox.addEventListener("click", function(e) {
                        if (e.target === lightbox) {
                            closeFeaturedMediaLightbox();
                        }
                    });
                    document.addEventListener("keydown", function(e) {
                        if (e.key === "Escape") {
                            closeFeaturedMediaLightbox();
                        }
                    });
                }
                const img = document.getElementById("featured-media-lightbox-img");
                if (img) {
                    img.src = src;
                }
                lightbox.classList.add("active");
                document.body.style.overflow = "hidden";
            }
            function closeFeaturedMediaLightbox() {
                const lightbox = document.getElementById("featured-media-lightbox-modal");
                if (lightbox) {
                    lightbox.classList.remove("active");
                    document.body.style.overflow = "";
                }
            }
        }
    </script>';

    return $html;
}




function getProjects($conn, $category = 'all') {
    $sql = "SELECT p.*, c.name AS category FROM projects p LEFT JOIN categories c ON p.category_id = c.id WHERE p.published = 1";
    if ($category !== 'all') {
        $cat = dbEscape($conn, $category);
        $sql .= " AND c.name = '$cat'";
    }
    $sql .= " ORDER BY CASE WHEN LOWER(c.name) = 'children' THEN 0 ELSE 1 END ASC, p.created_at DESC";

    $result = $conn->query($sql);
    $projects = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
    }
    return $projects;
}

function getGalleryItems($conn, $category = 'all') {
    $sql = "SELECT g.*, c.name AS category FROM gallery_items g LEFT JOIN categories c ON g.category_id = c.id WHERE g.published = 1";
    if ($category !== 'all') {
        $cat = dbEscape($conn, $category);
        $sql .= " AND c.name = '$cat'";
    }
    $sql .= " ORDER BY CASE WHEN LOWER(c.name) = 'children' THEN 0 ELSE 1 END ASC, g.created_at DESC";

    $result = $conn->query($sql);
    $items = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    return $items;
}


function getNewsArticles($conn, $category = 'all') {
    $sql = "SELECT n.*, c.name AS category FROM news_articles n LEFT JOIN categories c ON n.category_id = c.id WHERE n.published = 1";
    if ($category !== 'all') {
        $cat = dbEscape($conn, $category);
        $sql .= " AND c.name = '$cat'";
    }
    $sql .= " ORDER BY n.published_date DESC, n.created_at DESC";

    $result = $conn->query($sql);
    $articles = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $articles[] = $row;
        }
    }
    return $articles;
}

function getUpcomingEvents($conn) {
   $sql = "SELECT *
        FROM upcoming_events
        WHERE published = 1
        AND event_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        ORDER BY event_date ASC";
    $result = $conn->query($sql);
    $events = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    return $events;
}


function getDistinctCategories($conn, $table) {
    $allowed = ['projects', 'gallery_items', 'news_articles'];
    if (!in_array($table, $allowed, true)) {
        return [];
    }
    $result = $conn->query("SELECT DISTINCT c.name AS category FROM `$table` t JOIN categories c ON t.category_id = c.id ORDER BY c.name ASC");
    $cats = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cats[] = $row['category'];
        }
    }
    return $cats;
}


function getOrCreateCategoryId($conn, $category_name) {
    $cat = dbEscape($conn, trim($category_name));
    if (empty($cat)) return null;
    $res = $conn->query("SELECT id FROM categories WHERE name = '$cat'");
    if ($res && $res->num_rows > 0) {
        return $res->fetch_row()[0];
    }
    $conn->query("INSERT INTO categories (name) VALUES ('$cat')");
    return $conn->insert_id;
}

function logActivity($conn, $activity, $details = null, $user_type = null, $user_id = null, $username = null) {
    startAppSession();

    if ($user_type === null) {
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
            $user_type = 'admin';
            $user_id = $_SESSION['admin_id'] ?? null;
            $username = $_SESSION['admin_username'] ?? 'admin';
        } else {
            $user_type = 'system';
            $username = 'System';
        }
    }

    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    $full_details = "";
    if ($details !== null) {
        $full_details = $details;
    }
    if (!empty($user_agent)) {
        if ($full_details !== "") {
            $full_details .= " | ";
        }
        $full_details .= "UA: " . $user_agent;
    }

    $sql = "INSERT INTO activity_logs (user_type, user_id, username, activity, details, ip_address) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sissss", $user_type, $user_id, $username, $activity, $full_details, $ip_address);
        $stmt->execute();
        $stmt->close();
    }
}

// ==============================================================================
// DYNAMIC CMS & DATABASE-DRIVEN CONTENT MODULES (CRUD & DATA RETRIEVAL)
// ==============================================================================

function tableExists($conn, $tableName) {
    if (!$conn) return false;
    $tableName = preg_replace('/[^a-zA-Z0-9_]/', '', (string)$tableName);
    $res = $conn->query("SHOW TABLES LIKE '$tableName'");
    return ($res && $res->num_rows > 0);
}

function columnExists($conn, $tableName, $columnName) {
    if (!$conn || !tableExists($conn, $tableName)) return false;
    $tableName = preg_replace('/[^a-zA-Z0-9_]/', '', (string)$tableName);
    $columnName = preg_replace('/[^a-zA-Z0-9_]/', '', (string)$columnName);
    $res = $conn->query("SHOW COLUMNS FROM `$tableName` LIKE '$columnName'");
    return ($res && $res->num_rows > 0);
}

// ------------------------------------------------------------------------------
// 1. THEMATIC AREAS MODULE
// ------------------------------------------------------------------------------

function getThematicAreas($conn, $publishedOnly = true) {
    if (!$conn || !tableExists($conn, 'thematic_areas')) {
        $settings = getSiteSettings($conn);
        if (!empty($settings['thematic_areas'])) {
            $decoded = json_decode($settings['thematic_areas'], true);
            if (is_array($decoded) && !empty($decoded)) {
                $areas = [];
                foreach ($decoded as $idx => $d) {
                    $areas[] = [
                        'id' => $idx + 1,
                        'title' => $d['title'] ?? '',
                        'description' => $d['intro'] ?? ($d['description'] ?? ''),
                        'icon' => $d['icon'] ?? ($idx === 0 ? 'account_balance' : 'museum'),
                        'image_url' => $d['image_url'] ?? '',
                        'points' => is_array($d['points'] ?? null) ? implode("\n", $d['points']) : (string)($d['points'] ?? ''),
                        'points_array' => is_array($d['points'] ?? null) ? $d['points'] : siteSettingLines($d['points'] ?? ''),
                        'display_order' => $idx + 1,
                        'published' => 1
                    ];
                }
                return $areas;
            }
        }
        return [
            [
                'id' => 1,
                'title' => $settings['civictech_title'] ?? 'CivicTech Awareness',
                'description' => $settings['civictech_intro'] ?? 'Civic technology education and digital participation.',
                'icon' => 'account_balance',
                'image_url' => '',
                'points' => $settings['civictech_points'] ?? '',
                'points_array' => siteSettingLines($settings['civictech_points'] ?? ''),
                'display_order' => 1,
                'published' => 1
            ],
            [
                'id' => 2,
                'title' => $settings['heritage_title'] ?? 'Culture and Heritage',
                'description' => $settings['heritage_intro'] ?? 'Community-led approaches to documenting and preserving African heritage.',
                'icon' => 'museum',
                'image_url' => '',
                'points' => $settings['heritage_points'] ?? '',
                'points_array' => siteSettingLines($settings['heritage_points'] ?? ''),
                'display_order' => 2,
                'published' => 1
            ],
        ];
    }

    $sql = "SELECT * FROM thematic_areas";
    if ($publishedOnly) {
        $sql .= " WHERE published = 1";
    }
    $sql .= " ORDER BY display_order ASC, id ASC";

    $result = $conn->query($sql);
    $areas = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rawPoints = $row['points'] ?? '';
            $row['points_array'] = is_array(json_decode($rawPoints, true)) ? json_decode($rawPoints, true) : siteSettingLines($rawPoints);
            $areas[] = $row;
        }
    }
    return $areas;
}

function getThematicAreaById($conn, $id) {
    if (!$conn || !tableExists($conn, 'thematic_areas')) return null;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM thematic_areas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();
    if ($row) {
        $rawPoints = $row['points'] ?? '';
        $row['points_array'] = is_array(json_decode($rawPoints, true)) ? json_decode($rawPoints, true) : siteSettingLines($rawPoints);
    }
    return $row;
}

function saveThematicAreaRecord($conn, $data) {
    $title = trim((string)($data['title'] ?? ''));
    $desc = trim((string)($data['description'] ?? ($data['intro'] ?? '')));
    $icon = trim((string)($data['icon'] ?? 'account_balance'));
    $imageUrl = trim((string)($data['image_url'] ?? ''));
    $displayOrder = (int)($data['display_order'] ?? 0);
    $published = isset($data['published']) && (string)$data['published'] === '0' ? 0 : 1;

    $rawPoints = $data['points'] ?? [];
    if (is_array($rawPoints)) {
        $points = implode("\n", array_filter(array_map('trim', $rawPoints)));
    } else {
        $points = trim((string)$rawPoints);
    }

    if ($title === '') {
        throw new InvalidArgumentException('Thematic area title is required.');
    }
    if (strlen($title) > 180) {
        throw new InvalidArgumentException('Thematic area title must be 180 characters or fewer.');
    }
    if ($desc === '') {
        throw new InvalidArgumentException('Thematic area description is required.');
    }

    $id = isset($data['id']) && (int)$data['id'] > 0 ? (int)$data['id'] : null;

    if ($id) {
        $sql = "UPDATE thematic_areas SET title = ?, description = ?, icon = ?, image_url = ?, points = ?, display_order = ?, published = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssiii", $title, $desc, $icon, $imageUrl, $points, $displayOrder, $published, $id);
    } else {
        $sql = "INSERT INTO thematic_areas (title, description, icon, image_url, points, display_order, published) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssii", $title, $desc, $icon, $imageUrl, $points, $displayOrder, $published);
    }

    if (!$stmt) {
        throw new RuntimeException('Failed to prepare thematic area statement: ' . $conn->error);
    }
    $success = $stmt->execute();
    if (!$success) {
        throw new RuntimeException('Failed to save thematic area: ' . $stmt->error);
    }
    if (!$id) {
        $id = $stmt->insert_id;
    }
    $stmt->close();

    // Synchronize to site_settings for legacy backward compatibility
    syncThematicAreasToSettings($conn);

    return $id;
}

function deleteThematicAreaRecord($conn, $id) {
    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM thematic_areas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    syncThematicAreasToSettings($conn);
    return true;
}

function syncThematicAreasToSettings($conn) {
    if (!tableExists($conn, 'site_settings') || !tableExists($conn, 'thematic_areas')) return;
    $areas = getThematicAreas($conn, false);
    $thematicData = [];
    foreach ($areas as $a) {
        $thematicData[] = [
            'id' => $a['id'],
            'title' => $a['title'],
            'intro' => $a['description'],
            'icon' => $a['icon'],
            'points' => $a['points_array']
        ];
    }
    $pairs = [
        'thematic_areas' => json_encode($thematicData, JSON_UNESCAPED_UNICODE),
        'civictech_title' => $areas[0]['title'] ?? '',
        'civictech_intro' => $areas[0]['description'] ?? '',
        'civictech_points' => $areas[0]['points'] ?? '',
        'heritage_title' => $areas[1]['title'] ?? '',
        'heritage_intro' => $areas[1]['description'] ?? '',
        'heritage_points' => $areas[1]['points'] ?? '',
    ];
    saveSettingKeyValuePairs($conn, $pairs);
}

// ------------------------------------------------------------------------------
// 2. ORGANISATIONAL OBJECTIVES MODULE
// ------------------------------------------------------------------------------

function getObjectives($conn, $publishedOnly = true) {
    if (!$conn || !tableExists($conn, 'objectives')) {
        $settings = getSiteSettings($conn);
        $lines = siteSettingLines($settings['objectives'] ?? '');
        $objs = [];
        foreach ($lines as $idx => $line) {
            $objs[] = [
                'id' => $idx + 1,
                'title' => $line,
                'description' => '',
                'category' => 'Strategic',
                'display_order' => $idx + 1,
                'published' => 1
            ];
        }
        return $objs;
    }

    $sql = "SELECT * FROM objectives";
    if ($publishedOnly) {
        $sql .= " WHERE published = 1";
    }
    $sql .= " ORDER BY display_order ASC, id ASC";

    $result = $conn->query($sql);
    $objs = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $objs[] = $row;
        }
    }
    return $objs;
}

function getObjectiveById($conn, $id) {
    if (!$conn || !tableExists($conn, 'objectives')) return null;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM objectives WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();
    return $row;
}

function saveObjectiveRecord($conn, $data) {
    $title = trim((string)($data['title'] ?? ''));
    $desc = trim((string)($data['description'] ?? ''));
    $cat = trim((string)($data['category'] ?? 'Strategic'));
    $displayOrder = (int)($data['display_order'] ?? 0);
    $published = isset($data['published']) && (string)$data['published'] === '0' ? 0 : 1;

    if ($title === '') {
        throw new InvalidArgumentException('Objective title is required.');
    }

    $id = isset($data['id']) && (int)$data['id'] > 0 ? (int)$data['id'] : null;

    if ($id) {
        $sql = "UPDATE objectives SET title = ?, description = ?, category = ?, display_order = ?, published = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiii", $title, $desc, $cat, $displayOrder, $published, $id);
    } else {
        $sql = "INSERT INTO objectives (title, description, category, display_order, published) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $title, $desc, $cat, $displayOrder, $published);
    }

    if (!$stmt) throw new RuntimeException('Failed to prepare objective statement: ' . $conn->error);
    $stmt->execute();
    if (!$id) $id = $stmt->insert_id;
    $stmt->close();

    // Sync to site_settings objectives for backward compatibility
    if (tableExists($conn, 'site_settings')) {
        $all = getObjectives($conn, true);
        $lines = array_map(function($o) { return $o['title']; }, $all);
        saveSettingKeyValuePairs($conn, ['objectives' => implode("\n", $lines)]);
    }

    return $id;
}

function deleteObjectiveRecord($conn, $id) {
    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM objectives WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    return true;
}

// ------------------------------------------------------------------------------
// 3. PUBLICATIONS AND REPORTS MODULE
// ------------------------------------------------------------------------------

function formatPublicationRow($row) {
    if (!$row) return null;
    $row['file_path'] = $row['file_url'] ?? ($row['file_path'] ?? '');
    $row['file_url'] = $row['file_path'];
    $row['cover_image'] = $row['cover_image_url'] ?? ($row['cover_image'] ?? '');
    $row['cover_image_url'] = $row['cover_image'];
    $row['summary'] = $row['description'] ?? ($row['summary'] ?? '');
    $row['description'] = $row['summary'];
    $row['published_date'] = $row['publication_date'] ?? ($row['published_date'] ?? date('Y-m-d'));
    $row['publication_date'] = $row['published_date'];
    if (!isset($row['author']) || $row['author'] === '') {
        $row['author'] = 'BuniAfrika Research Team';
    }
    return $row;
}

function getPublications($conn, $publishedOnly = true, $category = null) {
    if (!$conn || !tableExists($conn, 'publications')) return [];

    $sql = "SELECT * FROM publications WHERE 1=1";
    if ($publishedOnly) {
        $sql .= " AND published = 1";
    }
    if (!empty($category) && $category !== 'all') {
        $catEsc = dbEscape($conn, $category);
        $sql .= " AND category = '$catEsc'";
    }
    $sql .= " ORDER BY publication_date DESC, display_order ASC, id DESC";

    $result = $conn->query($sql);
    $pubs = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $pubs[] = formatPublicationRow($row);
        }
    }
    return $pubs;
}

function getPublicationById($conn, $id) {
    if (!$conn || !tableExists($conn, 'publications')) return null;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM publications WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();
    return formatPublicationRow($row);
}

function savePublicationRecord($conn, $data) {
    $id = isset($data['id']) && (int)$data['id'] > 0 ? (int)$data['id'] : null;

    $title = trim((string)($data['title'] ?? ''));
    $desc = trim((string)($data['description'] ?? ($data['summary'] ?? '')));
    $author = trim((string)($data['author'] ?? 'BuniAfrika Research Team'));
    $cat = trim((string)($data['category'] ?? 'Annual Report'));
    $fileUrl = trim((string)($data['file_url'] ?? ($data['file_path'] ?? '')));
    $fileSize = trim((string)($data['file_size'] ?? ''));
    $fileType = trim((string)($data['file_type'] ?? 'PDF'));
    $coverImage = trim((string)($data['cover_image_url'] ?? ($data['cover_image'] ?? '')));
    $pubDate = trim((string)($data['publication_date'] ?? ($data['published_date'] ?? date('Y-m-d'))));
    $pubYear = (int)($data['publication_year'] ?? date('Y', strtotime($pubDate)));
    $displayOrder = (int)($data['display_order'] ?? 0);
    $published = isset($data['published']) && (string)$data['published'] === '0' ? 0 : 1;

    // When updating an existing publication, retain previous file or cover if not re-uploaded
    if ($id) {
        $existing = getPublicationById($conn, $id);
        if ($existing) {
            if ($fileUrl === '') {
                $fileUrl = $existing['file_url'] ?? ($existing['file_path'] ?? '');
            }
            if ($fileSize === '') {
                $fileSize = $existing['file_size'] ?? '';
            }
            if ($fileType === '') {
                $fileType = $existing['file_type'] ?? 'PDF';
            }
            if ($coverImage === '') {
                $coverImage = $existing['cover_image_url'] ?? ($existing['cover_image'] ?? '');
            }
            if ($author === '' && !empty($existing['author'])) {
                $author = $existing['author'];
            }
        }
    }

    if ($title === '') {
        throw new InvalidArgumentException('Publication title is required.');
    }
    if ($fileUrl === '') {
        throw new InvalidArgumentException('Publication document file is required.');
    }

    $hasAuthor = columnExists($conn, 'publications', 'author');

    if ($id) {
        if ($hasAuthor) {
            $sql = "UPDATE publications SET title = ?, description = ?, author = ?, category = ?, file_url = ?, file_size = ?, file_type = ?, cover_image_url = ?, publication_date = ?, publication_year = ?, display_order = ?, published = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssiiii", $title, $desc, $author, $cat, $fileUrl, $fileSize, $fileType, $coverImage, $pubDate, $pubYear, $displayOrder, $published, $id);
        } else {
            $sql = "UPDATE publications SET title = ?, description = ?, category = ?, file_url = ?, file_size = ?, file_type = ?, cover_image_url = ?, publication_date = ?, publication_year = ?, display_order = ?, published = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssiiii", $title, $desc, $cat, $fileUrl, $fileSize, $fileType, $coverImage, $pubDate, $pubYear, $displayOrder, $published, $id);
        }
    } else {
        if ($hasAuthor) {
            $sql = "INSERT INTO publications (title, description, author, category, file_url, file_size, file_type, cover_image_url, publication_date, publication_year, display_order, published) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssssiii", $title, $desc, $author, $cat, $fileUrl, $fileSize, $fileType, $coverImage, $pubDate, $pubYear, $displayOrder, $published);
        } else {
            $sql = "INSERT INTO publications (title, description, category, file_url, file_size, file_type, cover_image_url, publication_date, publication_year, display_order, published) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssiii", $title, $desc, $cat, $fileUrl, $fileSize, $fileType, $coverImage, $pubDate, $pubYear, $displayOrder, $published);
        }
    }

    if (!$stmt) throw new RuntimeException('Failed to prepare publication statement: ' . $conn->error);
    $stmt->execute();
    if (!$id) $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}

function deletePublicationRecord($conn, $id) {
    $id = (int)$id;
    $pub = getPublicationById($conn, $id);
    if ($pub && !empty($pub['file_url'])) {
        $assetRoot = resolveAssetRoot();
        if ($assetRoot && strpos($pub['file_url'], 'assets/') === 0 && strpos($pub['file_url'], '..') === false) {
            $fullPath = dirname($assetRoot) . '/' . $pub['file_url'];
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }
    }
    $stmt = $conn->prepare("DELETE FROM publications WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    return true;
}

function incrementPublicationDownload($conn, $id) {
    if (!$conn || !tableExists($conn, 'publications')) return false;
    $id = (int)$id;
    $stmt = $conn->prepare("UPDATE publications SET download_count = download_count + 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    return true;
}

function uploadAdminPublicationPdf($file) {
    if (!isset($file) || !isset($file['error'])) {
        return ['status' => 'error', 'message' => 'No file was provided for publication upload.'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $phpUploadErrors = [
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the server upload limit (upload_max_filesize).',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the form MAX_FILE_SIZE limit.',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder on server.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk on server.',
            UPLOAD_ERR_EXTENSION => 'A server extension stopped the file upload.',
        ];
        $msg = $phpUploadErrors[$file['error']] ?? ('File upload failed with error code ' . (int)$file['error'] . '.');
        return ['status' => 'error', 'message' => $msg];
    }

    $maxBytes = 50 * 1024 * 1024; // 50MB
    if ($file['size'] > $maxBytes) {
        return ['status' => 'error', 'message' => 'File size exceeds maximum permitted limit of 50MB.'];
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($ext !== 'pdf') {
        return ['status' => 'error', 'message' => 'Only PDF files (.pdf) are allowed for publications.'];
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    if ($mimeType !== 'application/pdf') {
        return ['status' => 'error', 'message' => 'Invalid file contents. The uploaded document is not a valid PDF.'];
    }

    $assetRoot = resolveAssetRoot();
    $targetDir = ($assetRoot ? $assetRoot : dirname(__DIR__) . '/assets') . '/uploads/publications';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $uniqueName = 'pub_' . bin2hex(random_bytes(16)) . '.pdf';
    $targetPath = $targetDir . '/' . $uniqueName;

    $moved = is_uploaded_file($file['tmp_name'])
        ? move_uploaded_file($file['tmp_name'], $targetPath)
        : (copy($file['tmp_name'], $targetPath) && @unlink($file['tmp_name']));

    if (!$moved) {
        return ['status' => 'error', 'message' => 'Failed to save publication document to server. Check directory permissions.'];
    }

    // Format human-readable file size
    $bytes = filesize($targetPath);
    $formattedSize = $bytes >= 1048576 ? round($bytes / 1048576, 2) . ' MB' : round($bytes / 1024, 1) . ' KB';

    return [
        'status' => 'success',
        'file_url' => 'assets/uploads/publications/' . $uniqueName,
        'file_path' => 'assets/uploads/publications/' . $uniqueName,
        'file_size' => $formattedSize,
        'file_type' => 'PDF',
    ];
}

// ------------------------------------------------------------------------------
// 4. TESTIMONIALS MODULE
// ------------------------------------------------------------------------------

function getTestimonials($conn, $publishedOnly = true) {
    if (!$conn || !tableExists($conn, 'testimonials')) return [];

    $sql = "SELECT * FROM testimonials";
    if ($publishedOnly) {
        $sql .= " WHERE published = 1";
    }
    $sql .= " ORDER BY display_order ASC, id DESC";

    $result = $conn->query($sql);
    $items = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    return $items;
}

function getTestimonialById($conn, $id) {
    if (!$conn || !tableExists($conn, 'testimonials')) return null;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();
    return $row;
}

function saveTestimonialRecord($conn, $data) {
    $author = trim((string)($data['author_name'] ?? ''));
    $desig = trim((string)($data['designation'] ?? ($data['author_role'] ?? '')));
    $org = trim((string)($data['organization'] ?? ($data['author_organization'] ?? '')));
    $text = trim((string)($data['testimonial_text'] ?? ($data['quote'] ?? '')));
    $image = trim((string)($data['author_image_url'] ?? ($data['author_image'] ?? '')));
    $displayOrder = (int)($data['display_order'] ?? 0);
    $published = isset($data['published']) && (string)$data['published'] === '0' ? 0 : 1;

    if ($author === '') {
        throw new InvalidArgumentException('Testimonial author name is required.');
    }
    if ($text === '') {
        throw new InvalidArgumentException('Testimonial text is required.');
    }

    $id = isset($data['id']) && (int)$data['id'] > 0 ? (int)$data['id'] : null;

    if ($id) {
        $sql = "UPDATE testimonials SET author_name = ?, designation = ?, organization = ?, testimonial_text = ?, author_image_url = ?, display_order = ?, published = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssiii", $author, $desig, $org, $text, $image, $displayOrder, $published, $id);
    } else {
        $sql = "INSERT INTO testimonials (author_name, designation, organization, testimonial_text, author_image_url, display_order, published) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssii", $author, $desig, $org, $text, $image, $displayOrder, $published);
    }

    if (!$stmt) throw new RuntimeException('Failed to prepare testimonial statement: ' . $conn->error);
    $stmt->execute();
    if (!$id) $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}

function deleteTestimonialRecord($conn, $id) {
    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM testimonials WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    return true;
}

// ------------------------------------------------------------------------------
// 5. FREQUENTLY ASKED QUESTIONS (FAQS) MODULE
// ------------------------------------------------------------------------------

function getFaqs($conn, $publishedOnly = true, $category = null) {
    if (!$conn || !tableExists($conn, 'faqs')) return [];

    $sql = "SELECT * FROM faqs WHERE 1=1";
    if ($publishedOnly) {
        $sql .= " AND published = 1";
    }
    if (!empty($category) && $category !== 'all') {
        $catEsc = dbEscape($conn, $category);
        $sql .= " AND category = '$catEsc'";
    }
    $sql .= " ORDER BY display_order ASC, id ASC";

    $result = $conn->query($sql);
    $faqs = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $faqs[] = $row;
        }
    }
    return $faqs;
}

function getFaqById($conn, $id) {
    if (!$conn || !tableExists($conn, 'faqs')) return null;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM faqs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();
    return $row;
}

function getFaqCategories($conn) {
    if (!$conn || !tableExists($conn, 'faqs')) return ['General', 'Programmes', 'Partnerships', 'Volunteering'];
    $res = $conn->query("SELECT DISTINCT category FROM faqs WHERE category IS NOT NULL AND category != '' ORDER BY category ASC");
    $cats = [];
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $cats[] = $r['category'];
        }
    }
    return $cats ?: ['General', 'Programmes', 'Partnerships', 'Volunteering'];
}

function saveFaqRecord($conn, $data) {
    $q = trim((string)($data['question'] ?? ''));
    $a = trim((string)($data['answer'] ?? ''));
    $cat = trim((string)($data['category'] ?? 'General'));
    $displayOrder = (int)($data['display_order'] ?? 0);
    $published = isset($data['published']) && (string)$data['published'] === '0' ? 0 : 1;
    $detailed = trim((string)($data['detailed_answer'] ?? ''));
    $pageSlug = trim((string)($data['page_slug'] ?? ''));
    $linkUrl = trim((string)($data['related_link_url'] ?? ''));
    $linkText = trim((string)($data['related_link_text'] ?? ''));

    if ($q === '') {
        throw new InvalidArgumentException('FAQ question is required.');
    }
    if ($a === '') {
        throw new InvalidArgumentException('FAQ answer is required.');
    }

    $id = isset($data['id']) && (int)$data['id'] > 0 ? (int)$data['id'] : null;

    if ($id) {
        $sql = "UPDATE faqs SET question = ?, answer = ?, detailed_answer = ?, page_slug = ?, related_link_url = ?, related_link_text = ?, category = ?, display_order = ?, published = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssiii", $q, $a, $detailed, $pageSlug, $linkUrl, $linkText, $cat, $displayOrder, $published, $id);
    } else {
        $sql = "INSERT INTO faqs (question, answer, detailed_answer, page_slug, related_link_url, related_link_text, category, display_order, published) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssii", $q, $a, $detailed, $pageSlug, $linkUrl, $linkText, $cat, $displayOrder, $published);
    }

    if (!$stmt) throw new RuntimeException('Failed to prepare FAQ statement: ' . $conn->error);
    $stmt->execute();
    if (!$id) $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}

function deleteFaqRecord($conn, $id) {
    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM faqs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    return true;
}

// ------------------------------------------------------------------------------
// 6. PARTNERSHIPS MODULE
// ------------------------------------------------------------------------------

function getPartners($conn, $publishedOnly = true) {
    if (!$conn || !tableExists($conn, 'partners')) return [];

    $sql = "SELECT * FROM partners";
    if ($publishedOnly) {
        $sql .= " WHERE published = 1";
    }
    $sql .= " ORDER BY display_order ASC, id ASC";

    $result = $conn->query($sql);
    $partners = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $partners[] = $row;
        }
    }
    return $partners;
}

function getPartnerById($conn, $id) {
    if (!$conn || !tableExists($conn, 'partners')) return null;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM partners WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();
    return $row;
}

function savePartnerRecord($conn, $data) {
    $name = trim((string)($data['name'] ?? ''));
    $logo = trim((string)($data['logo_url'] ?? ''));
    $website = trim((string)($data['website_url'] ?? ''));
    $desc = trim((string)($data['description'] ?? ''));
    $displayOrder = (int)($data['display_order'] ?? 0);
    $published = isset($data['published']) && (string)$data['published'] === '0' ? 0 : 1;

    if ($name === '') {
        throw new InvalidArgumentException('Partner organisation name is required.');
    }
    if ($website !== '') {
        $parts = parse_url($website);
        if (strlen($website) > 2048 || !filter_var($website, FILTER_VALIDATE_URL) || !is_array($parts) || !in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true)) {
            throw new InvalidArgumentException('Use a valid URL for the partner website.');
        }
    }

    $id = isset($data['id']) && (int)$data['id'] > 0 ? (int)$data['id'] : null;

    if ($id) {
        $sql = "UPDATE partners SET name = ?, logo_url = ?, website_url = ?, description = ?, display_order = ?, published = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssiii", $name, $logo, $website, $desc, $displayOrder, $published, $id);
    } else {
        $sql = "INSERT INTO partners (name, logo_url, website_url, description, display_order, published) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $name, $logo, $website, $desc, $displayOrder, $published);
    }

    if (!$stmt) throw new RuntimeException('Failed to prepare partner statement: ' . $conn->error);
    $stmt->execute();
    if (!$id) $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}

function deletePartnerRecord($conn, $id) {
    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM partners WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    return true;
}

// ------------------------------------------------------------------------------
// 7. VOLUNTEER MANAGEMENT MODULE (OPPORTUNITIES & APPLICATIONS)
// ------------------------------------------------------------------------------

function getVolunteerOpportunities($conn, $activeOnly = false) {
    if (!$conn || !tableExists($conn, 'volunteer_opportunities')) return [];

    $sql = "SELECT * FROM volunteer_opportunities WHERE 1=1";
    if ($activeOnly) {
        $sql .= " AND published = 1 AND status = 'active'";
    }
    $sql .= " ORDER BY display_order ASC, id DESC";

    $result = $conn->query($sql);
    $opps = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $opps[] = $row;
        }
    }
    return $opps;
}

function getVolunteerOpportunityById($conn, $id) {
    if (!$conn || !tableExists($conn, 'volunteer_opportunities')) return null;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM volunteer_opportunities WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();
    return $row;
}

function saveVolunteerOpportunityRecord($conn, $data) {
    $title = trim((string)($data['title'] ?? ''));
    $desc = trim((string)($data['description'] ?? ''));
    $reqs = trim((string)($data['requirements'] ?? ''));
    $resp = trim((string)($data['responsibilities'] ?? ''));
    $loc = trim((string)($data['location'] ?? 'Mombasa / Remote'));
    $deadline = !empty($data['deadline']) ? trim((string)$data['deadline']) : null;
    $status = in_array($data['status'] ?? '', ['active', 'closed'], true) ? $data['status'] : 'active';
    $displayOrder = (int)($data['display_order'] ?? 0);
    $published = isset($data['published']) && (string)$data['published'] === '0' ? 0 : 1;

    if ($title === '') {
        throw new InvalidArgumentException('Volunteer opportunity title is required.');
    }
    if ($desc === '') {
        throw new InvalidArgumentException('Volunteer opportunity description is required.');
    }

    $id = isset($data['id']) && (int)$data['id'] > 0 ? (int)$data['id'] : null;

    if ($id) {
        $sql = "UPDATE volunteer_opportunities SET title = ?, description = ?, requirements = ?, responsibilities = ?, location = ?, deadline = ?, status = ?, display_order = ?, published = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssiii", $title, $desc, $reqs, $resp, $loc, $deadline, $status, $displayOrder, $published, $id);
    } else {
        $sql = "INSERT INTO volunteer_opportunities (title, description, requirements, responsibilities, location, deadline, status, display_order, published) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssii", $title, $desc, $reqs, $resp, $loc, $deadline, $status, $displayOrder, $published);
    }

    if (!$stmt) throw new RuntimeException('Failed to prepare volunteer opportunity statement: ' . $conn->error);
    $stmt->execute();
    if (!$id) $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}

function deleteVolunteerOpportunityRecord($conn, $id) {
    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM volunteer_opportunities WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    return true;
}

function getVolunteerApplications($conn, $opportunityId = null, $status = null) {
    if (!$conn || !tableExists($conn, 'volunteer_applications')) return [];

    $sql = "SELECT a.*, o.title AS opportunity_title 
            FROM volunteer_applications a 
            LEFT JOIN volunteer_opportunities o ON a.opportunity_id = o.id 
            WHERE 1=1";
    if ($opportunityId) {
        $sql .= " AND a.opportunity_id = " . (int)$opportunityId;
    }
    if ($status && in_array(strtolower($status), ['pending', 'reviewed', 'accepted', 'approved', 'rejected'], true)) {
        $statusLower = strtolower($status);
        if ($statusLower === 'accepted' || $statusLower === 'approved') {
            $sql .= " AND a.status IN ('accepted', 'approved')";
        } else {
            $statusEsc = dbEscape($conn, $statusLower);
            $sql .= " AND a.status = '$statusEsc'";
        }
    }
    $sql .= " ORDER BY a.created_at DESC";

    $result = $conn->query($sql);
    $apps = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['applicant_name'] = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));
            $row['applicant_email'] = $row['email'] ?? '';
            $row['applicant_phone'] = $row['phone'] ?? '';
            $row['skills'] = $row['skills_or_experience'] ?? '';
            $row['motivation'] = $row['motivation_message'] ?? '';
            $row['resume_path'] = $row['resume_url'] ?? '';
            $apps[] = $row;
        }
    }
    return $apps;
}

function getVolunteerApplicationById($conn, $id) {
    if (!$conn || !tableExists($conn, 'volunteer_applications')) return null;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT a.*, o.title AS opportunity_title 
                            FROM volunteer_applications a 
                            LEFT JOIN volunteer_opportunities o ON a.opportunity_id = o.id 
                            WHERE a.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();
    if ($row) {
        $row['applicant_name'] = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));
        $row['applicant_email'] = $row['email'] ?? '';
        $row['applicant_phone'] = $row['phone'] ?? '';
        $row['skills'] = $row['skills_or_experience'] ?? '';
        $row['motivation'] = $row['motivation_message'] ?? '';
        $row['resume_path'] = $row['resume_url'] ?? '';
    }
    return $row;
}

function submitVolunteerApplication($conn, $data) {
    $oppId = !empty($data['opportunity_id']) ? (int)$data['opportunity_id'] : null;
    $firstName = trim((string)($data['first_name'] ?? ''));
    $lastName = trim((string)($data['last_name'] ?? ''));
    if ($firstName === '' && !empty($data['applicant_name'])) {
        $parts = preg_split('/\s+/', trim((string)$data['applicant_name']), 2);
        $firstName = $parts[0] ?? '';
        $lastName = $parts[1] ?? $parts[0];
    }
    $email = trim((string)($data['email'] ?? ($data['applicant_email'] ?? '')));
    $phone = trim((string)($data['phone'] ?? ($data['applicant_phone'] ?? '')));
    $loc = trim((string)($data['location'] ?? ''));
    $skills = trim((string)($data['skills_or_experience'] ?? ($data['skills'] ?? '')));
    $message = trim((string)($data['motivation_message'] ?? ($data['motivation'] ?? '')));
    $resumeUrl = trim((string)($data['resume_url'] ?? ($data['resume_path'] ?? '')));
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';

    if ($firstName === '' || $lastName === '') {
        throw new InvalidArgumentException('First name and last name are required.');
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('A valid email address is required.');
    }
    if ($phone !== '' && !preg_match('/^\+?[0-9][0-9\s().-]{5,24}$/', $phone)) {
        throw new InvalidArgumentException('A valid phone number is required.');
    }
    if ($message === '') {
        throw new InvalidArgumentException('Please provide a brief statement of interest or motivation.');
    }

    $sql = "INSERT INTO volunteer_applications (opportunity_id, first_name, last_name, email, phone, location, skills_or_experience, motivation_message, resume_url, ip_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) throw new RuntimeException('Failed to prepare application submission: ' . $conn->error);
    $stmt->bind_param("isssssssss", $oppId, $firstName, $lastName, $email, $phone, $loc, $skills, $message, $resumeUrl, $ip);
    $success = $stmt->execute();
    if (!$success) throw new RuntimeException('Failed to save volunteer application: ' . $stmt->error);
    $appId = $stmt->insert_id;
    $stmt->close();

    return $appId;
}

function updateVolunteerApplicationStatus($conn, $id, $status) {
    $id = (int)$id;
    $status = strtolower(trim((string)$status));
    $validStatuses = ['pending', 'reviewed', 'accepted', 'approved', 'rejected'];
    if (!in_array($status, $validStatuses, true)) {
        throw new InvalidArgumentException("Invalid volunteer application status: '{$status}'. Allowed statuses are: pending, reviewed, accepted, rejected.");
    }
    $stmt = $conn->prepare("UPDATE volunteer_applications SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();
    return true;
}

function deleteVolunteerApplicationRecord($conn, $id) {
    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM volunteer_applications WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    return true;
}

// ------------------------------------------------------------------------------
// 8. OUR STORY / MILESTONES MODULE
// ------------------------------------------------------------------------------

function getOurStory($conn, $publishedOnly = true) {
    if (!$conn || !tableExists($conn, 'our_story')) return [];

    $sql = "SELECT * FROM our_story";
    if ($publishedOnly) {
        $sql .= " WHERE published = 1";
    }
    $sql .= " ORDER BY display_order ASC, year_or_date ASC, id ASC";

    $result = $conn->query($sql);
    $milestones = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $milestones[] = $row;
        }
    }
    return $milestones;
}

function getStoryMilestoneById($conn, $id) {
    if (!$conn || !tableExists($conn, 'our_story')) return null;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM our_story WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();
    return $row;
}

function saveStoryMilestoneRecord($conn, $data) {
    $yearOrDate = trim((string)($data['year_or_date'] ?? ($data['year'] ?? '')));
    $title = trim((string)($data['title'] ?? ''));
    $desc = trim((string)($data['description'] ?? ''));
    $image = trim((string)($data['image_url'] ?? ''));
    $displayOrder = (int)($data['display_order'] ?? 0);
    $published = isset($data['published']) && (string)$data['published'] === '0' ? 0 : 1;

    if ($yearOrDate === '') {
        throw new InvalidArgumentException('Milestone year or date is required.');
    }
    if ($title === '') {
        throw new InvalidArgumentException('Milestone title is required.');
    }
    if ($desc === '') {
        throw new InvalidArgumentException('Milestone description is required.');
    }

    $id = isset($data['id']) && (int)$data['id'] > 0 ? (int)$data['id'] : null;

    if ($id) {
        $sql = "UPDATE our_story SET year_or_date = ?, title = ?, description = ?, image_url = ?, display_order = ?, published = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssiii", $yearOrDate, $title, $desc, $image, $displayOrder, $published, $id);
    } else {
        $sql = "INSERT INTO our_story (year_or_date, title, description, image_url, display_order, published) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $yearOrDate, $title, $desc, $image, $displayOrder, $published);
    }

    if (!$stmt) throw new RuntimeException('Failed to prepare milestone statement: ' . $conn->error);
    $stmt->execute();
    if (!$id) $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}

function deleteStoryMilestoneRecord($conn, $id) {
    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM our_story WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    return true;
}

// ------------------------------------------------------------------------------
// 9. CORE VALUES MODULE
// ------------------------------------------------------------------------------

function getCoreValues($conn, $publishedOnly = true) {
    if (!$conn || !tableExists($conn, 'core_values')) return [];

    $sql = "SELECT * FROM core_values";
    if ($publishedOnly) {
        $sql .= " WHERE published = 1";
    }
    $sql .= " ORDER BY display_order ASC, id ASC";

    $result = $conn->query($sql);
    $values = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $values[] = $row;
        }
    }
    return $values;
}

function getCoreValueById($conn, $id) {
    if (!$conn || !tableExists($conn, 'core_values')) return null;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM core_values WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();
    return $row;
}

function saveCoreValueRecord($conn, $data) {
    $title = trim((string)($data['title'] ?? ''));
    $desc = trim((string)($data['description'] ?? ''));
    $icon = trim((string)($data['icon'] ?? 'verified'));
    $displayOrder = (int)($data['display_order'] ?? 0);
    $published = isset($data['published']) && (string)$data['published'] === '0' ? 0 : 1;

    if ($title === '') {
        throw new InvalidArgumentException('Core value title is required.');
    }
    if ($desc === '') {
        throw new InvalidArgumentException('Core value description is required.');
    }

    $id = isset($data['id']) && (int)$data['id'] > 0 ? (int)$data['id'] : null;

    if ($id) {
        $sql = "UPDATE core_values SET title = ?, description = ?, icon = ?, display_order = ?, published = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiii", $title, $desc, $icon, $displayOrder, $published, $id);
    } else {
        $sql = "INSERT INTO core_values (title, description, icon, display_order, published) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $title, $desc, $icon, $displayOrder, $published);
    }

    if (!$stmt) throw new RuntimeException('Failed to prepare core value statement: ' . $conn->error);
    $stmt->execute();
    if (!$id) $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}

function deleteCoreValueRecord($conn, $id) {
    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM core_values WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    return true;
}

// ------------------------------------------------------------------------------
// 10. ACHIEVEMENTS AND IMPACT MODULE
// ------------------------------------------------------------------------------

function getAchievements($conn, $publishedOnly = true) {
    if (!$conn || !tableExists($conn, 'achievements')) return [];

    $sql = "SELECT * FROM achievements";
    if ($publishedOnly) {
        $sql .= " WHERE published = 1";
    }
    $sql .= " ORDER BY display_order ASC, id ASC";

    $result = $conn->query($sql);
    $items = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
    }
    return $items;
}

function getAchievementById($conn, $id) {
    if (!$conn || !tableExists($conn, 'achievements')) return null;
    $id = (int)$id;
    $stmt = $conn->prepare("SELECT * FROM achievements WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();
    return $row;
}

function saveAchievementRecord($conn, $data) {
    $title = trim((string)($data['title'] ?? ($data['label'] ?? '')));
    $desc = trim((string)($data['description'] ?? ''));
    $val = trim((string)($data['metric_value'] ?? ''));
    $unit = trim((string)($data['metric_unit'] ?? ($data['unit'] ?? '')));
    $period = trim((string)($data['reporting_period'] ?? ''));
    $image = trim((string)($data['image_url'] ?? ''));
    $displayOrder = (int)($data['display_order'] ?? 0);
    $published = isset($data['published']) && (string)$data['published'] === '0' ? 0 : 1;

    if ($title === '') {
        throw new InvalidArgumentException('Achievement title is required.');
    }

    $id = isset($data['id']) && (int)$data['id'] > 0 ? (int)$data['id'] : null;

    if ($id) {
        $sql = "UPDATE achievements SET title = ?, description = ?, metric_value = ?, metric_unit = ?, reporting_period = ?, image_url = ?, display_order = ?, published = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssiii", $title, $desc, $val, $unit, $period, $image, $displayOrder, $published, $id);
    } else {
        $sql = "INSERT INTO achievements (title, description, metric_value, metric_unit, reporting_period, image_url, display_order, published) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssii", $title, $desc, $val, $unit, $period, $image, $displayOrder, $published);
    }

    if (!$stmt) throw new RuntimeException('Failed to prepare achievement statement: ' . $conn->error);
    $stmt->execute();
    if (!$id) $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}

function deleteAchievementRecord($conn, $id) {
    $id = (int)$id;
    $stmt = $conn->prepare("DELETE FROM achievements WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    return true;
}

/**
 * Updates or creates a page-level SEO record in seo_page_metadata.
 *
 * @param mysqli $conn
 * @param array $data
 * @return array [bool $success, string $message]
 */
function savePageSeoMetadata($conn, $data) {
    $route = trim($data['page_route'] ?? '');
    if ($route === '') {
        return [false, 'Page route identifier is required.'];
    }

    $title = trim($data['meta_title'] ?? '');
    $desc = trim($data['meta_description'] ?? '');
    $canonical = trim($data['canonical_url'] ?? '') ?: null;
    $robotsIndex = isset($data['robots_index']) ? (int)$data['robots_index'] : 1;
    $robotsFollow = isset($data['robots_follow']) ? (int)$data['robots_follow'] : 1;
    $ogTitle = trim($data['og_title'] ?? '') ?: null;
    $ogDesc = trim($data['og_description'] ?? '') ?: null;
    $ogImage = trim($data['og_image_url'] ?? '') ?: null;
    $ogType = trim($data['og_type'] ?? 'website') ?: 'website';
    $twitterCard = trim($data['twitter_card'] ?? 'summary_large_image') ?: 'summary_large_image';

    $stmt = $conn->prepare("
        INSERT INTO `seo_page_metadata`
            (`page_route`, `meta_title`, `meta_description`, `canonical_url`, `robots_index`, `robots_follow`, `og_title`, `og_description`, `og_image_url`, `og_type`, `twitter_card`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            `meta_title` = VALUES(`meta_title`),
            `meta_description` = VALUES(`meta_description`),
            `canonical_url` = VALUES(`canonical_url`),
            `robots_index` = VALUES(`robots_index`),
            `robots_follow` = VALUES(`robots_follow`),
            `og_title` = VALUES(`og_title`),
            `og_description` = VALUES(`og_description`),
            `og_image_url` = VALUES(`og_image_url`),
            `og_type` = VALUES(`og_type`),
            `twitter_card` = VALUES(`twitter_card`),
            `updated_at` = CURRENT_TIMESTAMP
    ");

    if (!$stmt) {
        return [false, 'Database error: ' . $conn->error];
    }

    $stmt->bind_param('ssssiisssss', $route, $title, $desc, $canonical, $robotsIndex, $robotsFollow, $ogTitle, $ogDesc, $ogImage, $ogType, $twitterCard);
    $ok = $stmt->execute();
    $err = $stmt->error;
    $stmt->close();

    if ($ok) {
        return [true, 'SEO metadata for ' . htmlspecialchars($route, ENT_QUOTES, 'UTF-8') . ' saved successfully.'];
    }
    return [false, 'Failed to save SEO metadata: ' . $err];
}

/**
 * Updates global SEO settings in site_settings table.
 *
 * @param mysqli $conn
 * @param array $data
 * @return array [bool $success, string $message]
 */
function saveGlobalSeoSettings($conn, $data) {
    $allowedKeys = [
        'seo_default_title',
        'seo_default_description',
        'seo_default_og_image',
        'seo_canonical_domain',
        'seo_default_robots',
        'seo_twitter_handle',
        'seo_organization_type',
        'seo_org_founding_date',
        'seo_org_area_served',
    ];

    $stmt = $conn->prepare("
        INSERT INTO `site_settings` (`setting_key`, `setting_value`)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`), `updated_at` = CURRENT_TIMESTAMP
    ");

    if (!$stmt) {
        return [false, 'Database error: ' . $conn->error];
    }

    $updated = 0;
    foreach ($allowedKeys as $key) {
        if (isset($data[$key])) {
            $val = trim($data[$key]);
            $stmt->bind_param('ss', $key, $val);
            if ($stmt->execute()) {
                $updated++;
            }
        }
    }
    $stmt->close();

    return [true, "Global SEO & AEO settings updated successfully ($updated parameters)."];
}
