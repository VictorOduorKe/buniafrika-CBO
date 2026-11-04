
document.addEventListener('DOMContentLoaded', () => {
    initFormHandlers();
});

function initFormHandlers() {
    const contactForm = document.getElementById('contact-form');
    const registerForm = document.getElementById('register-form');

    if (contactForm) {
        contactForm.addEventListener('submit', (e) => handleFormSubmit(e, 'process_contact.php'));
    }

    if (registerForm) {
        registerForm.addEventListener('submit', (e) => handleFormSubmit(e, 'process_registration.php'));
    }
      const donationForm = document.getElementById('donation-form');
    if (donationForm) {
        donationForm.addEventListener('submit', (e) => handleFormSubmit(e, 'process_donation.php'));
    }

    const newsletterForm = document.getElementById('sidebar-newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', (e) => handleFormSubmit(e, (window.__i18nBase || '') + 'process_newsletter.php'));
    }
    const projectNewsletterForm = document.getElementById('project-newsletter-form');
    if (projectNewsletterForm) {
        projectNewsletterForm.addEventListener('submit', (e) => handleFormSubmit(e, 'process_newsletter.php'));
    }
}

function isLocalHostname(hostname) {
    if (!hostname) return false;
    return hostname === 'localhost' ||
           hostname === '127.0.0.1' ||
           hostname === '::1' ||
           hostname.endsWith('.localhost') ||
           hostname.endsWith('.local') ||
           hostname.endsWith('.test');
}

function getApiEndpoint(endpoint) {
    if (!endpoint) return '';
    if (endpoint.startsWith('http://') || endpoint.startsWith('https://')) {
        return endpoint;
    }

    const cleanEndpoint = endpoint.split(/[?#]/)[0].replace(/^\/+/, '');
    const isBackendApi = cleanEndpoint.startsWith('process_') ||
                         cleanEndpoint.startsWith('api.php') ||
                         cleanEndpoint.startsWith('api/') ||
                         cleanEndpoint.startsWith('translate.php') ||
                         cleanEndpoint.startsWith('download-publication.php');

    if (!isBackendApi) {
        return endpoint;
    }

    const base = (window.__apiBase || window.__backendUrl || '').replace(/\/+$/, '');
    if (!base) {
        return endpoint;
    }

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

function handleFormSubmit(event, endpoint) {
    event.preventDefault();
    const form = event.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    const alertBox = document.getElementById('form-feedback');

    let feedbackEl = alertBox;
    if (!feedbackEl) {
        feedbackEl = document.createElement('div');
        feedbackEl.id = 'form-feedback';
        feedbackEl.className = 'alert';
        feedbackEl.setAttribute('aria-live', 'polite');
        form.insertBefore(feedbackEl, form.firstChild);
    }

    const formData = new FormData(form);
    let hasError = false;

    const email = formData.get('email');
    if (email !== null) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email.trim())) {
            showFeedback(feedbackEl, 'Please enter a valid email address.', 'danger');
            hasError = true;
        }
    }

    if (hasError) {
        return;
    }

    const originalBtnText = submitBtn ? submitBtn.innerHTML : 'Submit';
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin" style="font-size:16px; margin-right:8px; vertical-align:middle;">sync</span>Processing...';
    }

    const targetUrl = getApiEndpoint(endpoint);
    const isCrossOrigin = targetUrl.startsWith('http') && !targetUrl.startsWith(window.location.origin);

    fetch(targetUrl, {
        method: 'POST',
        body: formData,
        credentials: isCrossOrigin ? 'include' : 'same-origin'
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showFeedback(feedbackEl, data.message, 'success');
                form.reset();
            } else if (data.status === 'warning') {
                showFeedback(feedbackEl, data.message, 'warning');
                form.reset();
            } else {
                showFeedback(feedbackEl, data.message || 'An error occurred. Please try again.', 'danger');
            }
        })
        .catch(error => {
            console.error('Submission error:', error);
            showFeedback(feedbackEl, 'Could not reach server. Please check your internet connection and try again.', 'danger');
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
}

function showFeedback(element, message, type) {
    element.className = `alert alert-${type} mb-md`;
    element.textContent = message;
    element.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
