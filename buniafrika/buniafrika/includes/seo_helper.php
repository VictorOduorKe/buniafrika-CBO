<?php
// Bridge loader to private SEO and AEO helper
require_once __DIR__ . '/bootstrap.php';
if (defined('BUNIAFRIKA_PRIVATE_DIR') && is_dir(BUNIAFRIKA_PRIVATE_DIR)) {
    require_once BUNIAFRIKA_PRIVATE_DIR . '/includes/seo_helper.php';
} else {
    require_once dirname(__DIR__, 2) . '/buniafrika.co.ke/includes/seo_helper.php';
}
