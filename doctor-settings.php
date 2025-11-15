
        
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Settings & Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        // Apply theme early before styles load
        (function(){
            try{
                const savedTheme = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-theme', savedTheme);
            }catch(_e){}
        })();
    </script>
    <link rel="stylesheet" href="styles.css">
    <style>
        :root {
            --blue: #2563eb;
            --blue-600: #1d4ed8;
            --blue-50: #eff6ff;
            --primary-blue: #2563eb;
            --primary-blue-dark: #1d4ed8;
            --primary-blue-light: #3b82f6;
            --secondary-blue: #1e40af;
            --accent-blue: #60a5fa;
            --light-blue: #dbeafe;
            --white: #ffffff;
            --gray-50:#f9fafb;
            --gray-100:#f3f4f6;
            --gray-200:#e5e7eb;
            --gray-300:#d1d5db;
            --gray-400:#9ca3af;
            --gray-500:#6b7280;
            --gray-600:#4b5563;
            --gray-700:#374151;
            --gray-800:#1f2937;
            --gray-900:#111827;
            --radius: 12px;
        }
        /* Dark theme overrides */
        [data-theme="dark"]{
            --primary-blue: #3b82f6;
            --primary-blue-dark: #2563eb;
            --primary-blue-light: #60a5fa;
            --secondary-blue: #60a5fa;
            --accent-blue: #93c5fd;
            --light-blue: #1e3a8a;
            --white: #0f172a;
            --gray-50:#0b1220;
            --gray-100:#111827;
            --gray-200:#1f2937;
            --gray-300:#374151;
            --gray-400:#6b7280;
            --gray-500:#9ca3af;
            --gray-600:#d1d5db;
            --gray-700:#e5e7eb;
            --gray-800:#f3f4f6;
            --gray-900:#ffffff;
            --blue-50:#0b2a4e;
            --blue-600: #3b82f6;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -2px rgba(0, 0, 0, 0.3);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 10px 10px -5px rgba(0, 0, 0, 0.3);
        }
        html, body { margin:0; height:100%; background: var(--gray-50); color: var(--gray-900); font-family: Inter, system-ui, Arial, sans-serif; transition: background 0.3s, color 0.3s; }
        body { background: var(--gray-50); }
        .page { max-width: 900px; margin: 40px auto; padding: 0 var(--spacing-6); }
        .header { display:flex; align-items:center; justify-content: space-between; margin-bottom: var(--spacing-6); }
        .header-right { display:flex; align-items:center; gap: var(--spacing-4); }
        .logo { font-size: var(--font-size-2xl); font-weight: 800; color: var(--primary-blue); }
        .logo img{display:block;height:36px;width:auto;}
        .tagline { color: var(--gray-600); font-size: var(--font-size-sm); font-weight: 500; }
        .theme-toggle { padding: 8px 14px; border-radius: 8px; border: 1px solid var(--gray-200); background: var(--white); color: var(--gray-700); cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: background 0.3s, border-color 0.3s, color 0.3s; }
        .theme-toggle:hover { background: var(--gray-100); }
        .card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius-xl); box-shadow: var(--shadow-md); padding: var(--spacing-6); margin-bottom: var(--spacing-6); transition: background 0.3s, border-color 0.3s; }
        .section-title { font-size: var(--font-size-xl); font-weight: 700; color: var(--gray-900); margin-bottom: var(--spacing-4); padding-bottom: var(--spacing-3); border-bottom: 2px solid var(--primary-blue); transition: color 0.3s; }
        .form-grid { display:grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-4); }
        @media(max-width: 700px){ .form-grid{ grid-template-columns: 1fr; } }
        .form-group { margin-bottom: var(--spacing-4); }
        .form-group.full-width { grid-column: 1 / -1; }
        label { font-weight:600; color: var(--gray-700); margin-bottom: var(--spacing-2); display:block; }
        input, select, textarea { width:100%; padding: var(--spacing-3) var(--spacing-4); border: 2px solid var(--gray-200); border-radius: var(--radius-md); font-family: var(--font-family); background: var(--white); color: var(--gray-900); transition: background 0.3s, border-color 0.3s; }
        input:focus, select:focus, textarea:focus { outline:none; border-color: var(--primary-blue); }
        input:disabled, select:disabled, textarea:disabled { background: var(--gray-50); cursor: default; }
        textarea { min-height: 100px; resize: vertical; }
        .error { color: #dc2626; font-size: var(--font-size-sm); margin-top: 4px; }
        .invalid { border-color: #dc2626 !important; }
        .btn-row { display:flex; gap: var(--spacing-3); justify-content: flex-end; margin-top: var(--spacing-4); }
        .btn { padding: var(--spacing-3) var(--spacing-6); border-radius: var(--radius-md); border: none; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn-primary { background: var(--primary-blue); color: var(--white); }
        .btn-primary:hover { background: var(--primary-blue-dark); }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }
        .btn-outline { background: var(--white); color: var(--primary-blue); border: 2px solid var(--primary-blue); }
        .btn-outline:hover { background: var(--primary-blue); color: var(--white); }
        .btn-secondary { background: var(--gray-100); color: var(--gray-700); border: 2px solid var(--gray-200); }
        .btn-secondary:hover { background: var(--gray-200); }
        .back { text-decoration:none; color: var(--gray-600); transition: color 0.3s; }
        .back:hover { color: var(--gray-900); }
        .view-mode .form-group input, .view-mode .form-group select, .view-mode .form-group textarea { background: var(--gray-50); cursor: default; }
        .info-text { color: var(--gray-600); font-size: var(--font-size-sm); margin-top: 4px; }
        /* Password form layout - improved format */
        .password-form-grid { display:grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-4); }
        .password-form-grid .form-group:nth-child(1) { grid-column: 1; }
        .password-form-grid .form-group:nth-child(2) { grid-column: 2; }
        .password-form-grid .form-group:nth-child(3) { grid-column: 1; }
        @media(max-width: 700px){ 
            .password-form-grid { grid-template-columns: 1fr; }
            .password-form-grid .form-group { grid-column: 1 !important; }
        }
        /* Alert Messages */
        .alert { padding: 1rem; border-radius: var(--radius-md); margin-bottom: var(--spacing-4); font-size: var(--font-size-sm); }
        .alert-success { background: #D1FAE5; color: #065F46; border: 1px solid #86EFAC; }
        .alert-error { background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; }
        .alert-info { background: #DBEAFE; color: #1E40AF; border: 1px solid #93C5FD; }
        /* Password requirements */
        .password-requirements { background: var(--blue-50); border: 1px solid var(--light-blue); border-radius: var(--radius-md); padding: var(--spacing-4); margin-top: var(--spacing-2); font-size: var(--font-size-sm); }
        .password-requirements-title { font-weight: 600; color: var(--primary-blue); margin-bottom: var(--spacing-2); }
        .password-requirements ul { margin: 0; padding-left: 1.25rem; color: var(--gray-700); }
        .password-requirements li { margin-bottom: 0.25rem; }
    </style>
    <link rel="icon" type="image/svg+xml" href="public/assets/doxi-icon.svg?v=1">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="page">
        <div class="header">
            <div>
                <div class="logo"><img src="public/assets/doxi-logo.svg?v=4" alt="DOXI" style="height:52px;width:auto;"></div>
                <div class="tagline">Settings & Profile</div>
            </div>
            <div class="header-right">
                <button class="theme-toggle" id="theme-toggle" onclick="toggleTheme()">
                    <span id="theme-icon">🌙</span>
                    <span id="theme-text">Dark</span>
                </button>
                <a class="back" href="doctor-dashboard.php">← Back to Dashboard</a>
            </div>
        </div>

        <!-- Alert Messages Container -->
        <div id="alert-container"></div>

        <!-- Personal Information -->
        <div class="card">
            <h2 class="section-title">Personal Information</h2>
            
            <!-- Profile Completion Warning -->
            <div id="profile-incomplete-warning" class="alert alert-info" style="display: none;">
                <strong>⚠️ Profile Incomplete:</strong> Please complete all required fields before accessing other modules.
            </div>
            
            <form id="personal-info-form" onsubmit="handleProfileUpdate(event)">
                <input type="hidden" id="user-id">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="first-name" class="required">First Name</label>
                        <input type="text" id="first-name" required />
                        <div id="err-first-name" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="last-name" class="required">Last Name</label>
                        <input type="text" id="last-name" required />
                        <div id="err-last-name" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="required">Work Email</label>
                        <input type="email" id="email" required />
                        <div id="err-email" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" />
                        <div id="err-phone" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="date-of-birth">Date of Birth</label>
                        <input type="date" id="date-of-birth" />
                        <div id="err-date-of-birth" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="gender" class="required">Gender</label>
                        <select id="gender" required>
                            <option value="">Select Gender...</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                            <option value="Prefer not to say">Prefer not to say</option>
                        </select>
                        <div id="err-gender" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="specialty" class="required">Specialty</label>
                        <input type="text" id="specialty" required placeholder="e.g., Cardiologist, Dermatologist" />
                        <div id="err-specialty" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="license-number" class="required">License Number</label>
                        <input type="text" id="license-number" required />
                        <div id="err-license-number" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="years-experience" class="required">Years of Experience</label>
                        <input type="number" id="years-experience" required min="0" max="50" />
                        <div id="err-years-experience" class="error"></div>
                    </div>
                    <div class="form-group full-width">
                        <label for="practice-address">Practice Address</label>
                        <textarea id="practice-address" rows="3" placeholder="Enter your clinic or hospital address"></textarea>
                        <div id="err-practice-address" class="error"></div>
                    </div>
                </div>
                
                <div class="btn-row">
                    <button type="button" class="btn btn-secondary" onclick="resetPersonalForm()">Reset</button>
                    <button type="submit" class="btn btn-primary" id="save-changes-btn">
                        <span id="save-btn-text">Save Changes</span>
                        <span id="save-btn-spinner" style="display: none;">⏳ Saving...</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="card">
            <h2 class="section-title">Change Password</h2>
            <form id="change-password-form" onsubmit="handlePasswordChange(event)">
                <div class="password-form-grid">
                    <div class="form-group">
                        <label for="current-password" class="required">Current Password</label>
                        <input type="password" id="current-password" required />
                        <div id="err-current-password" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="new-password" class="required">New Password</label>
                        <input type="password" id="new-password" required />
                        <div id="err-new-password" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password" class="required">Confirm New Password</label>
                        <input type="password" id="confirm-password" required />
                        <div id="err-confirm-password" class="error"></div>
                    </div>
                </div>
                <div class="password-requirements">
                    <div class="password-requirements-title">Password Requirements:</div>
                    <ul>
                        <li>Minimum 8 characters long</li>
                        <li>At least one uppercase letter (A-Z)</li>
                        <li>At least one number (0-9)</li>
                        <li>At least one special character (!@#$%^&*...)</li>
                    </ul>
                </div>
                <div class="btn-row">
                    <button type="button" class="btn btn-secondary" onclick="resetPasswordForm()">Reset</button>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>

        <!-- Account Control -->
        <div class="card" style="background:#fef2f2;border:1px solid #fecaca;">
            <h2 class="section-title" style="border-color:#f9b4b4;color:#b91c1c;">Account Control</h2>
            <p style="color:#b91c1c;margin-bottom:var(--spacing-4);">
                Deleting your account will permanently remove your profile, appointments, and records. This action cannot be undone.
            </p>
            <div class="btn-row" style="justify-content:flex-start;">
                <button type="button"
                        class="btn"
                        style="background:#ef4444;color:#fff;border:2px solid #ef4444;"
                        onclick="confirmDoctorAccountDeletion()">
                    Delete My Account
                </button>
            </div>
        </div>
    </div>

    <script>
        // ============================================
        // DOCTOR SETTINGS / PROFILE JAVASCRIPT
        // Full profile management and password change functionality
        // ============================================

        // Global variables
        let doctorId = null;
        let doctorData = null;

        /**
         * Authentication Check
         * Verifies if doctor is logged in, redirects to login if not
         */
        function checkAuthentication() {
            const isLoggedIn = sessionStorage.getItem('isLoggedIn');
            const userRole = sessionStorage.getItem('userRole');
            const userId = sessionStorage.getItem('userId');
            
            if (isLoggedIn !== 'true' || userRole !== 'doctor') {
                sessionStorage.clear();
                window.location.href = 'login.php';
                return false;
            }
            
            doctorId = userId ? parseInt(userId) : null;
            
            if (!doctorId) {
                const email = sessionStorage.getItem('userEmail');
                if (email) {
                    loadDoctorIdFromEmail(email);
                } else {
                    sessionStorage.clear();
                    window.location.href = 'login.php';
                    return false;
                }
            }
            
            return true;
        }

        /**
         * Load Doctor ID from Email
         * Fetches doctor information using email from API
         */
        async function loadDoctorIdFromEmail(email) {
            try {
                const response = await fetch(`api/users.php?search=${encodeURIComponent(email)}&role=doctor&page=1&limit=1`);
                const result = await response.json();
                
                if (result.success && result.data && result.data.length > 0) {
                    doctorId = parseInt(result.data[0].id);
                    sessionStorage.setItem('userId', doctorId);
                    await loadDoctorProfile();
                } else {
                    console.error('Doctor not found in database');
                    sessionStorage.clear();
                    window.location.href = 'login.php';
                }
            } catch (error) {
                console.error('Error loading doctor ID:', error);
                sessionStorage.clear();
                window.location.href = 'login.php';
            }
        }

        /**
         * Load Doctor Profile
         * Fetches complete doctor profile data from API by ID
         */
        async function loadDoctorProfile() {
            if (!doctorId) {
                showAlert('Doctor ID not available', 'error');
                return;
            }

            try {
                const response = await fetch(`api/users.php?id=${doctorId}`);
                const result = await response.json();
                
                if (result.success && result.data) {
                    doctorData = result.data;
                    populateForms();
                } else {
                    showAlert('Profile data not found. Please complete your profile.', 'info');
                }
            } catch (error) {
                console.error('Error loading profile:', error);
                showAlert('Error loading profile. Please try again.', 'error');
            }
        }

        /**
         * Populate Forms
         * Fills all form fields with current doctor data
         */
        function populateForms() {
            if (!doctorData) return;

            // Personal Information (including professional fields)
            document.getElementById('first-name').value = doctorData.first_name || '';
            document.getElementById('last-name').value = doctorData.last_name || '';
            document.getElementById('email').value = doctorData.email || '';
            document.getElementById('phone').value = doctorData.phone || '';
            document.getElementById('date-of-birth').value = doctorData.date_of_birth || '';
            document.getElementById('gender').value = doctorData.gender || '';

            // Professional Information
            document.getElementById('specialty').value = doctorData.specialty || '';
            document.getElementById('license-number').value = doctorData.license_number || '';
            document.getElementById('years-experience').value = doctorData.years_experience || '';
            document.getElementById('practice-address').value = doctorData.practice_address || '';

            // Check profile completion and show warning if incomplete
            checkProfileCompletion();
            
            // Enable/disable save button based on required fields
            validateRequiredFields();
        }

        /**
         * Handle Profile Update
         * Saves profile changes to database
         */
        async function handleProfileUpdate(event) {
            event.preventDefault();

            if (!doctorId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Doctor ID not available. Please refresh the page.'
                });
                return;
            }

            // Validate required fields
            const gender = document.getElementById('gender').value;

            if (!gender) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select your gender.'
                });
                return;
            }

            // Show loading spinner
            const saveBtn = document.getElementById('save-changes-btn');
            const saveBtnText = document.getElementById('save-btn-text');
            const saveBtnSpinner = document.getElementById('save-btn-spinner');
            saveBtn.disabled = true;
            saveBtnText.style.display = 'none';
            saveBtnSpinner.style.display = 'inline';

            // Prepare update data
            const updateData = {
                id: doctorId,
                first_name: document.getElementById('first-name').value.trim(),
                last_name: document.getElementById('last-name').value.trim(),
                email: document.getElementById('email').value.trim(),
                phone: document.getElementById('phone').value.trim() || null,
                date_of_birth: document.getElementById('date-of-birth').value || null,
                gender: gender,
                specialty: document.getElementById('specialty').value.trim(),
                license_number: document.getElementById('license-number').value.trim(),
                years_experience: document.getElementById('years-experience').value ? parseInt(document.getElementById('years-experience').value) : null,
                practice_address: document.getElementById('practice-address').value.trim() || null
            };

            const emailPattern = /^[^\s@]+@[A-Za-z0-9.-]+\.com$/i;
            if (!emailPattern.test(updateData.email)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Email',
                    text: 'Please enter a valid email address ending with .com.'
                });
                return;
            }

            if (updateData.phone) {
                const digitsOnly = updateData.phone.replace(/\D/g, '');
                if (digitsOnly.length !== 10) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Phone',
                        text: 'Phone number must contain exactly 10 digits.'
                    });
                    return;
                }
                updateData.phone = digitsOnly;
            }

            try {
                const response = await fetch('api/users.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(updateData)
                });

                const result = await response.json();
                
                // Reset button state
                saveBtn.disabled = false;
                saveBtnText.style.display = 'inline';
                saveBtnSpinner.style.display = 'none';
                
                if (result.success) {
                    // Reload profile to get updated data from database
                    await loadDoctorProfile();
                    
                    // Verify profile completion with freshly loaded data
                    const genderValue = document.getElementById('gender').value;
                    const specialtyValue = document.getElementById('specialty').value.trim();
                    const licenseValue = document.getElementById('license-number').value.trim();
                    const experienceValue = document.getElementById('years-experience').value;
                    const firstNameValue = document.getElementById('first-name').value.trim();
                    const lastNameValue = document.getElementById('last-name').value.trim();
                    const emailValue = document.getElementById('email').value.trim();
                    
                    // Check if all required fields are complete
                    const isComplete = firstNameValue && lastNameValue && emailValue && 
                                     genderValue && specialtyValue && licenseValue && 
                                     experienceValue && experienceValue > 0;
                    
                    if (isComplete) {
                        // Profile is complete - set flags to prevent future checks from blocking
                        sessionStorage.setItem('profileComplete', 'true');
                        localStorage.setItem('doctorProfileCompleted', 'true'); // Persistent flag
                        
                        // Show success message and redirect to dashboard
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Profile updated successfully!',
                            timer: 2000,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        }).then(() => {
                            // Redirect to dashboard after success message
                            window.location.href = 'doctor-dashboard.php';
                        });
                    } else {
                        // Profile updated but still incomplete
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Profile updated successfully! Please complete all required fields.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update profile: ' + (result.message || 'Unknown error')
                    });
                }
            } catch (error) {
                console.error('Error updating profile:', error);
                
                // Reset button state
                saveBtn.disabled = false;
                saveBtnText.style.display = 'inline';
                saveBtnSpinner.style.display = 'none';
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error updating profile. Please try again.'
                });
            }
        }

        /**
         * Handle Password Change
         * Validates and changes password, then logs out user
         */
        async function handlePasswordChange(event) {
            event.preventDefault();

            if (!doctorId) {
                showAlert('Doctor ID not available. Please refresh the page.', 'error');
                return;
            }

            const currentPassword = document.getElementById('current-password').value;
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            // Client-side validation
            if (newPassword !== confirmPassword) {
                showAlert('New password and confirm password do not match.', 'error');
                return;
            }

            if (newPassword.length < 8) {
                showAlert('Password must be at least 8 characters long.', 'error');
                return;
            }

            if (!/[A-Z]/.test(newPassword)) {
                showAlert('Password must contain at least one uppercase letter.', 'error');
                return;
            }

            if (!/[0-9]/.test(newPassword)) {
                showAlert('Password must contain at least one number.', 'error');
                return;
            }

            if (!/[!@#$%^&*(),.?":{}|<>]/.test(newPassword)) {
                showAlert('Password must contain at least one special character.', 'error');
                return;
            }

            try {
                const response = await fetch('api/change-password.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        user_id: doctorId,
                        current_password: currentPassword,
                        new_password: newPassword,
                        confirm_password: confirmPassword
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    showAlert(result.message || 'Password changed successfully. Please log in again.', 'success');
                    document.getElementById('change-password-form').reset();
                    sessionStorage.clear();
                    window.location.href = 'login.php';
                } else {
                    showAlert('Failed to change password: ' + (result.message || 'Unknown error'), 'error');
                }
            } catch (error) {
                console.error('Error changing password:', error);
                showAlert('Error changing password. Please try again.', 'error');
            }
        }

        /**
         * Check Profile Completion
         * Checks if all required profile fields are filled
         */
        function checkProfileCompletion() {
            if (!doctorData) return false;

            const requiredFields = {
                'first_name': doctorData.first_name,
                'last_name': doctorData.last_name,
                'email': doctorData.email,
                'gender': doctorData.gender,
                'specialty': doctorData.specialty,
                'license_number': doctorData.license_number,
                'years_experience': doctorData.years_experience
            };

            const incompleteFields = Object.keys(requiredFields).filter(key => {
                const value = requiredFields[key];
                return !value || (typeof value === 'string' && value.trim() === '');
            });

            if (incompleteFields.length > 0) {
                const warningDiv = document.getElementById('profile-incomplete-warning');
                if (warningDiv) {
                    warningDiv.style.display = 'block';
                }
                return false;
            } else {
                const warningDiv = document.getElementById('profile-incomplete-warning');
                if (warningDiv) {
                    warningDiv.style.display = 'none';
                }
                return true;
            }
        }

        /**
         * Validate Required Fields
         * Enables/disables save button based on required fields
         */
        function validateRequiredFields() {
            const saveBtn = document.getElementById('save-changes-btn');
            const gender = document.getElementById('gender').value;

            // Enable/disable save button
            if (!gender) {
                saveBtn.disabled = true;
                saveBtn.style.opacity = '0.6';
                saveBtn.style.cursor = 'not-allowed';
            } else {
                saveBtn.disabled = false;
                saveBtn.style.opacity = '1';
                saveBtn.style.cursor = 'pointer';
            }
        }

        /**
         * Reset Personal Form
         * Resets personal information form to original values
         */
        function resetPersonalForm() {
            if (doctorData) {
                populateForms();
            }
        }

        /**
         * Reset Password Form
         * Clears password change form
         */
        function resetPasswordForm() {
            document.getElementById('change-password-form').reset();
        }

        /**
         * Show Alert
         * Displays alert message to user
         */
        function showAlert(message, type = 'info') {
            const alertContainer = document.getElementById('alert-container');
            const alertTypes = {
                success: 'alert-success',
                error: 'alert-error',
                info: 'alert-info'
            };

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert ${alertTypes[type] || alertTypes.info}`;
            alertDiv.textContent = message;
            
            alertContainer.innerHTML = '';
            alertContainer.appendChild(alertDiv);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        /**
         * Theme Toggle Functionality
         * Handles dark/light mode switching
         */
        function applyTheme(theme){
            const t = (theme === 'dark') ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', t);
            try{ localStorage.setItem('theme', t); }catch(_e){}
            updateThemeToggle(t);
        }

        function updateThemeToggle(theme){
            const icon = document.getElementById('theme-icon');
            const text = document.getElementById('theme-text');
            if (icon && text) {
                if (theme === 'dark'){
                    icon.textContent = '☀️';
                    text.textContent = 'Light';
                } else {
                    icon.textContent = '🌙';
                    text.textContent = 'Dark';
                }
            }
        }

        function toggleTheme(){
            const current = document.documentElement.getAttribute('data-theme') || 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            applyTheme(next);
        }

        /**
         * Initialize Settings Module
         * Sets up authentication and loads profile data
         */
        async function initializeSettings() {
            // Theme from localStorage early
            try{
                const savedTheme = localStorage.getItem('theme');
                if (savedTheme){ 
                    document.documentElement.setAttribute('data-theme', savedTheme);
                    updateThemeToggle(savedTheme);
                } else {
                    updateThemeToggle('light');
                }
            }catch(_e){}

            if (!checkAuthentication()) {
                return;
            }

            const email = sessionStorage.getItem('userEmail');
            if (email && !doctorId) {
                await loadDoctorIdFromEmail(email);
            } else if (doctorId) {
                await loadDoctorProfile();
            }
        }

        // Add event listeners for real-time validation
        document.addEventListener('DOMContentLoaded', function() {
            initializeSettings();
            
            // Add input listeners for real-time validation
            const genderSelect = document.getElementById('gender');
            
            if (genderSelect) {
                genderSelect.addEventListener('change', validateRequiredFields);
            }
        });

        function confirmDoctorAccountDeletion(){
            if (!doctorId){
                showAlert('Doctor ID not available. Please refresh and try again.', 'error');
                return;
            }
            Swal.fire({
                title: 'Delete Account?',
                text: 'This will permanently remove your doctor profile, appointments, and related records. This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then(async (result) => {
                if (result.isConfirmed){
                    const success = await deleteDoctorAccount();
                    if (success){
                        Swal.fire({
                            icon: 'success',
                            title: 'Account deleted',
                            text: 'Your account has been removed. Redirecting...',
                            timer: 2500,
                            showConfirmButton: false
                        });
                        sessionStorage.clear();
                        setTimeout(()=>{ window.location.href = 'index.php'; }, 2000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Deletion failed',
                            text: 'We could not delete your account. Please try again later.'
                        });
                    }
                }
            });
        }

        async function deleteDoctorAccount(){
            try{
                const res = await fetch('api/users.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: doctorId })
                });
                const json = await res.json().catch(()=>null);
                return json && json.success;
            }catch(error){
                console.error('Account deletion error', error);
                return false;
            }
        }
    </script>
    <script src="public/js/email-phone-validation.js"></script>
</body>
</html>

