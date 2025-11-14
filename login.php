<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Login Portal</title>
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
    <link rel="icon" type="image/svg+xml" href="public/assets/doxi-icon.svg?v=1">
    <style>
        /* Dark theme overrides */
        [data-theme="dark"] {
            --white: #0f172a;
            --gray-50: #0b1220;
            --gray-100: #111827;
            --gray-200: #1f2937;
            --gray-300: #374151;
            --gray-400: #6b7280;
            --gray-500: #9ca3af;
            --gray-600: #d1d5db;
            --gray-700: #e5e7eb;
            --gray-800: #f3f4f6;
            --gray-900: #ffffff;
        }
        
        [data-theme="dark"] .login-container {
            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
        }
        
        [data-theme="dark"] .login-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
        }
        
        [data-theme="dark"] .role-btn {
            background: var(--gray-100);
            border-color: var(--gray-200);
            color: var(--gray-900);
        }
        
        [data-theme="dark"] .role-btn:hover,
        [data-theme="dark"] .role-btn.active {
            background: var(--gray-200);
            border-color: var(--primary-blue);
        }
        
        [data-theme="dark"] .form-group input {
            background: var(--gray-100);
            border-color: var(--gray-200);
            color: var(--gray-900);
        }
        
        [data-theme="dark"] .form-group label {
            color: var(--gray-800);
        }
        
        [data-theme="dark"] .admin-note {
            background: var(--gray-200);
            color: var(--gray-800);
        }
        
        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 16px;
            border-radius: 8px;
            border: 2px solid var(--gray-200);
            background: var(--white);
            color: var(--gray-700);
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            font-size: 0.875rem;
            z-index: 1000;
            box-shadow: var(--shadow-md);
        }
        
        .theme-toggle:hover {
            background: var(--gray-100);
            border-color: var(--gray-300);
        }
        
        [data-theme="dark"] .theme-toggle {
            background: var(--gray-200);
            border-color: var(--gray-300);
            color: var(--gray-900);
        }
        
        [data-theme="dark"] .theme-toggle:hover {
            background: var(--gray-300);
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--light-blue) 0%, var(--white) 100%);
            padding: var(--spacing-6);
        }
        
        .login-card {
            background: var(--white);
            padding: var(--spacing-12);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-xl);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }
        
        .login-header {
            margin-bottom: var(--spacing-8);
        }
        
        .login-header .logo {
            font-size: var(--font-size-4xl);
            font-weight: 800;
            color: var(--primary-blue);
            margin-bottom: var(--spacing-2);
        }
        
        .login-header .tagline {
            color: var(--gray-600);
            font-size: var(--font-size-lg);
        }
        
        .role-selection {
            margin-bottom: var(--spacing-8);
        }
        
        .role-buttons {
            display: grid;
            gap: var(--spacing-4);
        }
        
        .role-btn {
            display: flex;
            align-items: center;
            gap: var(--spacing-4);
            padding: var(--spacing-4) var(--spacing-6);
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-lg);
            background: var(--white);
            text-decoration: none;
            color: var(--gray-700);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .role-btn:hover {
            border-color: var(--primary-blue);
            background: var(--light-blue);
            transform: translateY(-2px);
        }
        
        .role-btn.active {
            border-color: var(--primary-blue);
            background: var(--light-blue);
            color: var(--primary-blue);
        }
        
        .role-icon {
            font-size: 2rem;
        }
        
        .role-info h3 {
            font-size: var(--font-size-lg);
            font-weight: 600;
            margin-bottom: var(--spacing-1);
        }
        
        .role-info p {
            font-size: var(--font-size-sm);
            color: var(--gray-500);
        }
        
        .login-form {
            display: none;
        }
        
        .login-form.active {
            display: block;
        }
        
        .form-group {
            margin-bottom: var(--spacing-6);
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: var(--spacing-2);
            font-weight: 500;
            color: var(--gray-700);
        }
        
        .form-group input {
            width: 100%;
            padding: var(--spacing-3) var(--spacing-4);
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-md);
            font-size: var(--font-size-base);
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-blue);
        }
        
        .form-group input.error {
            border-color: #ef4444;
            background-color: #fef2f2;
        }
        
        .form-group input.valid {
            border-color: #10b981;
        }
        
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
        
        .error-message.show {
            display: block;
        }
        
        .login-btn {
            width: 100%;
            padding: var(--spacing-4);
            background: var(--primary-blue);
            color: var(--white);
            border: none;
            border-radius: var(--radius-lg);
            font-size: var(--font-size-lg);
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .login-btn:hover {
            background: var(--primary-blue-dark);
        }
        
        .back-btn {
            background: none;
            border: none;
            color: var(--gray-500);
            cursor: pointer;
            margin-bottom: var(--spacing-4);
            font-size: var(--font-size-sm);
        }
        
        .back-btn:hover {
            color: var(--primary-blue);
        }
        
        .admin-note {
            background: var(--gray-100);
            padding: var(--spacing-4);
            border-radius: var(--radius-md);
            margin-bottom: var(--spacing-6);
            font-size: var(--font-size-sm);
            color: var(--gray-600);
        }
        
        .admin-note strong {
            color: var(--primary-blue);
        }
    </style>
</head>
<body>
    
    <button class="theme-toggle" id="theme-toggle" onclick="toggleTheme()" aria-label="Toggle theme">
        <span id="theme-icon">🌙</span>
        <span id="theme-text">Dark</span>
    </button>
    
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <img src="public/assets/doxi-logo.svg?v=4" alt="DOXI Logo" style="height:64px;width:auto;">
                </div>
                <div class="tagline">Your Health, Our Priority</div>
            </div>
            
            <!-- Role Selection -->
            <div id="role-selection" class="role-selection">
                <a href="index.php" class="back-btn" style="margin-bottom: var(--spacing-4); display: inline-block;">← Back to Landing Page</a>
                <h2 style="margin-bottom: var(--spacing-6); color: var(--gray-800);">Select Your Role</h2>
                <div class="role-buttons">
                    <div class="role-btn" onclick="selectRole('patient')">
                        <div class="role-icon">👤</div>
                        <div class="role-info">
                            <h3>Patient</h3>
                            <p>Access your appointments and care updates</p>
                        </div>
                    </div>
                    
                    <div class="role-btn" onclick="selectRole('doctor')">
                        <div class="role-icon">👨‍⚕️</div>
                        <div class="role-info">
                            <h3>Doctor</h3>
                            <p>Manage patients and clinic operations</p>
                        </div>
                    </div>
                    
                </div>
                
            </div>
            
            <!-- Patient Login Form -->
            <div id="patient-login" class="login-form">
                <button class="back-btn" onclick="goBack()">← Back to Role Selection</button>
                <h2 style="margin-bottom: var(--spacing-6); color: var(--gray-800);">Patient Login</h2>
                <form id="patient-login-form" onsubmit="handleLogin(event, 'patient')">
                    <div class="form-group">
                        <label for="patient-email">Patient ID or Email</label>
                        <input type="text" id="patient-email" name="email" required>
                        <div class="error-message" id="patient-email-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="patient-password">Password</label>
                        <input type="password" id="patient-password" name="password" required>
                        <div class="error-message" id="patient-password-error"></div>
                    </div>
                    <button type="submit" class="login-btn">Login as Patient</button>
                </form>
                <div class="helper" style="margin-top: var(--spacing-4); text-align:center; color: var(--gray-600);">
                    New patient? <a href="patient-register.php">Create an account</a>
                </div>
            </div>
            
            <!-- Doctor Login Form -->
            <div id="doctor-login" class="login-form">
                <button class="back-btn" onclick="goBack()">← Back to Role Selection</button>
                <h2 style="margin-bottom: var(--spacing-6); color: var(--gray-800);">Doctor Login</h2>
                <form id="doctor-login-form" onsubmit="handleLogin(event, 'doctor')">
                    <div class="form-group">
                        <label for="doctor-email">Doctor ID or Email</label>
                        <input type="text" id="doctor-email" name="email" required>
                        <div class="error-message" id="doctor-email-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="doctor-password">Password</label>
                        <input type="password" id="doctor-password" name="password" required>
                        <div class="error-message" id="doctor-password-error"></div>
                    </div>
                    <button type="submit" class="login-btn">Login as Doctor</button>
                </form>
                <div class="helper" style="margin-top: var(--spacing-4); text-align:center; color: var(--gray-600);">
                    New doctor? <a href="doctor-register.php">Create an account</a>
                </div>
            </div>
            
        </div>
    </div>

    <script>
        // Theme init and toggle
        (function(){
            try{
                const savedTheme = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-theme', savedTheme);
                updateThemeToggle(savedTheme);
            }catch(_e){}
        })();
        
        function toggleTheme(){
            const current = document.documentElement.getAttribute('data-theme') || 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            updateThemeToggle(next);
        }
        
        function updateThemeToggle(theme) {
            const themeIcon = document.getElementById('theme-icon');
            const themeText = document.getElementById('theme-text');
            if (themeIcon && themeText) {
                if (theme === 'dark') {
                    themeIcon.textContent = '☀️';
                    themeText.textContent = 'Light';
                } else {
                    themeIcon.textContent = '🌙';
                    themeText.textContent = 'Dark';
                }
            }
        }
        // Redirect admin login attempts to dedicated admin page
        if (window.location.pathname.includes('admin-login')) {
            window.location.href = 'admin-login.php';
        }
        
        function selectRole(role) {
            // Hide role selection
            document.getElementById('role-selection').style.display = 'none';
            
            // Show appropriate login form
            document.getElementById(role + '-login').classList.add('active');
        }
        
        function goBack() {
            // Hide all login forms
            document.querySelectorAll('.login-form').forEach(form => {
                form.classList.remove('active');
            });
            
            // Show role selection
            document.getElementById('role-selection').style.display = 'block';
        }
        
        // Validation functions
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const idRegex = /^\d+$/;
            if (!email || email.trim() === '') {
                return { valid: false, message: 'Email or ID is required' };
            }
            if (email.includes('@')) {
                if (!emailRegex.test(email.trim())) {
                    return { valid: false, message: 'Please enter a valid email address' };
                }
            } else if (!idRegex.test(email.trim())) {
                return { valid: false, message: 'Please enter a valid ID or email address' };
            }
            return { valid: true, message: '' };
        }
        
        function validatePassword(password, fieldName = 'password') {
            if (!password || password.trim() === '') {
                return { valid: false, message: `${fieldName.charAt(0).toUpperCase() + fieldName.slice(1)} is required` };
            }
            if (password.length < 6) {
                return { valid: false, message: 'Password must be at least 6 characters long' };
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
        
        function clearValidation(inputId) {
            const input = document.getElementById(inputId);
            const errorDiv = document.getElementById(inputId + '-error');
            if (input && errorDiv) {
                input.classList.remove('error', 'valid');
                errorDiv.classList.remove('show');
            }
        }
        
        // Real-time validation
        document.addEventListener('DOMContentLoaded', function() {
            // Patient login form validation
            const patientEmail = document.getElementById('patient-email');
            const patientPassword = document.getElementById('patient-password');
            
            if (patientEmail) {
                patientEmail.addEventListener('blur', function() {
                    const result = validateEmail(this.value);
                    if (!result.valid) {
                        showError('patient-email', result.message);
                    } else {
                        showValid('patient-email');
                    }
                });
                patientEmail.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        const result = validateEmail(this.value);
                        if (result.valid) {
                            showValid('patient-email');
                        }
                    }
                });
            }
            
            if (patientPassword) {
                patientPassword.addEventListener('blur', function() {
                    const result = validatePassword(this.value, 'password');
                    if (!result.valid) {
                        showError('patient-password', result.message);
                    } else {
                        showValid('patient-password');
                    }
                });
                patientPassword.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        const result = validatePassword(this.value, 'password');
                        if (result.valid) {
                            showValid('patient-password');
                        }
                    }
                });
            }
            
            // Doctor login form validation
            const doctorEmail = document.getElementById('doctor-email');
            const doctorPassword = document.getElementById('doctor-password');
            
            if (doctorEmail) {
                doctorEmail.addEventListener('blur', function() {
                    const result = validateEmail(this.value);
                    if (!result.valid) {
                        showError('doctor-email', result.message);
                    } else {
                        showValid('doctor-email');
                    }
                });
                doctorEmail.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        const result = validateEmail(this.value);
                        if (result.valid) {
                            showValid('doctor-email');
                        }
                    }
                });
            }
            
            if (doctorPassword) {
                doctorPassword.addEventListener('blur', function() {
                    const result = validatePassword(this.value, 'password');
                    if (!result.valid) {
                        showError('doctor-password', result.message);
                    } else {
                        showValid('doctor-password');
                    }
                });
                doctorPassword.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        const result = validatePassword(this.value, 'password');
                        if (result.valid) {
                            showValid('doctor-password');
                        }
                    }
                });
            }
        });
        
        async function handleLogin(event, role) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const email = formData.get('email');
            const password = formData.get('password');
            
            // Clear previous errors
            const emailField = role === 'patient' ? 'patient-email' : 'doctor-email';
            const passwordField = role === 'patient' ? 'patient-password' : 'doctor-password';
            
            // Validate all fields
            const emailValidation = validateEmail(email);
            const passwordValidation = validatePassword(password, 'password');
            
            let isValid = true;
            
            if (!emailValidation.valid) {
                showError(emailField, emailValidation.message);
                isValid = false;
            } else {
                showValid(emailField);
            }
            
            if (!passwordValidation.valid) {
                showError(passwordField, passwordValidation.message);
                isValid = false;
            } else {
                showValid(passwordField);
            }
            
            if (!isValid) {
                return;
            }
            
            // Show loading state
            const submitBtn = event.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Logging in...';
            submitBtn.disabled = true;
            
            try {
                const response = await fetch('api/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password,
                        role: role
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Store user session
                    sessionStorage.setItem('userRole', role);
                    sessionStorage.setItem('userEmail', email);
                    sessionStorage.setItem('isLoggedIn', 'true');
                    sessionStorage.setItem('userData', JSON.stringify(result.user));
                    sessionStorage.setItem('authToken', result.token);
                    // Fire-and-forget system log
                    try{ fetch('api/logs.php', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ level:'INFO', message: `${role} login success`, user_email: email }) }); }catch(_e){}
                    
                    // Redirect to appropriate dashboard
                    window.location.href = `${role}-dashboard.php`;
                } else {
                    alert('Error: ' + result.message);
                    try{ fetch('api/logs.php', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ level:'WARN', message: `${role} login failed: ${result.message}`, user_email: email }) }); }catch(_e){}
                }
            } catch (error) {
                console.error('Login error:', error);
                alert('Login failed. Please try again.');
            } finally {
                // Reset button state
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        }
        
        function authenticateUser(email, password, role) {
            // Built-in demo credentials
            const demoCredentials = {
                patient: [
                    { email: 'patient1@doxi.com', password: 'patient1' },
                    { email: 'john.doe@doxi.com', password: 'password123' }
                ],
                doctor: [
                    { email: 'doctor1@doxi.com', password: 'doctor1' },
                    { email: 'dr.smith@doxi.com', password: 'password123' }
                ],
                admin: [
                    { email: 'admin@doxi.com', password: 'admin123' },
                    { email: 'administrator', password: 'adminpass' }
                ]
            };
            
            // Also check locally registered users
            const storageKey = role === 'patient' ? 'patients' : role === 'doctor' ? 'doctors' : null;
            const registered = storageKey ? JSON.parse(localStorage.getItem(storageKey) || '[]') : [];
            
            const inDemo = demoCredentials[role].some(cred => cred.email === email && cred.password === password);
            const inRegistered = registered.some(u => u.email === email && u.password === password);
            return inDemo || inRegistered;
        }

        // Preselect role via URL parameter (e.g., login.php?role=patient)
        (function(){
            const params = new URLSearchParams(location.search);
            const role = params.get('role');
            if(role && ['patient','doctor'].includes(role)){
                document.getElementById('role-selection').style.display = 'none';
                document.getElementById(role + '-login').classList.add('active');
            }
        })();
        
        // Check if user is already logged in
        if (sessionStorage.getItem('isLoggedIn') === 'true') {
            const userRole = sessionStorage.getItem('userRole');
            window.location.href = `${userRole}-dashboard.php`;
        }
    </script>
</body>
</html>
