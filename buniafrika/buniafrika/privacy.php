<?php
$page_title = 'Privacy Notice | BuniAfrika CBO';
$meta_desc = 'Learn how BuniAfrika CBO uses information submitted through its public contact, registration and donation forms.';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <p class="section-tag" style="color: var(--secondary-container);">Privacy</p>
        <h1>Privacy notice</h1>
        <p class="lead">This notice explains how information submitted through BuniAfrika’s website forms is used.</p>
    </div>
</section>

<section class="section container">
    <article class="bento-card">
        <h2>Information submitted to us</h2>
        <p class="section-desc">Contact, registration and donation forms may collect your name, contact details and the information you choose to include in your message. Do not submit sensitive financial credentials or information that is not needed for your request.</p>
        <h2>How information is used</h2>
        <p class="section-desc">Information is used to respond to your enquiry, process a registration or donation submission, and maintain related organisational records. It is not used for unrelated purposes through these forms.</p>
        <h2>Storage, access and retention</h2>
        <p class="section-desc">Form submissions are stored in the website database and may be sent to the relevant team by email. Access should be limited to authorised personnel. Records are retained under the organisation’s records and legal requirements; the website does not currently apply an automatic retention schedule.</p>
        <h2>Cookies and external services</h2>
        <p class="section-desc">The website uses session storage for security-protected forms and administration. If you use the translation feature, your browser may interact with the translation provider under its own privacy terms. The contact page embeds Google Maps, and links to external social platforms may share connection or interaction information with those providers under their privacy terms.</p>
        <h2>Questions or requests</h2>
        <p class="section-desc">For questions or requests about information you submitted, contact <a href="mailto:<?php echo h($site_settings['general_email']); ?>"><?php echo h($site_settings['general_email']); ?></a>.</p>
    </article>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
