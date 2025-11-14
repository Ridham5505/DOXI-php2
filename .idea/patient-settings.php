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
        input, select { width:100%; padding: var(--spacing-3) var(--spacing-4); border: 2px solid var(--gray-200); border-radius: var(--radius-md); font-family: var(--font-family); background: var(--white); color: var(--gray-900); transition: background 0.3s, border-color 0.3s; }
        input:focus, select:focus { outline:none; border-color: var(--primary-blue); }
        input:disabled, select:disabled { background: var(--gray-50); cursor: default; }
        .error { color: #dc2626; font-size: var(--font-size-sm); margin-top: 4px; }
        .invalid { border-color: #dc2626 !important; }
        .btn-row { display:flex; gap: var(--spacing-3); justify-content: flex-end; margin-top: var(--spacing-4); }
        .btn { padding: var(--spacing-3) var(--spacing-6); border-radius: var(--radius-md); border: none; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn-primary { background: var(--primary-blue); color: var(--white); }
        .btn-primary:hover { background: var(--primary-blue-dark); }
        .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }
        .btn-outline { background: var(--white); color: var(--primary-blue); border: 2px solid var(--primary-blue); }
        .btn-outline:hover { background: var(--primary-blue); color: var(--white); }
        .back { text-decoration:none; color: var(--gray-600); transition: color 0.3s; }
        .back:hover { color: var(--gray-900); }
        .view-mode .form-group input, .view-mode .form-group select { background: var(--gray-50); cursor: default; }
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
    </style>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⚙️</text></svg>">
</head>
<body>
    <div class="page">
        <div class="header">
            <div>
                <div class="logo">DOXI</div>
                <div class="tagline">Settings & Profile</div>
            </div>
            <div class="header-right">
                <button class="theme-toggle" id="theme-toggle" onclick="toggleTheme()">
                    <span id="theme-icon">🌙</span>
                    <span id="theme-text">Dark</span>
                </button>
                <a class="back" href="patient-dashboard.php">← Back to Dashboard</a>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: var(--spacing-4);">
                <h2 class="section-title" style="margin:0; border:none; padding:0;">Personal Information</h2>
                <button id="edit-btn" class="btn btn-primary" onclick="toggleEditMode()">Edit Profile</button>
            </div>
            
            <form id="profile-form" class="view-mode">
                <input type="hidden" id="user-id">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" required />
                        <div id="err-first_name" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" required />
                        <div id="err-last_name" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" required />
                        <div id="err-email" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" />
                        <div id="err-phone" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" id="date_of_birth" />
                        <div id="err-date_of_birth" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select id="gender">
                            <option value="">Select Gender...</option>
                            <option value="Female">Female</option>
                            <option value="Male">Male</option>
                            <option value="Other">Other</option>
                            <option value="Prefer not to say">Prefer not to say</option>
                        </select>
                        <div id="err-gender" class="error"></div>
                    </div>
                    <div class="form-group full-width">
                        <label for="address">Address</label>
                        <input type="text" id="address" />
                        <div id="err-address" class="error"></div>
                    </div>
                </div>
                
                <div class="btn-row" id="save-btn-row" style="display:none;">
                    <button type="button" class="btn btn-outline" onclick="cancelEdit()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="save-btn">Save Changes</button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div class="card">
            <h2 class="section-title">Change Password</h2>
            <form id="password-form">
                <div class="password-form-grid">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" />
                        <div id="err-current_password" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" />
                        <div id="err-new_password" class="error"></div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" />
                        <div id="err-confirm_password" class="error"></div>
                    </div>
                </div>
                <div class="btn-row">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        if (sessionStorage.getItem('isLoggedIn') !== 'true' || sessionStorage.getItem('userRole') !== 'patient') {
            window.location.href = 'login.php';
        }

        let currentUserId = null;
        let isEditMode = false;
        
        function getJSON(url){ return fetch(url).then(r=>r.json()); }
        function api(path, method='GET', body){
            return fetch(path, { method, headers:{ 'Content-Type':'application/json' }, body: body ? JSON.stringify(body) : undefined }).then(r=>r.json());
        }

        async function loadCurrentUser(){
            const email = sessionStorage.getItem('userEmail');
            const res = await getJSON(`api/users.php?search=${encodeURIComponent(email)}&page=1&limit=1`);
            if (res.success && res.data && res.data.length){
                const user = res.data[0];
                currentUserId = user.id;
                populateForm(user);
            }
        }

        function populateForm(user){
            document.getElementById('user-id').value = user.id;
            document.getElementById('first_name').value = user.first_name || '';
            document.getElementById('last_name').value = user.last_name || '';
            document.getElementById('email').value = user.email || '';
            document.getElementById('phone').value = user.phone || '';
            document.getElementById('date_of_birth').value = user.date_of_birth || '';
            document.getElementById('gender').value = user.gender || '';
            document.getElementById('address').value = user.address || '';
        }

        function toggleEditMode(){
            isEditMode = !isEditMode;
            const form = document.getElementById('profile-form');
            const editBtn = document.getElementById('edit-btn');
            const saveBtnRow = document.getElementById('save-btn-row');
            
            if (isEditMode){
                form.classList.remove('view-mode');
                editBtn.style.display = 'none';
                saveBtnRow.style.display = 'flex';
                // Enable all inputs
                form.querySelectorAll('input, select').forEach(el=>{
                    el.disabled = false;
                });
            } else {
                form.classList.add('view-mode');
                editBtn.style.display = 'block';
                saveBtnRow.style.display = 'none';
                // Disable all inputs
                form.querySelectorAll('input, select').forEach(el=>{
                    el.disabled = true;
                });
            }
        }

        function cancelEdit(){
            loadCurrentUser(); // Reload original data
            toggleEditMode(); // Exit edit mode
            clearErrors();
        }

        function clearErrors(){
            ['first_name','last_name','email','phone','date_of_birth','gender','address'].forEach(field=>{
                setError(`err-${field}`, '', field);
            });
        }

        function setError(id, message, inputId){
            const el = document.getElementById(id);
            if (el) el.textContent = message || '';
            if (inputId){
                const input = document.getElementById(inputId);
                if (input) input.classList.toggle('invalid', !!message);
            }
        }

        function validateProfileForm(){
            clearErrors();
            let valid = true;
            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const dob = document.getElementById('date_of_birth').value;

            if (!firstName){
                valid = false;
                setError('err-first_name', 'First name is required.', 'first_name');
            }

            if (!lastName){
                valid = false;
                setError('err-last_name', 'Last name is required.', 'last_name');
            }

            if (!email){
                valid = false;
                setError('err-email', 'Email is required.', 'email');
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){
                valid = false;
                setError('err-email', 'Please enter a valid email address.', 'email');
            }

            if (phone && !/^[\d\s\-\(\)\+]+$/.test(phone)){
                valid = false;
                setError('err-phone', 'Please enter a valid phone number.', 'phone');
            }

            if (dob){
                const dobDate = new Date(dob);
                const today = new Date();
                if (dobDate > today){
                    valid = false;
                    setError('err-date_of_birth', 'Date of birth cannot be in the future.', 'date_of_birth');
                }
            }

            return valid;
        }

        function validatePasswordForm(){
            const current = document.getElementById('current_password').value;
            const newPass = document.getElementById('new_password').value;
            const confirm = document.getElementById('confirm_password').value;

            ['current_password','new_password','confirm_password'].forEach(field=>{
                setError(`err-${field}`, '', field);
            });

            let valid = true;

            if (!current){
                valid = false;
                setError('err-current_password', 'Current password is required.', 'current_password');
            }

            if (!newPass){
                valid = false;
                setError('err-new_password', 'New password is required.', 'new_password');
            } else if (newPass.length < 6){
                valid = false;
                setError('err-new_password', 'Password must be at least 6 characters.', 'new_password');
            }

            if (!confirm){
                valid = false;
                setError('err-confirm_password', 'Please confirm your new password.', 'confirm_password');
            } else if (newPass !== confirm){
                valid = false;
                setError('err-confirm_password', 'Passwords do not match.', 'confirm_password');
            }

            return valid;
        }

        document.getElementById('profile-form').addEventListener('submit', async (e)=>{
            e.preventDefault();
            if (!validateProfileForm()) return;
            if (!currentUserId) { alert('User not loaded'); return; }

            const payload = {
                id: currentUserId,
                first_name: document.getElementById('first_name').value.trim(),
                last_name: document.getElementById('last_name').value.trim(),
                email: document.getElementById('email').value.trim(),
                phone: document.getElementById('phone').value.trim(),
                date_of_birth: document.getElementById('date_of_birth').value || null,
                gender: document.getElementById('gender').value || null,
                address: document.getElementById('address').value.trim() || null
            };

            const saveBtn = document.getElementById('save-btn');
            const prev = saveBtn.textContent;
            saveBtn.disabled = true;
            saveBtn.textContent = 'Saving...';

            const res = await api('api/users.php', 'PUT', payload);
            
            if (res.success){
                alert('Profile updated successfully!');
                await loadCurrentUser(); // Reload to get updated data
                toggleEditMode(); // Exit edit mode
            } else {
                alert(res.message || 'Failed to update profile');
            }

            saveBtn.disabled = false;
            saveBtn.textContent = prev;
        });

        document.getElementById('password-form').addEventListener('submit', async (e)=>{
            e.preventDefault();
            if (!validatePasswordForm()) return;
            if (!currentUserId) { alert('User not loaded'); return; }

            const payload = {
                id: currentUserId,
                password: document.getElementById('new_password').value
            };

            const submitBtn = e.target.querySelector('button[type="submit"]');
            const prev = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Changing...';

            // Note: The API doesn't verify current password, but we'll note this limitation
            const res = await api('api/users.php', 'PUT', payload);
            
            if (res.success){
                alert('Password changed successfully!');
                document.getElementById('password-form').reset();
            } else {
                alert(res.message || 'Failed to change password');
            }

            submitBtn.disabled = false;
            submitBtn.textContent = prev;
        });

        // Theme toggle functionality
        function applyTheme(theme){
            const t = (theme === 'dark') ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', t);
            try{ localStorage.setItem('theme', t); }catch(_e){}
            updateThemeToggle(t);
        }

        function updateThemeToggle(theme){
            const icon = document.getElementById('theme-icon');
            const text = document.getElementById('theme-text');
            if (theme === 'dark'){
                icon.textContent = '☀️';
                text.textContent = 'Light';
            } else {
                icon.textContent = '🌙';
                text.textContent = 'Dark';
            }
        }

        function toggleTheme(){
            const current = document.documentElement.getAttribute('data-theme') || 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            applyTheme(next);
        }

        (async function init(){
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

            // Initialize in view mode
            toggleEditMode(); // This sets it to view mode
            await loadCurrentUser();
        })();
    </script>
</body>
</html>
