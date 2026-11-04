</main>

<footer class="footer site-footer">
    <div class="container footer-grid">
        <section class="footer-brand">
            <a class="brand-logo" href="<?php echo h($base_path); ?>index.php">
                <img src="<?php echo h($logo); ?>" alt="" width="40" height="40">
                <span class="brand-title"><?php echo h($site_settings['site_name']); ?></span>
            </a>
            <p class="footer-desc"><?php echo h($site_settings['mission']); ?></p>
        </section>
        <nav class="footer-column" aria-label="Explore">
            <h2 class="footer-col-title">Explore</h2>
            <a class="footer-link-new" href="<?php echo h($base_path); ?>about.php">About us</a>
            <a class="footer-link-new" href="<?php echo h($base_path); ?>programmes.php">Thematic areas</a>
            <a class="footer-link-new" href="<?php echo h($base_path); ?>projects.php">Projects</a>
            <a class="footer-link-new" href="<?php echo h($base_path); ?>publications.php">Publications &amp; Reports</a>
            <a class="footer-link-new" href="<?php echo h($base_path); ?>news.php">Research and news</a>
            <a class="footer-link-new" href="<?php echo h($base_path); ?>events.php">Events &amp; Gatherings</a>
            <a class="footer-link-new" href="<?php echo h($base_path); ?>volunteer.php">Get Involved &amp; Volunteer</a>
            <a class="footer-link-new" href="<?php echo h($base_path); ?>gallery.php">Media</a>
        </nav>
        <section class="footer-column footer-contact">
            <h2 class="footer-col-title">Contact</h2>
            <a class="footer-contact-card" href="mailto:<?php echo h($site_settings['general_email']); ?>">
                <span class="footer-contact-icon material-symbols-outlined" aria-hidden="true">mail</span>
                <span class="footer-contact-label">General enquiries</span>
                <span><?php echo h($site_settings['general_email']); ?></span>
            </a>
            <a class="footer-contact-card" href="mailto:<?php echo h($site_settings['partnership_email']); ?>">
                <span class="footer-contact-icon material-symbols-outlined" aria-hidden="true">handshake</span>
                <span class="footer-contact-label">Partnerships</span>
                <span><?php echo h($site_settings['partnership_email']); ?></span>
            </a>
            <a class="footer-contact-card" href="mailto:<?php echo h($site_settings['finance_email']); ?>">
                <span class="footer-contact-icon material-symbols-outlined" aria-hidden="true">account_balance</span>
                <span class="footer-contact-label">Donations and finance</span>
                <span><?php echo h($site_settings['finance_email']); ?></span>
            </a>
            <a class="footer-contact-card footer-phone-card" href="tel:<?php echo h(phoneNumberHref($site_settings['general_phone'])); ?>">
                <span class="footer-contact-icon material-symbols-outlined" aria-hidden="true">call</span>
                <span class="footer-contact-label">Call or WhatsApp</span>
                <span><?php echo h($site_settings['general_phone']); ?></span>
            </a>
        </section>
        <section class="footer-column footer-location">
            <h2 class="footer-col-title">Find us online</h2>
            <p><a class="footer-link-new footer-inline-link" href="<?php echo h($site_settings['website_url']); ?>"><span class="material-symbols-outlined" aria-hidden="true">language</span><?php echo h(parse_url($site_settings['website_url'], PHP_URL_HOST)); ?></a></p>
            <?php if ($site_settings['location_text'] !== ''): ?>
                <p class="footer-location-text"><span class="material-symbols-outlined" aria-hidden="true">location_on</span><?php echo h($site_settings['location_text']); ?></p>
            <?php endif; ?>
            <?php
            $footer_socials = [
                'Facebook' => [$site_settings['social_facebook'], 'thumb_up'],
                'Instagram' => [$site_settings['social_instagram'], 'photo_camera'],
                'LinkedIn' => [$site_settings['social_linkedin'], 'business_center'],
                'YouTube' => [$site_settings['social_youtube'], 'smart_display'],
                'X' => [$site_settings['social_x'], 'alternate_email'],
                'TikTok' => [$site_settings['social_tiktok'], 'music_note'],
                'WhatsApp Channel' => [$site_settings['social_whatsapp_channel'], 'forum'],
            ];
            ?>
            <ul class="footer-social-links" aria-label="BuniAfrika social media">
                <?php foreach ($footer_socials as $network => [$url, $icon]): ?>
                    <?php if ($url !== ''): ?>
                        <li><a class="footer-social-link" href="<?php echo h($url); ?>" target="_blank" rel="noopener noreferrer"><span class="material-symbols-outlined" aria-hidden="true"><?php echo h($icon); ?></span><?php echo h($network); ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <?php if ($site_settings['location_map_url'] !== ''): ?>
                <a class="footer-map-link footer-inline-link" href="<?php echo h($site_settings['location_map_url']); ?>" target="_blank" rel="noopener noreferrer"><span class="material-symbols-outlined" aria-hidden="true">map</span>Open location map</a>
            <?php endif; ?>
        </section>
    </div>
    <div class="container footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> <?php echo h($site_settings['site_name']); ?>. All rights reserved.</p>
        <p>
            <a class="footer-link-new" href="<?php echo h($base_path); ?>privacy.php">Privacy notice</a>
            <span aria-hidden="true"> · </span>
            <?php if (isAdminLoggedIn()): ?>
                <a class="footer-link-new" href="<?php echo h($base_path); ?>admin/admin_dashboard.php">Admin Dashboard</a>
            <?php else: ?>
                <a class="footer-link-new" href="<?php echo h($base_path); ?>login.php">Admin Login</a>
            <?php endif; ?>
        </p>
    </div>
