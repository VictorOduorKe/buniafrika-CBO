/**
 * BuniAfrika Website-Wide AJAX Form Submission & SweetAlert2 Feedback Handler
 * Handles validation, double-submission protection, CSRF inclusion, rate-limiting, and error preservation.
 */
document.addEventListener('DOMContentLoaded', () => {
    initFormHandlers();
});

function initFormHandlers() {
    // 1. Public Contact Form
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', (e) => handleFormSubmit(e, 'process_contact.php', {
            validate: validateContactForm
        }));
    }

    // 2. Member / Partner Registration Form
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', (e) => handleFormSubmit(e, 'process_registration.php', {
            validate: validateRegisterForm
        }));
    }

    // 3. Donation Intent Form
    const donationForm = document.getElementById('donation-form');
    if (donationForm) {
        donationForm.addEventListener('submit', (e) => handleFormSubmit(e, 'process_donation.php', {
            validate: validateDonationForm
        }));
    }

    // 4. Sidebar Newsletter Form
    const newsletterForm = document.getElementById('sidebar-newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', (e) => handleFormSubmit(e, (window.__i18nBase || '') + 'process_newsletter.php', {
            validate: validateNewsletterForm
        }));
    }

    // 5. Projects Newsletter Form
    const projectNewsletterForm = document.getElementById('project-newsletter-form');
    if (projectNewsletterForm) {
        projectNewsletterForm.addEventListener('submit', (e) => handleFormSubmit(e, 'process_newsletter.php', {
            validate: validateNewsletterForm
        }));
    }

    // 6. Public Volunteer Application Form
    const volunteerForm = document.querySelector('#volunteer-application-form form');
    if (volunteerForm) {
        volunteerForm.addEventListener('submit', (e) => handleFormSubmit(e, 'volunteer.php', {
            validate: validateVolunteerForm
        }));
    }

    // 7. Community Article Submission Form
    const articleForm = document.getElementById('submit-article-form');
    if (articleForm) {
        articleForm.addEventListener('submit', (e) => handleFormSubmit(e, 'submit_article.php', {
            validate: validateArticleForm,
            isMultipart: true
        }));
    }

    // Generic handler for any form flagged with data-buni-ajax="true"
    document.querySelectorAll('form[data-buni-ajax="true"]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            const action = form.getAttribute('action') || window.location.href;
            handleFormSubmit(e, action);
        });
    });

    // Clear validation feedback on input
    document.addEventListener('input', (e) => {
        const input = e.target;
        if (input.classList && input.classList.contains('is-invalid')) {
            input.classList.remove('is-invalid');
            input.removeAttribute('aria-invalid');
            const err = input.parentNode ? input.parentNode.querySelector(`.field-error[data-for="${input.name}"]`) : null;
            if (err) err.remove();
        }
    });
}

/**
 * Helper to check if a hostname is local development
 */
function isLocalHostname(hostname) {
    if (!hostname) return false;
    return hostname === 'localhost' ||
           hostname === '127.0.0.1' ||
           hostname === '::1' ||
           hostname.endsWith('.localhost') ||
           hostname.endsWith('.local') ||
           hostname.endsWith('.test');
}

/**
 * Resolve target URL ensuring correct production backend vs local path
 */
