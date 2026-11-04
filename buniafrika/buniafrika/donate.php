<?php

$page_title = 'Donate and Finance | BuniAfrika CBO';
$meta_desc = 'For donation and finance enquiries, contact BuniAfrika CBO’s finance team.';

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-overlay"></div>
    <img class="hero-bg" style="opacity: 0.65;" src="<?php echo $base_path; ?>assets/brad.jpeg" alt="Community members at an organisational activity" />
    <div class="hero-content">
        <h1 class="hero-title poppins-bold">Support Our Cause</h1>
        <p class="hero-desc poppins-medium">Your generous contribution directly fuels peacebuilding, environmental conservation, youth empowerment, and humanitarian aid in the Coastal Region.</p>
    </div>
</section>

<!-- Main Donation Section -->
<section class="section container">
    <div class="bento-grid">
        <!-- Left Pane: Donation Methods -->
        <div class="col-span-5 flex" style="flex-direction: column; gap: var(--spacing-lg);">
            <!-- Impact Message Card -->
            <div class="bento-card bento-card-primary" style="padding: var(--spacing-lg);">
                <h2 class="mb-sm poppins-bold" style="color: #ffffff; font-size: 20px;">Every Contribution Counts</h2>
                <p style="font-size: 14px; opacity: 0.95; line-height: 1.6;">
                    For verified donation instructions, please contact BuniAfrika’s finance team before sending funds. The organisation’s current payment details have not been verified for this website.
                </p>
            </div>

            <!-- M-Pesa Card -->
            <div class="bento-card" style="box-shadow: var(--shadow-sm); padding: var(--spacing-lg); justify-content: flex-start; border-left: 5px solid #2ecc71;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: var(--spacing-md);">
                    <span class="material-symbols-outlined" style="font-size: 32px; color: #2ecc71;">payments</span>
                    <h3 class="poppins-bold" style="margin: 0; font-size: 20px;">Lipa Na M-Pesa</h3>
                </div>
                
                <div style="display: flex; flex-direction: column; gap: var(--spacing-sm); font-size: 14px;">
                    <p>Contact <a href="mailto:<?php echo h($site_settings['finance_email']); ?>"><?php echo h($site_settings['finance_email']); ?></a> for verified M-Pesa instructions before making a payment.</p>
                    <div style="background: var(--surface-container-low); padding: 12px 16px; border-radius: var(--radius-lg); border: 1px dashed var(--outline-variant);">
                        <strong style="color: var(--on-surface);">Payment details are provided by the finance team.</strong>
                    </div>
                    <div style="background: var(--surface-container-low); padding: 12px 16px; border-radius: var(--radius-lg); border: 1px dashed var(--outline-variant);">
                        <span>Use only payment instructions confirmed directly by BuniAfrika finance.</span>
                    </div>
                </div>
            </div>

            <!-- Bank Transfer Card -->
            <div class="bento-card" style="box-shadow: var(--shadow-sm); padding: var(--spacing-lg); justify-content: flex-start; border-left: 5px solid var(--primary);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: var(--spacing-md);">
                    <span class="material-symbols-outlined" style="font-size: 32px; color: var(--primary);">account_balance</span>
                    <h3 class="poppins-bold" style="margin: 0; font-size: 20px;">Bank Transfer</h3>
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px; font-size: 14px;">
                    <p>Request current bank transfer instructions from <a href="mailto:<?php echo h($site_settings['finance_email']); ?>"><?php echo h($site_settings['finance_email']); ?></a>. Previous payment details are not confirmed for this site.</p>
                    <div style="background: var(--surface-container-low); padding: var(--spacing-md); border-radius: var(--radius-lg); border: 1px solid var(--outline-variant); display: flex; flex-direction: column; gap: 6px;">
                        <strong>Contact the finance team for verified account details.</strong>
                        <a href="mailto:<?php echo h($site_settings['finance_email']); ?>"><?php echo h($site_settings['finance_email']); ?></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Pane: Submit Donation Form -->
        <div class="col-span-7 bento-card" style="box-shadow: var(--shadow-md); padding: var(--spacing-lg); justify-content: flex-start;">
            <h2 class="mb-xs poppins-bold" style="font-size: 20px;">Report / Register Your Donation</h2>
            <p class="section-desc mb-xl" style="font-size: 14px;">
                After completing your payment via M-Pesa or Bank, please use the form below to register it. Our team will verify and log your donation to ensure transparency and accountability.
            </p>

            <!-- AJAX Form feedback container -->
            <div id="form-feedback" aria-live="polite"></div>

            <form id="donation-form" method="POST" action="process_donation.php">
                <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                <div hidden aria-hidden="true" style="position:absolute; left:-10000px;">
                    <label for="donation_company_website">Leave this field empty</label>
                    <input type="text" id="donation_company_website" name="company_website" tabindex="-1" autocomplete="off">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="name">Your Name</label>
                    <input class="form-control" type="text" id="name" name="name" placeholder="Full Name or Anonymous" required />
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input class="form-control" type="email" id="email" name="email" placeholder="you@example.com" required />
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone">Phone Number</label>
                    <div style="display: flex; gap: 8px;">
                        <select class="form-control country-code-select" name="country_code" data-selected="+254"
                            style="width: 130px; padding: 10px; background: var(--background); color: var(--on-background); border: 1px solid var(--outline-variant); border-radius: var(--radius-lg); cursor: pointer;"
                            required></select>
                        <input class="form-control" type="tel" id="phone" name="phone" placeholder="705 556565" style="flex: 1;" required />
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="currency_select">Currency & Amount</label>
                    <div style="display: flex; gap: 12px; align-items: center; margin-bottom: 8px;">
                        <select class="form-control" id="currency_select" name="currency" style="width: 100px; padding: 10px; border-radius: var(--radius-lg); border: 1px solid var(--outline-variant); background: var(--background); color: var(--on-background); font-weight: 700; cursor: pointer;">
                            <option value="KES">KES (KSh)</option>
                            <option value="USD">USD ($)</option>
                        </select>
                        <input class="form-control" type="number" step="any" min="1" id="amount" name="amount" placeholder="Amount" style="flex: 1;" required />
                    </div>

                    <!-- Preset Amount Buttons -->
                    <div id="presets-container" style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px;">
                        <button type="button" class="btn btn-outline preset-btn" data-value="500" style="padding: 6px 12px; font-size: 12px; border-radius: var(--radius-lg);">KES 500</button>
                        <button type="button" class="btn btn-outline preset-btn" data-value="1000" style="padding: 6px 12px; font-size: 12px; border-radius: var(--radius-lg);">KES 1,000</button>
                        <button type="button" class="btn btn-outline preset-btn" data-value="2000" style="padding: 6px 12px; font-size: 12px; border-radius: var(--radius-lg);">KES 2,000</button>
                        <button type="button" class="btn btn-outline preset-btn" data-value="5000" style="padding: 6px 12px; font-size: 12px; border-radius: var(--radius-lg);">KES 5,000</button>
                        <button type="button" class="btn btn-outline preset-btn" data-value="10000" style="padding: 6px 12px; font-size: 12px; border-radius: var(--radius-lg);">KES 10,000</button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="payment_method">Payment Method</label>
                    <select class="form-control" id="payment_method" name="payment_method" style="width: 100%; padding: 12px; border-radius: var(--radius-lg); border: 1px solid var(--outline-variant); background: var(--background); color: var(--on-background); cursor: pointer;" required>
                        <option value="">-- Select Payment Method --</option>
                        <option value="M-Pesa">M-Pesa</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="PayPal">PayPal</option>
                        <option value="Credit/Debit Card">Credit/Debit Card</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="transaction_reference">Transaction Reference / Receipt Code</label>
                    <input class="form-control" type="text" id="transaction_reference" name="transaction_reference" placeholder="e.g. M-Pesa Code RJ54X9DK8F or Bank Ref" required />
                    <span style="font-size: 11px; color: var(--on-surface-variant); display: block; margin-top: 4px;">Important: Please enter the exact transaction code to help us match your payment.</span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="message">Message / Notes (Optional)</label>
                    <textarea class="form-control" id="message" name="message" rows="4" placeholder="Would you like us to designate this donation to a specific initiative (e.g. Health, Blue Econpomy, Youth Empowerment) or leave a note?"></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-full mt-md" style="padding: 12px 24px; font-size: 14px;">Submit Donation Report</button>
            </form>
        </div>
    </div>