</footer>

<?php if (strpos($_SERVER['SCRIPT_NAME'] ?? '', '/admin/') === false): ?>
    <a class="floating-whatsapp" href="https://wa.me/<?php echo h($site_settings['whatsapp_number']); ?>?text=Hello%20BuniAfrika%2C%20I%20would%20like%20to%20make%20an%20enquiry." target="_blank" rel="noopener noreferrer" aria-label="Message BuniAfrika on WhatsApp">
        <svg viewBox="0 0 32 32" aria-hidden="true" focusable="false">
            <path d="M16 3.5a12 12 0 0 0-10.2 18.3L4.2 28l6.4-1.7A12 12 0 1 0 16 3.5Z" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linejoin="round"/>
            <path d="M12 10.7c-.4-.9-.8-.9-1.2-.9h-1c-.4 0-1 .2-1.5.7-.5.5-1.9 1.8-1.9 4.3s1.9 5 2.2 5.4c.3.3 3.7 5.8 9 7.9 4.5 1.8 5.4 1.4 6.4 1.3 1-.1 3.2-1.3 3.7-2.6.4-1.3.4-2.4.3-2.6-.1-.2-.5-.4-1-.6-.5-.3-3.2-1.6-3.7-1.8-.5-.2-.8-.3-1.1.3-.3.5-1.3 1.8-1.6 2.2-.3.3-.6.4-1.1.1-.5-.3-2.2-.8-4.1-2.5-1.5-1.3-2.5-2.9-2.8-3.4-.3-.5 0-.8.2-1.1.2-.2.5-.6.8-.9.3-.3.4-.5.5-.8.2-.3.1-.6 0-.9-.1-.3-1.1-2.9-1.5-3.8Z" fill="currentColor" transform="translate(1 0) scale(.86)"/>
        </svg>
    </a>
<?php endif; ?>