function getApiEndpoint(endpoint) {
    if (!endpoint) return '';
    if (endpoint.startsWith('http://') || endpoint.startsWith('https://')) {
        return endpoint;
    }

    // Clean query/hash for endpoint check
    const cleanEndpoint = endpoint.split(/[?#]/)[0].replace(/^\/+/, '');

    // List of known backend API endpoints that should route to BACKEND_API_URL
    const isBackendApi = cleanEndpoint.startsWith('process_') ||
                         cleanEndpoint.startsWith('api.php') ||
                         cleanEndpoint.startsWith('api/') ||
                         cleanEndpoint.startsWith('translate.php') ||
                         cleanEndpoint.startsWith('download-publication.php');

    // Page-level endpoints (volunteer.php, submit_article.php, etc.) belong to the frontend and stay on current origin
    if (!isBackendApi) {
        return endpoint;
    }

    const base = (window.__apiBase || window.__backendUrl || '').replace(/\/+$/, '');
    if (!base) {
        return endpoint;
    }

    // If running in local development (localhost / 127.0.0.1):
    // Do NOT route local requests to a remote production backend (avoids CORS blocks & prevents writing to prod DB).
    if (typeof window !== 'undefined' && window.location && isLocalHostname(window.location.hostname)) {
        let baseHostname = '';
        try {
            baseHostname = new URL(base).hostname;
        } catch (e) {
            baseHostname = '';
        }
        if (!isLocalHostname(baseHostname)) {
            return endpoint;
        }
    }

    return base + '/' + cleanEndpoint;
}

/**
 * Universal Form Submission Handler
 */
function handleFormSubmit(event, endpoint, options = {}) {
    event.preventDefault();
    const form = event.target;

    // Prevent double submission
    if (form.dataset.submitting === 'true') {
        return;
    }

    const submitBtn = form.querySelector('button[type="submit"]') || form.querySelector('input[type="submit"]');

    // Run custom client validation if provided
    if (typeof options.validate === 'function') {
        const errors = options.validate(form);
        if (errors && Object.keys(errors).length > 0) {
            if (window.BuniAlert) {
                window.BuniAlert.validation(form, errors, 'Check Required Fields', 'Please correct the highlighted fields before submitting.');
            }
            return;
        }
    }

    if (window.BuniAlert) {
        window.BuniAlert.clearValidation(form);
    }

    // Mark submitting & disable button
    form.dataset.submitting = 'true';
    if (window.BuniAlert && submitBtn) {
        window.BuniAlert.setButtonLoading(submitBtn, options.loadingText || 'Processing...');
    }

    const formData = new FormData(form);

    // Extract CSRF token
    const csrfToken = formData.get('csrf_token') || (document.querySelector('input[name="csrf_token"]') ? document.querySelector('input[name="csrf_token"]').value : '');

    const targetUrl = getApiEndpoint(endpoint);
    const isCrossOrigin = targetUrl.startsWith('http') && !targetUrl.startsWith(window.location.origin);

    const headers = {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json, text/plain, */*'
    };
    if (csrfToken) {
        headers['X-CSRF-Token'] = csrfToken;
    }

    fetch(targetUrl, {
        method: 'POST',
        headers: headers,
        body: formData,
        credentials: isCrossOrigin ? 'include' : 'same-origin'
    })
    .then(async (response) => {
        let data = {};
        const contentType = response.headers.get('content-type') || '';
        if (contentType.includes('application/json')) {
            try {
                data = await response.json();
            } catch (err) {
                data = { status: 'error', message: 'Invalid response from server.' };
            }
        } else {
            const text = await response.text();
            try {
                data = JSON.parse(text);
            } catch (e) {
                // If the response is HTML, check status code
                if (response.ok) {
                    data = { status: 'success', message: 'Your submission was received.' };
                } else if (response.status === 429) {
                    data = { status: 'error', code: 'RATE_LIMIT_EXCEEDED', message: 'Too many submissions. Please wait before trying again.' };
                } else {
                    data = { status: 'error', message: 'Server returned an error status (' + response.status + ').' };
                }
            }
        }

        // Handle Rate Limiting (HTTP 429)
        if (response.status === 429 || data.code === 'RATE_LIMIT_EXCEEDED') {
            const retryAfter = data.retry_after || response.headers.get('Retry-After') || 60;
            if (window.BuniAlert) {
                window.BuniAlert.rateLimit(retryAfter);
            } else {
                alert('Rate limit exceeded: Please wait ' + retryAfter + ' seconds before trying again.');
            }
            return;
        }

        // Handle CSRF Security Failure (HTTP 403)
        if (response.status === 403 || data.code === 'CSRF_INVALID') {
            if (window.BuniAlert) {
                window.BuniAlert.error('Security Token Expired', data.message || 'Your security session has expired. Please refresh the page and try again.');
            } else {
                alert('Security session expired. Please refresh and try again.');
            }
            return;
        }

        // Handle Success
        if (response.ok && (data.status === 'success' || data.status === 'warning')) {
            const title = data.status === 'warning' ? 'Notice' : 'Submission Successful';
            const msg = data.message || 'Your information has been submitted successfully.';

            if (window.BuniAlert) {
                window.BuniAlert[data.status === 'warning' ? 'warning' : 'success'](title, msg).then(() => {
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    }
                });
            } else {
                alert(title + ': ' + msg);
            }

            // Only clear the form when submission succeeds!
            form.reset();
            if (window.BuniAlert) {
                window.BuniAlert.clearValidation(form);
            }
            return;
        }

        // Handle Validation Failure (HTTP 422) or Other Error
        const errorMsg = data.message || 'An error occurred while processing your request. Please check your entries and try again.';
        if (data.errors && typeof data.errors === 'object') {
            if (window.BuniAlert) {
                window.BuniAlert.validation(form, data.errors, 'Submission Incomplete', errorMsg);
            } else {
                alert('Error: ' + errorMsg);
            }
        } else {
            if (window.BuniAlert) {
                window.BuniAlert.error('Submission Failed', errorMsg);
            } else {
                alert('Error: ' + errorMsg);
            }
        }
        // Note: form is NOT reset on failure! User data is strictly preserved.
    })
    .catch((error) => {
        console.error('Submission network error:', error);
        if (window.BuniAlert) {
            window.BuniAlert.error('Network Error', 'Could not connect to the server. Please check your internet connection and try again.');
        } else {
            alert('Could not connect to the server. Please check your connection and try again.');
        }
        // Preserve user input on network failure
    })
    .finally(() => {
        if (window.BuniAlert && submitBtn) {
            window.BuniAlert.restoreButton(submitBtn);
        }
        delete form.dataset.submitting;
    });
}

// ------------------------------------------------------------------------------
// Client-Side Validation Rules (Fast Feedback)
// ------------------------------------------------------------------------------

function validateContactForm(form) {
    const errors = {};
    const name = form.querySelector('[name="name"]');
    const email = form.querySelector('[name="email"]');
    const subject = form.querySelector('[name="subject"]');
    const message = form.querySelector('[name="message"]');
    const phone = form.querySelector('[name="phone"]');

    if (name && !name.value.trim()) {
        errors.name = 'Please provide your full name.';
    }
    if (email) {
        const val = email.value.trim();
        if (!val) {
            errors.email = 'Please provide your email address.';
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
            errors.email = 'Please provide a valid email address.';
        }
    }
    if (phone && phone.value.trim()) {
        if (!/^[+0-9()\s.-]{7,30}$/.test(phone.value.trim())) {
            errors.phone = 'Please enter a valid phone number (e.g. +254 700 000000).';
        }
    }
    if (subject && !subject.value.trim()) {
        errors.subject = 'Please enter an enquiry subject.';
    }
    if (message && !message.value.trim()) {
        errors.message = 'Please provide your message.';
    }
    return errors;
}

function validateRegisterForm(form) {
    const errors = {};
    const firstName = form.querySelector('[name="first_name"]');
    const lastName = form.querySelector('[name="last_name"]');
    const name = form.querySelector('[name="name"]');
    const email = form.querySelector('[name="email"]');

    if (firstName && !firstName.value.trim()) {
        errors.first_name = 'First name is required.';
    }
    if (lastName && !lastName.value.trim()) {
        errors.last_name = 'Last name is required.';
    }
    if (name && !name.value.trim() && !firstName) {
        errors.name = 'Full name is required.';
    }
    if (email) {
        const val = email.value.trim();
        if (!val || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
            errors.email = 'A valid email address is required.';
        }
    }
    return errors;
}

function validateDonationForm(form) {
    const errors = {};
    const name = form.querySelector('[name="name"]');
    const email = form.querySelector('[name="email"]');
    const amount = form.querySelector('[name="amount"]');
    const method = form.querySelector('[name="payment_method"]');

    if (name && !name.value.trim()) {
        errors.name = 'Your name is required.';
    }
    if (email) {
        const val = email.value.trim();
        if (!val || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
            errors.email = 'A valid email address is required.';
        }
    }
    if (amount) {
        const val = parseFloat(amount.value);
        if (isNaN(val) || val <= 0) {
            errors.amount = 'Please enter a valid donation amount.';
        }
    }
    if (method && !method.value) {
        errors.payment_method = 'Please select a payment method.';
    }
    return errors;
}

function validateNewsletterForm(form) {
    const errors = {};
    const email = form.querySelector('[name="email"]');
    if (email) {
        const val = email.value.trim();
        if (!val || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
            errors.email = 'Please enter a valid email address.';
        }
    }
    return errors;
}

function validateVolunteerForm(form) {
    const errors = {};
    const name = form.querySelector('[name="applicant_name"]');
    const email = form.querySelector('[name="applicant_email"]');
    const phone = form.querySelector('[name="applicant_phone"]');

    if (name && !name.value.trim()) {
        errors.applicant_name = 'Please provide your full name.';
    }
    if (email) {
        const val = email.value.trim();
        if (!val || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
            errors.applicant_email = 'A valid email address is required.';
        }
    }
    if (phone && phone.value.trim()) {
        if (!/^[+0-9()\s.-]{7,30}$/.test(phone.value.trim())) {
            errors.applicant_phone = 'Please provide a valid phone number.';
        }
    }
    return errors;
}

function validateArticleForm(form) {
    const errors = {};
    const title = form.querySelector('[name="title"]');
    const summary = form.querySelector('[name="summary"]');
    const content = form.querySelector('[name="content"]');

    if (title && !title.value.trim()) {
        errors.title = 'Article title is required.';
    }
    if (summary && !summary.value.trim()) {
        errors.summary = 'A short summary is required.';
    }
    if (content && !content.value.trim()) {
        errors.content = 'Article body content is required.';
    }
    return errors;
}
