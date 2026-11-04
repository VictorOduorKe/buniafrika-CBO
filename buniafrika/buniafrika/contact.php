<?php
$page_title = 'Contact BuniAfrika CBO | General, partnership and finance enquiries';
$meta_desc = 'Contact BuniAfrika CBO by email, phone or the enquiry form. Choose general, partnership or finance enquiries.';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <p class="section-tag" style="color: var(--secondary-container);">Get in touch</p>
        <h1>Contact BuniAfrika</h1>
        <p class="lead">Choose the appropriate contact for your enquiry. The form stores your message and routes its notification to the relevant team.</p>
    </div>
</section>

<section class="section container">
    <div class="bento-grid">
        <div class="bento-card col-span-5">
            <h2>Contact details</h2>
            <div class="contact-detail-grid">
                <a class="contact-detail-card" href="tel:<?php echo h(phoneNumberHref($site_settings['general_phone'])); ?>">
                    <span class="contact-detail-icon material-symbols-outlined" aria-hidden="true">call</span>
                    <span class="contact-detail-label">Call or WhatsApp</span>
                    <strong><?php echo h($site_settings['general_phone']); ?></strong>
                    <span>For general enquiries</span>
                </a>
                <a class="contact-detail-card" href="mailto:<?php echo h($site_settings['general_email']); ?>">
                    <span class="contact-detail-icon material-symbols-outlined" aria-hidden="true">mail</span>
                    <span class="contact-detail-label">General enquiries</span>
                    <strong><?php echo h($site_settings['general_email']); ?></strong>
                    <span>Questions about BuniAfrika and our work</span>
                </a>
                <a class="contact-detail-card" href="mailto:<?php echo h($site_settings['partnership_email']); ?>">
                    <span class="contact-detail-icon material-symbols-outlined" aria-hidden="true">handshake</span>
                    <span class="contact-detail-label">Partnerships</span>
                    <strong><?php echo h($site_settings['partnership_email']); ?></strong>
                    <span>Discuss collaboration with BuniAfrika</span>
                </a>
                <a class="contact-detail-card" href="mailto:<?php echo h($site_settings['finance_email']); ?>">
                    <span class="contact-detail-icon material-symbols-outlined" aria-hidden="true">account_balance</span>
                    <span class="contact-detail-label">Donations and finance</span>
                    <strong><?php echo h($site_settings['finance_email']); ?></strong>
                    <span>Finance-related questions and donation enquiries</span>
                </a>
                <a class="contact-detail-card" href="<?php echo h($site_settings['website_url']); ?>">
                    <span class="contact-detail-icon material-symbols-outlined" aria-hidden="true">language</span>
                    <span class="contact-detail-label">Website</span>
                    <strong><?php echo h(parse_url($site_settings['website_url'], PHP_URL_HOST)); ?></strong>
                    <span>Visit BuniAfrika online</span>
                </a>
            </div>
            <div class="contact-location">
                <h3><span class="material-symbols-outlined" aria-hidden="true">location_on</span>Our location</h3>
                <p><span class="material-symbols-outlined" aria-hidden="true">location_city</span><?php echo h($site_settings['location_text']); ?></p>
                <a class="contact-map-link" href="<?php echo h($site_settings['location_map_url']); ?>" target="_blank" rel="noopener noreferrer"><span class="material-symbols-outlined" aria-hidden="true">map</span>Open in Google Maps</a>
                <iframe class="contact-map-frame" title="Map showing BuniAfrika's location in <?php echo h($site_settings['location_text']); ?>" src="https://maps.google.com/maps?q=<?php echo rawurlencode($site_settings['location_text']); ?>&amp;output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
            </div>
            <div class="contact-social">
                <h3>Follow BuniAfrika</h3>
                <div class="contact-social-links">
                    <?php
                    $contact_socials = [
                        'Facebook' => [$site_settings['social_facebook'], 'thumb_up'],
                        'Instagram' => [$site_settings['social_instagram'], 'photo_camera'],
                        'LinkedIn' => [$site_settings['social_linkedin'], 'business_center'],
                        'YouTube' => [$site_settings['social_youtube'], 'smart_display'],
                        'X' => [$site_settings['social_x'], 'alternate_email'],
                        'TikTok' => [$site_settings['social_tiktok'], 'music_note'],
                        'WhatsApp Channel' => [$site_settings['social_whatsapp_channel'], 'forum'],
                    ];
                    ?>
                    <?php foreach ($contact_socials as $network => [$url, $icon]): ?>
                        <?php if ($url !== ''): ?>
                            <a href="<?php echo h($url); ?>" target="_blank" rel="noopener noreferrer"><span class="material-symbols-outlined" aria-hidden="true"><?php echo h($icon); ?></span><?php echo h($network); ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="bento-card col-span-7">
            <h2>Send an enquiry</h2>
            <p class="section-desc">Please share only the information needed to respond to your enquiry.</p>
            <div id="form-feedback" aria-live="polite"></div>
            <form id="contact-form" method="post" action="process_contact.php">
                <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
                <div class="form-group">
                    <label class="form-label" for="name">Your name</label>
                    <input class="form-control" type="text" id="name" name="name" maxlength="255" autocomplete="name" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email address</label>
                    <input class="form-control" type="email" id="email" name="email" maxlength="255" autocomplete="email" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="phone">Phone number</label>
                    <input class="form-control" type="tel" id="phone" name="phone" maxlength="32" autocomplete="tel" placeholder="0705556565">
                </div>
                <div class="form-group">
                    <label class="form-label" for="inquiry_type">Enquiry type</label>
                    <select class="form-control" id="inquiry_type" name="inquiry_type" required>
                        <option value="general">General enquiry</option>
                        <option value="partnership">Partnership</option>
                        <option value="finance">Donations and finance</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="subject">Subject</label>
                    <input class="form-control" type="text" id="subject" name="subject" maxlength="255" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="message">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="5" maxlength="5000" required></textarea>
                </div>
                <div class="form-group" hidden aria-hidden="true" style="position:absolute; left:-10000px;">
                    <label for="company_website">Leave this field empty</label>
                    <input type="text" id="company_website" name="company_website" tabindex="-1" autocomplete="off">
                </div>
                <button class="btn btn-primary w-full" type="submit">Send enquiry</button>
            </form>
            <p class="privacy-note">By submitting, you agree that BuniAfrika may use these details to respond to this enquiry.</p>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
