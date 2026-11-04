<?php
// Bridge loader to private business logic functions
require_once __DIR__ . '/bootstrap.php';
if (defined('BUNIAFRIKA_PRIVATE_DIR') && is_dir(BUNIAFRIKA_PRIVATE_DIR)) {
    require_once BUNIAFRIKA_PRIVATE_DIR . '/includes/functions.php';
} else {
    require_once dirname(__DIR__, 2) . '/buniafrika.co.ke/includes/functions.php';
}
