<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth_helper.php';
require_once __DIR__ . '/navigation.php';

$is_admin_folder = strpos($_SERVER['SCRIPT_NAME'] ?? '', '/admin/') !== false;
$base_path = $is_admin_folder ? '../' : '';
$site_settings = getSiteSettings($conn);
$site_url = rtrim($site_settings['website_url'] ?? 'https://buniafrika.co.ke', '/');
$logo = $base_path . 'assets/buniafrika%20favicon/android-chrome-192x192.png';
$schema_phone = phoneNumberHref($site_settings['general_phone'] ?? '');
$current_page = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
$is_private_page = $is_admin_folder || in_array($current_page, ['login.php', 'reset_password.php', 'register.php', 'submit_article.php'], true);
$nav_items = getNavigationStructure($base_path);

// Centralized SEO & AEO context resolution
$seo_context = $seo_context ?? [];
if (!empty($page_title)) $seo_context['title'] = $page_title;
if (!empty($meta_desc)) $seo_context['meta_description'] = $meta_desc;
if (!empty($canonical_url)) $seo_context['canonical_url'] = $canonical_url;
if (!empty($robots_directive)) $seo_context['robots'] = $robots_directive;
if ($is_private_page && empty($seo_context['robots'])) {
    $seo_context['robots'] = 'noindex, nofollow';
}

$seo = getSeoMetadata($conn, $current_page, $seo_context);
$page_title = $seo['title'];
$meta_desc = $seo['meta_description'];
$canonical_url = $seo['canonical_url'];

function isActive($current_page, $target_page) {
    return $current_page === $target_page ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php echo renderSeoHeadTags($seo); ?>
    <?php if (strpos($seo['robots'], 'noindex') === false): ?>
        <?php echo buildSchemaJsonLd($conn, $current_page, $seo, $seo_context); ?>
    <?php endif; ?>
    <link rel="icon" href="<?php echo h($base_path); ?>assets/buniafrika%20favicon/favicon.ico" sizes="any">
    <link rel="icon" href="<?php echo h($base_path); ?>assets/buniafrika%20favicon/favicon-32x32.png" type="image/png" sizes="32x32">
    <link rel="icon" href="<?php echo h($base_path); ?>assets/buniafrika%20favicon/favicon-16x16.png" type="image/png" sizes="16x16">
    <link rel="apple-touch-icon" href="<?php echo h($base_path); ?>assets/buniafrika%20favicon/apple-touch-icon.png">
    <link rel="manifest" href="<?php echo h($base_path); ?>assets/buniafrika%20favicon/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;600;700;800&family=Source+Serif+4:ital,opsz,wght@0,8..60,600;0,8..60,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link href="<?php echo h($base_path); ?>css/style.css" rel="stylesheet">
    <link href="<?php echo h($base_path); ?>css/accessibility.css" rel="stylesheet">
    <script>
    (function(){
        try {
            var p = JSON.parse(localStorage.getItem('buniafrica_a11y_prefs') || '{}');
            var d = document.documentElement;
            if (p.highContrast) d.classList.add('a11y-high-contrast');
            if (p.grayscale) d.classList.add('a11y-grayscale');
            if (p.highlightLinks) d.classList.add('a11y-highlight-links');
            if (p.readableFont) d.classList.add('a11y-readable-font');
            if (p.textScale === -1) d.classList.add('a11y-text-step-m1');
            else if (p.textScale === 1) d.classList.add('a11y-text-step-p1');
            else if (p.textScale === 2) d.classList.add('a11y-text-step-p2');
            else if (p.textScale === 3) d.classList.add('a11y-text-step-p3');
            else if (p.textScale === 4) d.classList.add('a11y-text-step-p4');
        } catch(e) {}
    })();
    </script>
</head>
<body>
<header class="header">
    <div class="brand">
        <button class="menu-btn js-open-drawer" type="button" aria-label="Open navigation menu" aria-expanded="false" aria-controls="drawer">
            <span class="material-symbols-outlined" aria-hidden="true">menu</span>
        </button>
        <a href="<?php echo h($base_path); ?>index.php" class="brand-logo logo-section">
            <img src="<?php echo h($logo); ?>" alt="BuniAfrika emblem" width="44" height="44">
            <span class="brand-title"><?php echo h($site_settings['site_name']); ?></span>
        </a>
    </div>
    <nav class="nav-desktop" aria-label="Main navigation">
        <?php echo renderDesktopNavigation($nav_items, $current_page); ?>
        <?php if (isAdminLoggedIn()): ?>
            <a class="nav-link" href="<?php echo h($base_path); ?>admin/admin_dashboard.php">Dashboard</a>
        <?php else: ?>
            <a class="btn btn-primary" href="<?php echo h($base_path); ?>donate.php">Donate</a>
        <?php endif; ?>
        <a class="call-us" href="tel:<?php echo h($schema_phone); ?>" aria-label="Call <?php echo h($site_settings['general_phone']); ?>"><span class="material-symbols-outlined" aria-hidden="true">call</span><?php echo h($site_settings['general_phone']); ?></a>
    </nav>
</header>

<div class="drawer-overlay" id="drawer-overlay"></div>
<aside class="drawer" id="drawer" aria-label="Mobile navigation" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="drawer-header">
        <a href="<?php echo h($base_path); ?>index.php" class="drawer-brand">
            <img src="<?php echo h($logo); ?>" alt="" width="36" height="36">
            <span class="drawer-title"><?php echo h($site_settings['site_name']); ?></span>
        </a>
        <button class="drawer-close js-close-drawer" type="button" aria-label="Close navigation menu">
            <span class="material-symbols-outlined" aria-hidden="true">close</span>
        </button>
    </div>
    <nav class="drawer-nav" aria-label="Mobile menu">
        <?php echo renderMobileDrawerNavigation($nav_items, $current_page); ?>
    </nav>
    <div class="drawer-footer">
        <?php if (isAdminLoggedIn()): ?>
            <a class="btn btn-outline drawer-action-btn" href="<?php echo h($base_path); ?>admin/admin_dashboard.php">Admin Dashboard</a>
        <?php else: ?>
            <a class="btn btn-primary drawer-action-btn" href="<?php echo h($base_path); ?>donate.php">Support &amp; Donate</a>
        <?php endif; ?>
        <a class="drawer-call" href="tel:<?php echo h($schema_phone); ?>">
            <span class="material-symbols-outlined" aria-hidden="true">call</span>
            <span>Call <?php echo h($site_settings['general_phone']); ?></span>
        </a>
    </div>
</aside>
<main>
