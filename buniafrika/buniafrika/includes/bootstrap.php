<?php
// Bridge loader to private application bootstrap
if (!defined('BUNIAFRIKA_PRIVATE_DIR')) {
    $envDir = getenv('BUNIAFRIKA_PRIVATE_DIR');
    if ($envDir && is_dir($envDir)) {
        define('BUNIAFRIKA_PRIVATE_DIR', rtrim($envDir, '/\\'));
    } elseif (is_dir(dirname(__DIR__, 2) . '/buniafrika.co.ke')) {
        // Unified cPanel deployment: /buniafrika/buniafrika/includes -> /buniafrika/buniafrika.co.ke
        define('BUNIAFRIKA_PRIVATE_DIR', dirname(__DIR__, 2) . '/buniafrika.co.ke');
    } elseif (is_dir(dirname(__DIR__, 3) . '/buniafrika.co.ke')) {
        // Alternative parent level: /buniafrika.co.ke
        define('BUNIAFRIKA_PRIVATE_DIR', dirname(__DIR__, 3) . '/buniafrika.co.ke');
    } elseif (is_dir(dirname(__DIR__) . '/buniafrika.co.ke')) {
        define('BUNIAFRIKA_PRIVATE_DIR', dirname(__DIR__) . '/buniafrika.co.ke');
    } else {
        define('BUNIAFRIKA_PRIVATE_DIR', dirname(__DIR__));
    }
}

require_once BUNIAFRIKA_PRIVATE_DIR . '/includes/bootstrap.php';