<?php if (!empty($is_admin_folder)): ?>
<script>
document.addEventListener('click', function (event) {
    const link = event.target.closest('a[href*="action="]');
    if (!link || event.defaultPrevented) return;
    const url = new URL(link.href, window.location.href);
    const action = url.searchParams.get('action');
    const supported = [
        'toggle_project_publish', 'toggle_gallery_publish', 'delete_project',
        'delete_gallery', 'toggle_news_publish', 'delete_news',
        'toggle_event_publish', 'delete_event', 'delete_contact',
        'delete_registration', 'complete_donation', 'fail_donation',
        'delete_donation', 'delete_log', 'delete_all_logs',
        'toggle_thematic_publish', 'delete_thematic',
        'toggle_objective_publish', 'delete_objective',
        'toggle_publication_publish', 'delete_publication',
        'toggle_testimonial_publish', 'delete_testimonial',
        'toggle_faq_publish', 'delete_faq',
        'toggle_partner_publish', 'delete_partner',
        'toggle_volunteer_opportunity_status', 'delete_volunteer_opportunity', 'delete_volunteer_application',
        'toggle_story_publish', 'delete_story',
        'toggle_value_publish', 'delete_value',
        'toggle_achievement_publish', 'delete_achievement',
        'delete_leadership'
    ];
    if (!supported.includes(action)) return;

    event.preventDefault();

    const isDestructive = action.startsWith('delete_') || action === 'fail_donation';
    const actionLabel = action.replace(/^(delete_|toggle_|fail_)/, '').replace(/_/g, ' ');
    const confirmTitle = isDestructive ? 'Confirm Deletion' : 'Confirm Action';
    const confirmText = isDestructive
        ? 'Are you sure you want to permanently delete this ' + actionLabel + '? This action cannot be undone.'
        : 'Are you sure you want to proceed with this update?';

    const doSubmit = function () {
        const form = document.createElement('form');
        form.method = 'post';
        form.action = window.location.pathname + '?tab=' + encodeURIComponent(url.searchParams.get('tab') || 'overview');
        for (const [name, value] of Object.entries({
            _mutation_action: action,
            _mutation_id: url.searchParams.get('id') || '0',
            csrf_token: <?php echo json_encode(generateCsrfToken()); ?>
        })) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            form.appendChild(input);
        }
        document.body.appendChild(form);
        form.submit();
    };

    if (isDestructive && window.BuniAlert) {
        window.BuniAlert.confirm(confirmTitle, confirmText, 'Yes, Delete', 'Cancel').then(function (confirmed) {
            if (confirmed) {
                doSubmit();
            }
        });
    } else {
        doSubmit();
    }
});
</script>
<?php endif; ?>
<?php
$flashSuccess = $_SESSION['flash_success'] ?? null;
$flashError = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$backend_api_url = rtrim($_ENV['BACKEND_API_URL'] ?? $site_settings['website_url'] ?? '', '/');
$currentHost = $_SERVER['HTTP_HOST'] ?? '';
$isLocalDev = (bool)preg_match('/^(localhost|127\.0\.0\.1|::1)(:\d+)?$/i', $currentHost);
if ($isLocalDev) {
    $backendHost = parse_url($backend_api_url, PHP_URL_HOST);
    if ($backendHost && !preg_match('/^(localhost|127\.0\.0\.1|::1)$/i', $backendHost)) {
        $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $backend_api_url = $proto . '://' . $currentHost;
    }
}
?>
<script src="<?php echo h($base_path); ?>js/sweetalert2.all.min.js"></script>
<script src="<?php echo h($base_path); ?>js/alerts.js"></script>
<?php if ($flashSuccess || $flashError): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.BuniAlert) {
        <?php if ($flashSuccess): ?>
        window.BuniAlert.success('Success', <?php echo json_encode($flashSuccess); ?>);
        <?php elseif ($flashError): ?>
        window.BuniAlert.error('Error', <?php echo json_encode($flashError); ?>);
        <?php endif; ?>
    }
});
</script>
<?php endif; ?>
<script>
window.__backendUrl = <?php echo json_encode($backend_api_url); ?>;
window.__apiBase = window.__backendUrl;
</script>
<script src="<?php echo h($base_path); ?>js/navigation.js" defer></script>
<script src="<?php echo h($base_path); ?>js/main.js" defer></script>
<script src="<?php echo h($base_path); ?>js/form_handler.js" defer></script>
<?php if (empty($is_private_page)): ?>
<script>window.__i18nBase = <?php echo json_encode($base_path); ?>;</script>
<script src="<?php echo h($base_path); ?>js/i18n.js"></script>
<?php endif; ?>

<?php require_once __DIR__ . '/accessibility.php'; ?>
<script src="<?php echo h($base_path); ?>js/accessibility.js" defer></script>
</body>
</html>