</section>

<!-- Preset amount switching JS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const currencySelect = document.getElementById('currency_select');
    const amountInput = document.getElementById('amount');
    const presetsContainer = document.getElementById('presets-container');

    const presetsList = {
        'KES': [
            { label: 'KES 500', value: 500 },
            { label: 'KES 1,000', value: 1000 },
            { label: 'KES 2,000', value: 2000 },
            { label: 'KES 5,000', value: 5000 },
            { label: 'KES 10,000', value: 10000 }
        ],
        'USD': [
            { label: '$10', value: 10 },
            { label: '$25', value: 25 },
            { label: '$50', value: 50 },
            { label: '$100', value: 100 },
            { label: '$250', value: 250 }
        ]
    };

    function updatePresets(currency) {
        presetsContainer.innerHTML = '';
        const list = presetsList[currency] || [];
        list.forEach(item => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-outline';
            btn.style.padding = '6px 12px';
            btn.style.fontSize = '12px';
            btn.style.borderRadius = 'var(--radius-lg)';
            btn.innerText = item.label;
            btn.addEventListener('click', function() {
                amountInput.value = item.value;
                
                // Highlight active preset
                presetsContainer.querySelectorAll('button').forEach(b => {
                    b.style.backgroundColor = 'transparent';
                    b.style.color = 'var(--primary)';
                });
                btn.style.backgroundColor = 'var(--primary)';
                btn.style.color = '#ffffff';
            });
            presetsContainer.appendChild(btn);
        });
    }

    currencySelect.addEventListener('change', function() {
        updatePresets(currencySelect.value);
        amountInput.value = '';
    });

    // Initialize
    updatePresets('KES');
});
</script>

<script src="js/country-codes.js" defer></script>
<script src="js/form_handler.js" defer></script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
