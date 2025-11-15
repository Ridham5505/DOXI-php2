<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Patient Registration</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .register-container{min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--light-blue) 0%, var(--white) 100%);padding:var(--spacing-6)}
        .register-card{background:#fff;padding:var(--spacing-12);border-radius:var(--radius-2xl);box-shadow:var(--shadow-xl);width:100%;max-width:640px}
        .register-header{margin-bottom:var(--spacing-8);text-align:center}
        .register-header .logo{font-size:var(--font-size-3xl);font-weight:800;color:var(--primary-blue)}
        .register-header .subtitle{color:var(--gray-600)}
        .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:var(--spacing-4)}
        .form-group{margin-bottom:var(--spacing-4)}
        label{display:block;margin-bottom:var(--spacing-2);font-weight:600;color:var(--gray-700)}
        input,select{width:100%;padding:var(--spacing-3) var(--spacing-4);border:2px solid var(--gray-200);border-radius:var(--radius-md);font-size:var(--font-size-base);box-sizing:border-box;transition:border-color 0.3s ease}
        input:focus,select:focus{outline:none;border-color:var(--primary-blue)}
        input.error,select.error{border-color:#ef4444;background-color:#fef2f2}
        input.valid,select.valid{border-color:#10b981}
        .error-message{color:#ef4444;font-size:0.875rem;margin-top:0.25rem;display:none}
        .error-message.show{display:block}
        .actions{display:flex;gap:var(--spacing-3);margin-top:var(--spacing-6)}
        .btn{display:inline-flex;align-items:center;justify-content:center;padding:var(--spacing-3) var(--spacing-6);border-radius:var(--radius-lg);font-weight:600;border:2px solid transparent;cursor:pointer;text-decoration:none}
        .btn-primary{background:var(--primary-blue);color:#fff}
        .btn-primary:hover{background:var(--primary-blue-dark)}
        .btn-secondary{background:#fff;color:var(--primary-blue);border-color:var(--primary-blue)}
        .btn-secondary:hover{background:var(--primary-blue);color:#fff}
        .helper{text-align:center;margin-top:var(--spacing-4);color:var(--gray-600)}
        @media(max-width:720px){.grid-2{grid-template-columns:1fr}}
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="logo">DOXI</div>
                <h2>Patient Registration</h2>
                <p class="subtitle">Create your patient account to access appointments and records</p>
            </div>
            <form id="patient-register-form" onsubmit="registerPatient(event)" autocomplete="off" novalidate>
                <div class="grid-2">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input id="firstName" name="firstName" required>
                        <div class="error-message" id="firstName-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input id="lastName" name="lastName" required>
                        <div class="error-message" id="lastName-error"></div>
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" required pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.com$" title="Email must end with .com">
                        <div class="error-message" id="email-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input id="phone" name="phone" required maxlength="10" pattern="^\d{10}$" title="Enter exactly 10 digits">
                        <div class="error-message" id="phone-error"></div>
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input id="dob" type="date" name="dob" required min="1900-01-01" max="<?php echo date('Y-m-d'); ?>">
                        <div class="error-message" id="dob-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select</option>
                            <option>Female</option>
                            <option>Male</option>
                            <option>Other</option>
                            <option>Prefer not to say</option>
                        </select>
                        <div class="error-message" id="gender-error"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input id="address" name="address">
                    <div class="error-message" id="address-error"></div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" minlength="6" required autocomplete="new-password" autocapitalize="none" autocorrect="off" spellcheck="false" inputmode="text" readonly onfocus="this.removeAttribute('readonly');">
                        <div class="error-message" id="password-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="confirm">Confirm Password</label>
                        <input id="confirm" type="password" name="confirm" minlength="6" required autocomplete="new-password" autocapitalize="none" autocorrect="off" spellcheck="false" inputmode="text" readonly onfocus="this.removeAttribute('readonly');">
                        <div class="error-message" id="confirm-error"></div>
                    </div>
                </div>
                <div class="actions">
                    <button class="btn btn-primary" type="submit">Create Patient Account</button>
                    <a class="btn btn-secondary" href="login.php" onclick="preselectRole(event,'patient')">Back to Login</a>
                </div>
                <div class="helper">Already have an account? <a href="login.php" onclick="preselectRole(event,'patient')">Login as Patient</a></div>
            </form>
        </div>
    </div>

    <script>
        function preselectRole(e, role){
            e.preventDefault();
            const url = new URL(location.origin + '/DOXI/login.php');
            url.searchParams.set('role', role);
            location.href = url.toString();
        }

        // Validation functions
        function validateName(name, fieldName) {
            if (!name || name.trim() === '') {
                return { valid: false, message: `${fieldName} is required` };
            }
            if (name.trim().length < 2) {
                return { valid: false, message: `${fieldName} must be at least 2 characters long` };
            }
            // Check if numbers are present
            if (/\d/.test(name.trim())) {
                return { valid: false, message: `${fieldName} cannot contain numbers` };
            }
            if (!/^[a-zA-Z\s'-]+$/.test(name.trim())) {
                return { valid: false, message: `${fieldName} can only contain letters, spaces, hyphens, and apostrophes` };
            }
            return { valid: true, message: '' };
        }
        
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[A-Za-z0-9.-]+\.com$/i;
            if (!email || email.trim() === '') {
                return { valid: false, message: 'Email is required' };
            }
            if (!emailRegex.test(email.trim())) {
                return { valid: false, message: 'Please enter a valid email address ending with .com' };
            }
            return { valid: true, message: '' };
        }
        
        function validatePhone(phone) {
            const digitsOnly = (phone || '').trim().replace(/\D/g, '');
            if (!/^\d{10}$/.test(digitsOnly)) {
                return { valid: false, message: 'Phone number must contain exactly 10 digits' };
            }
            return { valid: true, message: '' };
        }
        
        function validateAddress(address) {
            if (!address || address.trim() === '') {
                return { valid: true, message: '' }; // Address is optional
            }
            if (address.trim().length < 5) {
                return { valid: false, message: 'Address must be at least 5 characters long' };
            }
            if (address.trim().length > 200) {
                return { valid: false, message: 'Address must be less than 200 characters' };
            }
            // Address should contain at least letters and numbers (house numbers, street names)
            if (!/^[a-zA-Z0-9\s,\-\.#]+$/.test(address.trim())) {
                return { valid: false, message: 'Address can only contain letters, numbers, spaces, commas, hyphens, periods, and #' };
            }
            return { valid: true, message: '' };
        }
        
        function validateDateOfBirth(dob) {
            if (!dob || dob.trim() === '') {
                return { valid: false, message: 'Date of birth is required' };
            }
            const normalized = normalizeDateInput(dob);
            const birthDate = new Date(normalized);
            if (isNaN(birthDate.getTime())) {
                return { valid: false, message: 'Please enter a valid date (YYYY-MM-DD)' };
            }
            const today = new Date();
            const age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            if (birthDate > today) {
                return { valid: false, message: 'Date of birth cannot be in the future' };
            }
            if (age < 0 || age > 150) {
                return { valid: false, message: 'Please enter a valid date of birth' };
            }
            return { valid: true, message: '' };
        }
        
        function validateGender(gender) {
            if (!gender || gender.trim() === '') {
                return { valid: false, message: 'Please select a gender' };
            }
            return { valid: true, message: '' };
        }
        
        function validatePassword(password) {
            if (!password || password.trim() === '') {
                return { valid: false, message: 'Password is required' };
            }
            if (password.length < 6) {
                return { valid: false, message: 'Password must be at least 6 characters long' };
            }
            if (password.length > 50) {
                return { valid: false, message: 'Password must be less than 50 characters' };
            }
            return { valid: true, message: '' };
        }
        
        function validatePasswordMatch(password, confirm) {
            if (!confirm || confirm.trim() === '') {
                return { valid: false, message: 'Please confirm your password' };
            }
            if (password !== confirm) {
                return { valid: false, message: 'Passwords do not match' };
            }
            return { valid: true, message: '' };
        }

        function normalizeDateInput(value){
            if (!value) return '';
            const trimmed = value.trim();
            if (/^\d{4}-\d{2}-\d{2}$/.test(trimmed)){
                return trimmed;
            }
            const parts = trimmed.split(/[-\/]/);
            if (parts.length === 3){
                if (parts[0].length === 4){
                    const [y,m,d] = parts;
                    return `${y.padStart(4,'0')}-${m.padStart(2,'0')}-${d.padStart(2,'0')}`;
                }
                if (parts[2].length === 4){
                    const [d,m,y] = parts;
                    return `${y.padStart(4,'0')}-${m.padStart(2,'0')}-${d.padStart(2,'0')}`;
                }
            }
            return trimmed;
        }
 
        function showError(inputId, message) {
            const input = document.getElementById(inputId);
            const errorDiv = document.getElementById(inputId + '-error');
            if (input && errorDiv) {
                input.classList.add('error');
                input.classList.remove('valid');
                errorDiv.textContent = message;
                errorDiv.classList.add('show');
            }
        }
        
        function showValid(inputId) {
            const input = document.getElementById(inputId);
            const errorDiv = document.getElementById(inputId + '-error');
            if (input && errorDiv) {
                input.classList.remove('error');
                input.classList.add('valid');
                errorDiv.classList.remove('show');
            }
        }
        
        // Real-time validation
        document.addEventListener('DOMContentLoaded', function() {
            const fields = {
                firstName: { validator: (v) => validateName(v, 'First name') },
                lastName: { validator: (v) => validateName(v, 'Last name') },
                email: { validator: validateEmail },
                phone: { validator: validatePhone },
                dob: { validator: validateDateOfBirth },
                gender: { validator: validateGender },
                address: { validator: validateAddress },
                password: { validator: validatePassword },
                confirm: { validator: (v) => {
                    const password = document.getElementById('password').value;
                    return validatePasswordMatch(password, v);
                }}
            };
            
            // Prevent typing numbers in name fields
            ['firstName', 'lastName'].forEach(fieldId => {
                const input = document.getElementById(fieldId);
                if (input) {
                    input.addEventListener('input', function(e) {
                        // Remove numbers in real-time
                        const originalValue = this.value;
                        const withoutNumbers = originalValue.replace(/\d/g, '');
                        if (originalValue !== withoutNumbers) {
                            this.value = withoutNumbers;
                            showError(fieldId, 'Numbers are not allowed in name fields');
                            setTimeout(() => {
                                const result = validateName(withoutNumbers, fieldId === 'firstName' ? 'First name' : 'Last name');
                                if (result.valid) {
                                    showValid(fieldId);
                                }
                            }, 100);
                        }
                    });
                    input.addEventListener('keypress', function(e) {
                        // Prevent typing numbers
                        if (/\d/.test(e.key)) {
                            e.preventDefault();
                            showError(fieldId, 'Numbers are not allowed');
                        }
                    });
                }
            });
            
            // Prevent typing letters in phone field
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                const handlePhoneValidation = () => {
                    const digits = phoneInput.value.replace(/\D/g, '').slice(0, 10);
                    if (phoneInput.value !== digits) {
                        phoneInput.value = digits;
                    }
                    const result = validatePhone(digits);
                    if (!result.valid) {
                        showError('phone', result.message);
                    } else {
                        showValid('phone');
                    }
                };
                phoneInput.addEventListener('input', handlePhoneValidation);
                phoneInput.addEventListener('blur', handlePhoneValidation);
            }
            
            Object.keys(fields).forEach(fieldId => {
                const input = document.getElementById(fieldId);
                if (input) {
                    input.addEventListener('blur', function() {
                        const result = fields[fieldId].validator(this.value);
                        if (!result.valid) {
                            showError(fieldId, result.message);
                        } else {
                            showValid(fieldId);
                        }
                        // Re-validate confirm password when password changes
                        if (fieldId === 'password') {
                            const confirm = document.getElementById('confirm');
                            if (confirm && confirm.value) {
                                const confirmResult = validatePasswordMatch(this.value, confirm.value);
                                if (!confirmResult.valid) {
                                    showError('confirm', confirmResult.message);
                                } else {
                                    showValid('confirm');
                                }
                            }
                        }
                    });
                    input.addEventListener('input', function() {
                        if (this.classList.contains('error')) {
                            const result = fields[fieldId].validator(this.value);
                            if (result.valid) {
                                showValid(fieldId);
                            }
                        }
                        // Real-time password match validation
                        if (fieldId === 'password') {
                            const confirm = document.getElementById('confirm');
                            if (confirm && confirm.value) {
                                const confirmResult = validatePasswordMatch(this.value, confirm.value);
                                if (confirmResult.valid) {
                                    showValid('confirm');
                                } else {
                                    showError('confirm', confirmResult.message);
                                }
                            }
                        }
                    });
                    // Special handler for confirm password
                    if (fieldId === 'confirm') {
                        input.addEventListener('input', function() {
                            const password = document.getElementById('password').value;
                            if (password) {
                                const result = validatePasswordMatch(password, this.value);
                                if (!result.valid) {
                                    showError('confirm', result.message);
                                } else {
                                    showValid('confirm');
                                }
                            }
                        });
                    }
                }
            });
        });
        
        async function registerPatient(event){
            event.preventDefault();
            const form = event.target;
            const data = {};
            const formData = new FormData(form);
            formData.forEach((value, key) => {
                if (typeof value === 'string') {
                    data[key] = value.trim();
                } else {
                    data[key] = value;
                }
            });

            // Normalise date value so browsers that allow custom typing still submit
            if (data.dob){
                const normalizedDob = normalizeDateInput(data.dob);
                data.dob = normalizedDob;
                const dobInput = document.getElementById('dob');
                if (dobInput) dobInput.value = normalizedDob;
            }

            if (data.phone){
                data.phone = data.phone.replace(/\D/g, '').slice(0,10);
                const phoneInput = document.getElementById('phone');
                if (phoneInput) phoneInput.value = data.phone;
            }
             
             // Validate all fields
             const validations = {
                 firstName: validateName(data.firstName, 'First name'),
                 lastName: validateName(data.lastName, 'Last name'),
                 email: validateEmail(data.email),
                 phone: validatePhone(data.phone),
                 dob: validateDateOfBirth(data.dob),
                 gender: validateGender(data.gender),
                 address: validateAddress(data.address),
                 password: validatePassword(data.password),
                 confirm: validatePasswordMatch(data.password, data.confirm)
             };
             
             let isValid = true;
             Object.keys(validations).forEach(fieldId => {
                 const result = validations[fieldId];
                 if (!result.valid) {
                     showError(fieldId, result.message);
                     isValid = false;
                 } else {
                     showValid(fieldId);
                 }
             });
             
             if (!isValid) {
                 return;
             }
             
             // Show loading state
             const submitBtn = form.querySelector('button[type="submit"]');
             const originalText = submitBtn.textContent;
             submitBtn.textContent = 'Creating Account...';
             submitBtn.disabled = true;
             
             try {
                 const response = await fetch('api/register.php', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                     },
                     body: JSON.stringify({
                         role: 'patient',
                         email: data.email,
                         password: data.password,
                         confirmPassword: data.confirm,
                         firstName: data.firstName,
                         lastName: data.lastName,
                         phone: data.phone,
                         dateOfBirth: data.dob,
                         gender: data.gender,
                         address: data.address
                     })
                 });
                 
                 const responseText = await response.text();
                 let result = null;
                 try {
                     result = responseText ? JSON.parse(responseText) : null;
                 } catch(parseError) {
                     console.error('Failed to parse registration response:', parseError, responseText);
                 }

                 if (response.ok && result && result.success) {
                     alert('Patient registration successful! You can now login.');
                     const url = new URL('login.php', location.href);
                     url.searchParams.set('role','patient');
                     location.href = url.toString();
                 } else {
                     const message = result?.message || responseText || 'Registration failed. Please try again.';
                     alert('Error: ' + message);
                 }
             } catch (error) {
                 console.error('Registration error:', error);
                 alert('Registration failed. Please try again.');
             } finally {
                 // Reset button state
                 submitBtn.textContent = originalText;
                 submitBtn.disabled = false;
             }
         }
    </script>
    <script src="public/js/email-phone-validation.js"></script>
</body>
</html>


