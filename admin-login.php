<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Admin Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/svg+xml" href="public/assets/doxi-icon.svg?v=1">
    <style>
        .admin-login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            padding: var(--spacing-6);
        }
        
        .admin-login-card {
            background: var(--white);
            padding: var(--spacing-12);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-xl);
            width: 100%;
            max-width: 450px;
            text-align: center;
            border: 3px solid var(--primary-blue);
        }
        
        .admin-header {
            margin-bottom: var(--spacing-8);
        }
        
        .admin-header .logo {
            font-size: var(--font-size-4xl);
            font-weight: 800;
            color: var(--primary-blue);
            margin-bottom: var(--spacing-2);
        }
        
        .admin-header .tagline {
            color: var(--gray-600);
            font-size: var(--font-size-lg);
        }
        
        .admin-badge {
            background: var(--primary-blue);
            color: var(--white);
            padding: var(--spacing-2) var(--spacing-4);
            border-radius: var(--radius-md);
            font-size: var(--font-size-sm);
            font-weight: 600;
            display: inline-block;
            margin-bottom: var(--spacing-6);
        }
        
        .admin-warning {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: var(--spacing-4);
            border-radius: var(--radius-md);
            margin-bottom: var(--spacing-6);
            font-size: var(--font-size-sm);
        }
        
        .admin-warning strong {
            color: #b45309;
        }
        
        .form-group {
            margin-bottom: var(--spacing-6);
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: var(--spacing-2);
            font-weight: 600;
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
        
        .admin-login-btn {
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
        
        .admin-login-btn:hover {
            background: var(--primary-blue-dark);
        }
        
        .back-to-main {
            margin-top: var(--spacing-6);
        }
        
        .back-to-main a {
            color: var(--gray-500);
            text-decoration: none;
            font-size: var(--font-size-sm);
        }
        
        .back-to-main a:hover {
            color: var(--primary-blue);
        }
        
        .security-notice {
            background: var(--gray-100);
            padding: var(--spacing-4);
            border-radius: var(--radius-md);
            margin-top: var(--spacing-6);
            font-size: var(--font-size-xs);
            color: var(--gray-600);
        }
    </style>
</head>
<body>
    <div class="admin-login-container">
        <div class="admin-login-card">
            <div class="admin-header">
                <div class="logo">
                    <span style="display:inline-flex;align-items:center;gap:12px;">
                        <span style="width:48px;height:48px;border-radius:16px;background:linear-gradient(135deg,#3b82f6 0%,#1d4ed8 100%);display:flex;align-items:center;justify-content:center;box-shadow:0 8px 20px rgba(37,99,235,.3);">
                            <span style="width:28px;height:28px;border-radius:10px;border:2px solid #fff;display:flex;align-items:center;justify-content:center;">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 8.5L6.5 11L12 5.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </span>
                        <span style="font-size:32px;font-weight:800;color:#2563eb;letter-spacing:0.6px;">DOXI</span>
                    </span>
                </div>
                <div class="tagline">Your Health, Our Priority</div>
                <div class="admin-badge">🔒 ADMIN ACCESS</div>
            </div>
            
            <div class="admin-warning">
                <strong>⚠️ Restricted Access:</strong> This area is for authorized administrators only. All access attempts are logged and monitored.
            </div>
            
            <form id="admin-login-form" onsubmit="handleAdminLogin(event)">
                <div class="form-group">
                    <label for="admin-username">Admin Username</label>
                    <input type="text" id="admin-username" name="username" required placeholder="Enter admin username">
                    <div class="error-message" id="admin-username-error"></div>
                </div>
                <div class="form-group">
                    <label for="admin-password">Password</label>
                    <input type="password" id="admin-password" name="password" required placeholder="Enter admin password">
                    <div class="error-message" id="admin-password-error"></div>
                </div>
                <button type="submit" class="admin-login-btn">🔐 Login as Administrator</button>
            </form>
            
            <div class="back-to-main">
                <a href="login.php">← Back to Main Login</a>
            </div>
            
            <div class="security-notice">
                <strong>Security Notice:</strong> This is a secure administrative portal. Unauthorized access is prohibited and may result in legal action.
            </div>
        </div>
    </div>

    <script>
        // Check if user is already logged in as admin
        if (sessionStorage.getItem('isLoggedIn') === 'true' && sessionStorage.getItem('userRole') === 'admin') {
            window.location.href = 'admin-dashboard.php';
        }
        
        // Validation functions
        function validateUsername(username) {
            if (!username || username.trim() === '') {
                return { valid: false, message: 'Username is required' };
            }
            if (username.trim().length < 3) {
                return { valid: false, message: 'Username must be at least 3 characters long' };
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
            const username = document.getElementById('admin-username');
            const password = document.getElementById('admin-password');
            
            if (username) {
                username.addEventListener('blur', function() {
                    const result = validateUsername(this.value);
                    if (!result.valid) {
                        showError('admin-username', result.message);
                    } else {
                        showValid('admin-username');
                    }
                });
                username.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        const result = validateUsername(this.value);
                        if (result.valid) {
                            showValid('admin-username');
                        }
                    }
                });
            }
            
            if (password) {
                password.addEventListener('blur', function() {
                    const result = validatePassword(this.value);
                    if (!result.valid) {
                        showError('admin-password', result.message);
                    } else {
                        showValid('admin-password');
                    }
                });
                password.addEventListener('input', function() {
                    if (this.classList.contains('error')) {
                        const result = validatePassword(this.value);
                        if (result.valid) {
                            showValid('admin-password');
                        }
                    }
                });
            }
        });
        
        async function handleAdminLogin(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const username = formData.get('username');
            const password = formData.get('password');
            
            // Validate all fields
            const usernameValidation = validateUsername(username);
            const passwordValidation = validatePassword(password);
            
            let isValid = true;
            
            if (!usernameValidation.valid) {
                showError('admin-username', usernameValidation.message);
                isValid = false;
            } else {
                showValid('admin-username');
            }
            
            if (!passwordValidation.valid) {
                showError('admin-password', passwordValidation.message);
                isValid = false;
            } else {
                showValid('admin-password');
            }
            
            if (!isValid) {
                return;
            }
            
            const loginBtn = document.querySelector('.admin-login-btn');
            const prevLabel = loginBtn ? loginBtn.textContent : '';
            if (loginBtn){ loginBtn.disabled = true; loginBtn.textContent = 'Authenticating...'; }

            try{
                const result = await authenticateAdmin(username, password);
                
                const user = result.user || {};
                sessionStorage.setItem('userRole', 'admin');
                sessionStorage.setItem('userEmail', user.email || username);
                sessionStorage.setItem('userId', user.id || '');
                sessionStorage.setItem('isLoggedIn', 'true');
                sessionStorage.setItem('adminAccess', 'true');
                
                try{ fetch('api/logs.php', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ level:'INFO', message:'admin login success', user_email: user.email || username }) }); }catch(_e){}
                
                window.location.href = 'admin-dashboard.php';
            } catch(error){
                const message = error?.message || 'Invalid admin credentials. Access denied.';
                alert(message);
                try{ fetch('api/logs.php', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ level:'WARN', message:'admin login failed', user_email: username }) }); }catch(_e){}
            } finally {
                if (loginBtn){ loginBtn.disabled = false; loginBtn.textContent = prevLabel || '🔐 Login as Administrator'; }
            }
        }
        
        async function authenticateAdmin(username, password) {
            const payload = {
                email: username,
                password,
                role: 'admin'
            };
            const response = await fetch('api/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const data = await response.json().catch(()=>null);
            if (!response.ok || !data || !data.success){
                throw new Error((data && data.message) ? data.message : 'Invalid admin credentials. Access denied.');
            }
            return data;
        }
        
        // Auto-focus on username field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('admin-username').focus();
        });
    </script>
    <script src="public/js/email-phone-validation.js"></script>
</body>
</html>
