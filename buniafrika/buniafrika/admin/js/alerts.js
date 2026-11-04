/**
 * BuniAfrika Standard Notification & Form Feedback Utility
 * Uses SweetAlert2 for accessible, consistent, and beautiful alerts across the site.
 */
(function (global) {
    'use strict';

    // Ensure SweetAlert2 is available
    function hasSwal() {
        return typeof global.Swal !== 'undefined';
    }

    // Default custom theme configuration for Buniafrika
    const defaultTheme = {
        confirmButtonColor: '#1d4ed8', // BuniAfrika Primary Blue
        cancelButtonColor: '#64748b',  // Slate
        denyButtonColor: '#b91c1c',    // Danger Crimson
        focusConfirm: true,
        backdrop: 'rgba(15, 23, 42, 0.45)',
        customClass: {
            popup: 'buniafrika-swal-popup',
            title: 'buniafrika-swal-title',
            confirmButton: 'buniafrika-swal-btn-confirm',
            cancelButton: 'buniafrika-swal-btn-cancel'
        }
    };

    const BuniAlert = {
        /**
         * Display a success alert
         */
        success: function (title, message, options = {}) {
            if (!hasSwal()) {
                alert((title ? title + ': ' : '') + (message || ''));
                return Promise.resolve();
            }
            return global.Swal.fire({
                ...defaultTheme,
                icon: 'success',
                title: title || 'Submission Successful',
                text: message || 'Your information has been submitted successfully.',
                timer: options.timer || (options.autoClose ? 3500 : undefined),
                timerProgressBar: Boolean(options.timer || options.autoClose),
                confirmButtonText: options.confirmButtonText || 'OK',
                ...options
            });
        },

        /**
         * Display an error alert
         */
        error: function (title, message, options = {}) {
            if (!hasSwal()) {
                alert('Error: ' + (title ? title + ' - ' : '') + (message || 'An error occurred.'));
                return Promise.resolve();
            }
            return global.Swal.fire({
                ...defaultTheme,
                icon: 'error',
                title: title || 'Submission Failed',
                text: message || 'An error occurred while processing your request. Please try again.',
                confirmButtonText: options.confirmButtonText || 'Dismiss',
                ...options
            });
        },

        /**
         * Display a warning alert
         */
        warning: function (title, message, options = {}) {
            if (!hasSwal()) {
                alert('Warning: ' + (message || ''));
                return Promise.resolve();
            }
            return global.Swal.fire({
                ...defaultTheme,
                icon: 'warning',
                title: title || 'Notice',
                text: message || '',
                confirmButtonText: options.confirmButtonText || 'Understood',
                ...options
            });
        },

        /**
         * Display an info alert
         */
        info: function (title, message, options = {}) {
            if (!hasSwal()) {
                alert((title ? title + ': ' : '') + message);
                return Promise.resolve();
            }
            return global.Swal.fire({
                ...defaultTheme,
                icon: 'info',
                title: title || 'Information',
                text: message || '',
                confirmButtonText: options.confirmButtonText || 'OK',
                ...options
            });
        },

        /**
         * Confirmation dialog for destructive actions
         * Returns a Promise resolving to true if confirmed, false if cancelled
         */
        confirm: function (title, message, confirmBtnText = 'Yes, continue', cancelBtnText = 'Cancel', options = {}) {
            if (!hasSwal()) {
                const confirmed = confirm((title ? title + '\n\n' : '') + (message || 'Are you sure?'));
                return Promise.resolve(confirmed);
            }
            return global.Swal.fire({
                ...defaultTheme,
                icon: options.icon || 'warning',
                title: title || 'Are you sure?',
                text: message || 'This action cannot be undone.',
                showCancelButton: true,
                confirmButtonColor: options.confirmButtonColor || '#b91c1c', // Default red for destructive
                cancelButtonColor: options.cancelButtonColor || '#64748b',
                confirmButtonText: confirmBtnText,
                cancelButtonText: cancelBtnText,
                reverseButtons: true,
                focusCancel: true,
                ...options
            }).then((result) => {
                return Boolean(result.isConfirmed);
            });
        },

        /**
         * Display field-level validation feedback and a summary alert
         * @param {HTMLFormElement} form
         * @param {Object} errors Map of field name/id to error message string
         * @param {string} summaryTitle
         * @param {string} summaryText
         */
        validation: function (form, errors = {}, summaryTitle = 'Please Check Your Input', summaryText = 'Some fields require correction before submitting.') {
            this.clearValidation(form);

            let firstInvalid = null;
            const messages = [];

            if (typeof errors === 'string') {
                messages.push(errors);
            } else if (typeof errors === 'object' && errors !== null) {
                for (const [key, msg] of Object.entries(errors)) {
                    messages.push(msg);
                    if (form) {
                        const field = form.elements[key] || form.querySelector(`[name="${key}"]`) || form.querySelector(`#${key}`);
                        if (field) {
                            field.classList.add('is-invalid');
                            field.setAttribute('aria-invalid', 'true');

                            // Find or create invalid feedback element
                            let feedback = field.parentNode.querySelector(`.field-error[data-for="${key}"]`);
                            if (!feedback) {
                                feedback = document.createElement('div');
                                feedback.className = 'field-error text-danger mt-xs font-sm';
                                feedback.setAttribute('data-for', key);
                                feedback.setAttribute('role', 'alert');
                                if (field.nextSibling) {
                                    field.parentNode.insertBefore(feedback, field.nextSibling);
                                } else {
                                    field.parentNode.appendChild(feedback);
                                }
                            }
                            feedback.textContent = msg;

                            if (!firstInvalid) {
                                firstInvalid = field;
                            }
                        }
                    }
                }
            }

            if (firstInvalid) {
                firstInvalid.focus();
            }

            const alertText = messages.length > 0 ? messages.join('\n') : summaryText;
            return this.error(summaryTitle, alertText);
        },

        /**
         * Clear validation state from a form
         */
        clearValidation: function (form) {
            if (!form) return;
            const invalids = form.querySelectorAll('.is-invalid, [aria-invalid="true"]');
            invalids.forEach(el => {
                el.classList.remove('is-invalid');
                el.removeAttribute('aria-invalid');
            });
            const errors = form.querySelectorAll('.field-error, .invalid-feedback');
            errors.forEach(el => el.remove());
        },

        /**
         * Rate limit notice with countdown timer
         */
        rateLimit: function (retryAfterSeconds = 60) {
            const sec = parseInt(retryAfterSeconds, 10) || 60;
            const title = 'Submission Limit Reached';
            const message = `You have reached the maximum number of attempts from your network. Please wait ${sec} seconds before trying again.`;

            if (!hasSwal()) {
                alert(title + '\n\n' + message);
                return Promise.resolve();
            }

            let timerInterval;
            return global.Swal.fire({
                ...defaultTheme,
                icon: 'warning',
                title: title,
                html: `Too many submissions received. To prevent abuse, please wait <strong id="swal-rate-timer" class="text-primary font-bold">${sec}</strong> seconds before submitting again.`,
                timer: sec * 1000,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    const timer = global.Swal.getPopup().querySelector('#swal-rate-timer');
                    timerInterval = setInterval(() => {
                        const left = Math.ceil(global.Swal.getTimerLeft() / 1000);
                        if (timer) timer.textContent = left;
                    }, 1000);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
            });
        },

        /**
         * Set button to loading / processing state and disable it
         */
        setButtonLoading: function (button, loadingText = 'Processing...') {
            if (!button) return;
            if (!button.dataset.originalHtml) {
                button.dataset.originalHtml = button.innerHTML;
            }
            button.disabled = true;
            button.setAttribute('aria-busy', 'true');
            button.innerHTML = `<span class="material-symbols-outlined animate-spin" style="font-size:16px; margin-right:8px; vertical-align:middle; display:inline-block; animation: buniSpin 1s linear infinite;">sync</span>${loadingText}`;
        },

        /**
         * Restore button to original state
         */
        restoreButton: function (button) {
            if (!button) return;
            if (button.dataset.originalHtml) {
                button.innerHTML = button.dataset.originalHtml;
                delete button.dataset.originalHtml;
            }
            button.disabled = false;
            button.removeAttribute('aria-busy');
        }
    };

    // Inject CSS for SweetAlert spinner animation and styling
    if (typeof document !== 'undefined') {
        const style = document.createElement('style');
        style.id = 'buniafrika-alerts-style';
        style.textContent = `
            @keyframes buniSpin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            .is-invalid {
                border-color: #dc2626 !important;
                background-color: #fef2f2 !important;
                box-shadow: 0 0 0 1px #dc2626 !important;
            }
            .field-error {
                color: #dc2626;
                font-size: 0.825rem;
                margin-top: 0.25rem;
                font-weight: 500;
            }
            .buniafrika-swal-popup {
                font-family: 'Public Sans', system-ui, -apple-system, sans-serif !important;
                border-radius: 12px !important;
                padding: 1.75rem !important;
            }
            .buniafrika-swal-title {
                font-size: 1.35rem !important;
                font-weight: 700 !important;
                color: #0f172a !important;
            }
            .buniafrika-swal-btn-confirm, .buniafrika-swal-btn-cancel {
                border-radius: 8px !important;
                font-weight: 600 !important;
                padding: 0.625rem 1.25rem !important;
                font-size: 0.95rem !important;
            }
        `;
        document.head.appendChild(style);
    }

    // Attach to global window object
    global.BuniAlert = BuniAlert;

})(typeof window !== 'undefined' ? window : this);
