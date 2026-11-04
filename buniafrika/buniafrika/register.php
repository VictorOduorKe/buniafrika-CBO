<?php

$page_title = 'Get Involved | BuniAfrika CBO';
$meta_desc = 'Register your interest in volunteering with or partnering with BuniAfrika CBO.';

require_once __DIR__ . '/includes/header.php';

$query_role = isset($_GET['role']) ? strtolower(trim($_GET['role'])) : '';
$query_project = isset($_GET['project']) ? trim($_GET['project']) : '';

$selected_role = '';
if ($query_role === 'volunteer') {
    $selected_role = 'volunteer';
} elseif ($query_role === 'partner') {
    $selected_role = 'partner';
}

$default_message = '';
if ($query_project !== '') {
    $default_message = "I would like to get involved and support the project: " . h($query_project) . ".";
}
?>

<section class="hero">
    <div class="hero-overlay"></div>
    <img class="hero-bg" style="opacity: 0.6" src="<?php echo $base_path; ?>assets/contact1.jpeg" alt="Community gathering"/>
    <div class="hero-content">
        <h1 class="hero-title poppins-bold">Get Involved</h1>
        <p class="hero-desc poppins-medium">Share your interest in volunteering or partnering with BuniAfrika.</p>
    </div>
</section>

<section class="section container" style="max-width: 650px;">
    <div class="bento-card" style="box-shadow: var(--shadow-lg); padding: var(--spacing-xl);">
        <h2 class="mb-xs text-center poppins-bold" style="font-size: 24px;">Registration Form</h2>
        <p class="section-desc text-center mb-xl poppins-regular" style="font-size: 14px;">Select a role and tell us how you would like to contribute. The team will review your submission.</p>

        <div id="form-feedback" aria-live="polite"></div>

        <form id="register-form" method="POST" action="process_registration.php">
            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
            <div hidden aria-hidden="true" style="position:absolute; left:-10000px;">
                <label for="registration_company_website">Leave this field empty</label>
                <input type="text" id="registration_company_website" name="company_website" tabindex="-1" autocomplete="off">
            </div>
            <div class="form-group">
                <label class="form-label" for="first_name">First Name</label>
                <input class="form-control" type="text" id="first_name" name="first_name" placeholder="First name" style="width:100%" required/>
            </div>
            <div class="form-group">
                <label class="form-label" for="last_name">Last Name</label>
                <input class="form-control" type="text" id="last_name" name="last_name" placeholder="Last name" style="width:100%" required/>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input class="form-control" type="email" id="email" name="email" placeholder="you@example.com" required/>
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Phone Number</label>
                <div style="display: flex; gap: 8px;">
                    <select class="form-control country-code-select" name="country_code" data-selected="+254" style="width: 130px; padding: 10px; background: var(--background); color: var(--on-background); border: 1px solid var(--outline-variant); border-radius: var(--radius-lg); cursor: pointer;" required></select>
                    <input class="form-control" type="tel" id="phone" name="phone" placeholder="705 555 6565" style="flex: 1;" required/>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="role">Role of Interest</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="" disabled <?php echo $selected_role === '' ? 'selected' : ''; ?>>Select a role...</option>
                    <option value="volunteer" <?php echo $selected_role === 'volunteer' ? 'selected' : ''; ?>>Volunteer (Contribute Time & Skills)</option>
                    <option value="partner" <?php echo $selected_role === 'partner' ? 'selected' : ''; ?>>Strategic Partner (Organization Alignment)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="message">How would you like to help?</label>
                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Briefly introduce yourself or mention specific interests..." required><?php echo h($default_message); ?></textarea>
                <div id="word-count-display" style="font-size: 12px; margin-top: 6px; color: var(--on-surface-variant); text-align: right; font-weight: 500; transition: color 0.2s;">0 / 200 words</div>
            </div>

            <button type="submit" class="btn btn-primary w-full mt-md" style="padding: 14px 24px; border-radius: var(--radius-full); font-size: 14px;">Submit Registration</button>
        </form>
    </div>
</section>

<script src="js/country-codes.js" defer></script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
