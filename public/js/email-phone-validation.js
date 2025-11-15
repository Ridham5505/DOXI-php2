(function () {
    const emailPattern = /^[^\s@]+@[A-Za-z0-9.-]+\.com$/i;
    const phonePattern = /^\d{10}$/;

    function attachEmailValidation(input) {
        if (!input) return;
        input.setAttribute('pattern', '^[^\\s@]+@[A-Za-z0-9.-]+\\.com$');
        input.setAttribute('inputmode', 'email');
        if (!input.getAttribute('autocomplete')) {
            input.setAttribute('autocomplete', 'email');
        }
        const message = input.getAttribute('data-email-message') || 'Please enter a valid email ending with .com';

        const handler = function () {
            const value = this.value.trim();
            if (!value) {
                this.setCustomValidity('');
                return;
            }
            if (!emailPattern.test(value)) {
                this.setCustomValidity(message);
            } else {
                this.setCustomValidity('');
            }
        };

        input.addEventListener('input', handler);
        input.addEventListener('blur', handler);
    }

    function attachPhoneValidation(input) {
        if (!input) return;
        input.setAttribute('pattern', '\\d{10}');
        input.setAttribute('maxlength', '10');
        input.setAttribute('inputmode', 'tel');
        const message = input.getAttribute('data-phone-message') || 'Please enter a 10-digit phone number';

        const handler = function () {
            const digitsOnly = this.value.replace(/[^0-9]/g, '').slice(0, 10);
            if (this.value !== digitsOnly) {
                this.value = digitsOnly;
            }
            if (!digitsOnly) {
                this.setCustomValidity('');
                return;
            }
            if (!phonePattern.test(digitsOnly)) {
                this.setCustomValidity(message);
            } else {
                this.setCustomValidity('');
            }
        };

        input.addEventListener('input', handler);
        input.addEventListener('blur', handler);
    }

    function needsPhoneValidation(input) {
        if (input.getAttribute('data-validate-phone') === 'false') return false;
        const name = (input.getAttribute('name') || '').toLowerCase();
        const id = (input.getAttribute('id') || '').toLowerCase();
        const type = (input.getAttribute('type') || '').toLowerCase();
        return (
            type === 'tel' ||
            name.includes('phone') ||
            id.includes('phone') ||
            input.dataset.validatePhone === 'true'
        );
    }

    function initValidation() {
        document.querySelectorAll('input[type="email"], input[data-validate-email="true"]').forEach(attachEmailValidation);
        document.querySelectorAll('input').forEach(input => {
            if (needsPhoneValidation(input)) {
                attachPhoneValidation(input);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initValidation);
    } else {
        initValidation();
    }
})();

