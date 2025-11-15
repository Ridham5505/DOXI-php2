<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Doctor Registration</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .register-container{min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,var(--light-blue) 0%, var(--white) 100%);padding:var(--spacing-6)}
        .register-card{background:#fff;padding:var(--spacing-12);border-radius:var(--radius-2xl);box-shadow:var(--shadow-xl);width:100%;max-width:720px}
        .register-header{margin-bottom:var(--spacing-8);text-align:center}
        .register-header .logo{font-size:var(--font-size-3xl);font-weight:800;color:var(--primary-blue)}
        .register-header .subtitle{color:var(--gray-600)}
        .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:var(--spacing-4)}
        .grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:var(--spacing-4)}
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
        @media(max-width:900px){.grid-3{grid-template-columns:1fr}.grid-2{grid-template-columns:1fr}}
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="logo">DOXI</div>
                <h2>Doctor Registration</h2>
                <p class="subtitle">Create your doctor account to manage patients and records</p>
            </div>
            <form id="doctor-register-form" onsubmit="registerDoctor(event)">
                <div class="grid-3">
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
                    <div class="form-group">
                        <label for="specialty">Specialty</label>
                        <select id="specialty" name="specialty" required>
                            <option value="">Select</option>
                            <option>General Medicine</option>
                            <option>Cardiology</option>
                            <option>Pediatrics</option>
                            <option>Orthopedics</option>
                            <option>Neurology</option>
                            <option>Dermatology</option>
                        </select>
                        <div class="error-message" id="specialty-error"></div>
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label for="email">Work Email</label>
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
                        <label for="license">License Number</label>
                        <input
                            id="license"
                            name="license"
                            required
                            maxlength="20"
                            pattern="^[A-Z]{2}\/(19|20)\d{2}\/\d{5,6}$"
                            placeholder="e.g., TN/2020/123456"
                            title="Format: SS/YYYY/12345 (state code, year, serial)"
                        >
                        <div class="error-message" id="license-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="experience">Years of Experience</label>
                        <input id="experience" type="number" min="0" name="experience" required>
                        <div class="error-message" id="experience-error"></div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Practice Address</label>
                    <input id="address" name="address">
                    <div class="error-message" id="address-error"></div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" minlength="6" required>
                        <div class="error-message" id="password-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="confirm">Confirm Password</label>
                        <input id="confirm" type="password" name="confirm" minlength="6" required>
                        <div class="error-message" id="confirm-error"></div>
                    </div>
                </div>
                <div class="actions">
                    <button class="btn btn-primary" type="submit">Create Doctor Account</button>
                    <a class="btn btn-secondary" href="login.php" onclick="preselectRole(event,'doctor')">Back to Login</a>
                </div>
                <div class="helper">Already have an account? <a href="login.php" onclick="preselectRole(event,'doctor')">Login as Doctor</a></div>
                <div id="approvalStatusNotice" style="margin-top:16px; display:none; padding:12px; border-radius:12px; background:#fef3c7; color:#92400e; font-weight:600; text-align:center;"></div>
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

        // Validation functions (same as patient registration with additional validations)
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
            if (!phone || phone.trim() === '') {
                return { valid: true, message: '' }; // Phone is optional
            }
            const digitsOnly = phone.trim().replace(/\D/g, '');
            if (!/^\d{10}$/.test(digitsOnly)) {
                return { valid: false, message: 'Phone number must contain exactly 10 digits' };
            }
            return { valid: true, message: '' };
        }
        
        function validateLicense(license) {
            if (!license || license.trim() === '') {
                return { valid: false, message: 'License number is required' };
            }
            const formatted = license.trim().toUpperCase();
            const licenseRegex = /^[A-Z]{2}\/(19|20)\d{2}\/\d{5,6}$/;
            if (!licenseRegex.test(formatted)) {
                return {
                    valid: false,
                    message: 'Format must be SS/YYYY/12345 (e.g., TN/2020/123456)'
                };
            }
            return { valid: true, message: '', normalized: formatted };
        }
        
        function validateExperience(experience) {
            if (!experience || experience.trim() === '') {
                return { valid: false, message: 'Years of experience is required' };
            }
            // Check if letters or special characters are present
            if (/[a-zA-Z@#$%^&*!\.]/.test(experience.trim())) {
                return { valid: false, message: 'Experience must be a whole number only (no letters or decimals)' };
            }
            const exp = parseInt(experience, 10);
            if (isNaN(exp)) {
                return { valid: false, message: 'Please enter a valid number' };
            }
            if (exp < 0) {
                return { valid: false, message: 'Experience cannot be negative' };
            }
            if (exp > 60) {
                return { valid: false, message: 'Please enter a realistic years of experience (0-60)' };
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
        
        function validateSpecialty(specialty) {
            if (!specialty || specialty.trim() === '') {
                return { valid: false, message: 'Please select a specialty' };
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
                license: { validator: validateLicense },
                experience: { validator: validateExperience },
                specialty: { validator: validateSpecialty },
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

            const licenseInput = document.getElementById('license');
            if (licenseInput) {
                licenseInput.addEventListener('input', () => {
                    const cleaned = licenseInput.value
                        .toUpperCase()
                        .replace(/[^A-Z0-9\/]/g, '')
                        .slice(0, 20);
                    if (licenseInput.value !== cleaned) {
                        licenseInput.value = cleaned;
                    }
                });
                licenseInput.addEventListener('blur', () => {
                    const result = validateLicense(licenseInput.value);
                    if (!result.valid) {
                        showError('license', result.message);
                    } else {
                        showValid('license');
                    }
                });
            }
            
            // Prevent typing letters in experience field
            const experienceInput = document.getElementById('experience');
            if (experienceInput) {
                experienceInput.addEventListener('input', function(e) {
                    // Remove letters and special characters in real-time
                    const originalValue = this.value;
                    const cleaned = originalValue.replace(/[a-zA-Z@#$%^&*!\.]/g, '');
                    if (originalValue !== cleaned) {
                        this.value = cleaned;
                        showError('experience', 'Experience must be a whole number only (no letters or decimals)');
                        setTimeout(() => {
                            const result = validateExperience(cleaned);
                            if (result.valid) {
                                showValid('experience');
                            }
                        }, 100);
                    }
                });
                experienceInput.addEventListener('keypress', function(e) {
                    // Only allow numbers
                    if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                        e.preventDefault();
                        showError('experience', 'Only numbers allowed');
                    }
                });
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
        
        let approvalWatcherInterval = null;
        const APPROVAL_STORAGE_KEY = 'pendingDoctorEmail';

        function updateApprovalNotice(email, state){
            const notice = document.getElementById('approvalStatusNotice');
            if (!notice) return;
            if (!email){
                notice.style.display = 'none';
                notice.textContent = '';
                return;
            }
            notice.style.display = 'block';
            if (state === 'approved'){
                notice.textContent = `Doctor account for ${email} has been approved. Redirecting to login...`;
                notice.style.background = '#dcfce7';
                notice.style.color = '#166534';
            } else if (state === 'rejected'){
                notice.textContent = `Registration for ${email} was rejected. Please contact support.`;
                notice.style.background = '#fee2e2';
                notice.style.color = '#b91c1c';
            } else {
                notice.textContent = `Registration received for ${email}. Please wait while the admin approves your account.`;
                notice.style.background = '#fef3c7';
                notice.style.color = '#92400e';
            }
        }

        function stopDoctorApprovalWatcher(){
            if (approvalWatcherInterval){
                clearInterval(approvalWatcherInterval);
                approvalWatcherInterval = null;
            }
        }

        async function checkDoctorApprovalStatus(email){
            if (!email) return;
            try{
                const res = await fetch(`api/users.php?email=${encodeURIComponent(email)}`);
                const json = await res.json();
                if (!json.success || !json.data) return;
                const status = (json.data.doctor_status || 'approved').toLowerCase();
                if (status === 'approved'){
                    stopDoctorApprovalWatcher();
                    updateApprovalNotice(email, 'approved');
                    localStorage.removeItem(APPROVAL_STORAGE_KEY);
                    alert('Your doctor account has been approved! You can now log in.');
                    const url = new URL('login.php', location.href);
                    url.searchParams.set('role','doctor');
                    location.href = url.toString();
                } else if (status === 'rejected'){
                    stopDoctorApprovalWatcher();
                    updateApprovalNotice(email, 'rejected');
                    localStorage.removeItem(APPROVAL_STORAGE_KEY);
                    alert('Your registration was rejected. Please contact support.');
                } else {
                    updateApprovalNotice(email, 'pending');
                }
            } catch(e){
                console.error('Approval status check failed', e);
            }
        }

        function startDoctorApprovalWatcher(email){
            if (!email) return;
            localStorage.setItem(APPROVAL_STORAGE_KEY, email);
            updateApprovalNotice(email, 'pending');
            stopDoctorApprovalWatcher();
            checkDoctorApprovalStatus(email);
            approvalWatcherInterval = setInterval(() => checkDoctorApprovalStatus(email), 10000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const pendingEmail = localStorage.getItem(APPROVAL_STORAGE_KEY);
            if (pendingEmail){
                startDoctorApprovalWatcher(pendingEmail);
            }
        });

        async function registerDoctor(event){
            event.preventDefault();
            const form = event.target;
            const data = Object.fromEntries(new FormData(form));
            ['firstName','lastName','email','phone','license','experience','specialty','address','password','confirm'].forEach(field=>{
                if (typeof data[field] === 'string'){
                    data[field] = data[field].trim();
                }
            });
            if (data.license){
                data.license = data.license.toUpperCase();
                const licenseInput = document.getElementById('license');
                if (licenseInput) licenseInput.value = data.license;
            }
            
            // Validate all fields
            const validations = {
                firstName: validateName(data.firstName, 'First name'),
                lastName: validateName(data.lastName, 'Last name'),
                email: validateEmail(data.email),
                phone: validatePhone(data.phone),
                license: validateLicense(data.license),
                experience: validateExperience(data.experience),
                specialty: validateSpecialty(data.specialty),
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
                        role: 'doctor',
                        email: data.email,
                        password: data.password,
                        confirmPassword: data.confirm,
                        firstName: data.firstName,
                        lastName: data.lastName,
                        specialty: data.specialty,
                        phone: data.phone,
                        licenseNumber: data.license,
                        yearsExperience: parseInt(data.experience || '0', 10),
                        practiceAddress: data.address
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Registration submitted! Please wait for admin approval before logging in.');
                    startDoctorApprovalWatcher(data.email);
                } else {
                    alert('Error: ' + result.message);
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



