<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Patient Dashboard</title>
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
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏥</text></svg>">
    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-blue-dark: #1d4ed8;
            --primary-blue-light: #3b82f6;
            --secondary-blue: #1e40af;
            --accent-blue: #60a5fa;
            --light-blue: #dbeafe;
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
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -2px rgba(0, 0, 0, 0.3);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 10px 10px -5px rgba(0, 0, 0, 0.3);
        }
        html, body { margin:0; height:100%; background: var(--gray-50); color: var(--gray-900); transition: background 0.3s, color 0.3s; }
        html { scroll-behavior: smooth; }
        .dashboard-container {
            min-height: 100vh;
            background: var(--gray-50);
        }
        
        .dashboard-header {
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            padding: var(--spacing-4) 0;
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--spacing-6);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: var(--spacing-4);
        }
        
        .header-left .logo {
            display:flex;
            align-items:center;
        }
        .header-left .logo img{display:block;height:36px;width:auto;}
        
        .user-info {
            display: flex;
            align-items: center;
            gap: var(--spacing-3);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--primary-blue);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 600;
        }
        
        .user-details h3 {
            font-size: var(--font-size-base);
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: var(--spacing-1);
        }
        
        .user-details p {
            font-size: var(--font-size-sm);
            color: var(--gray-500);
        }
        
        .logout-btn {
            background: var(--gray-100);
            color: var(--gray-700);
            border: none;
            padding: var(--spacing-2) var(--spacing-4);
            border-radius: var(--radius-md);
            cursor: pointer;
            font-size: var(--font-size-sm);
            transition: background-color 0.3s ease;
        }
        
        .logout-btn:hover {
            background: var(--gray-200);
        }
        
        /* Layout with sidebar */
        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: var(--spacing-8) var(--spacing-6);
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: var(--spacing-6);
        }

        .sidebar {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            padding: var(--spacing-4);
            height: fit-content;
            position: sticky;
            top: var(--spacing-6);
        }

        .sidebar-title {
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: var(--spacing-3);
        }

        .nav-list { list-style: none; margin: 0; padding: 0; }
        .nav-item {
            display: flex; align-items: center; gap: var(--spacing-3);
            padding: 10px 12px; border-radius: var(--radius-lg);
            color: var(--gray-700); text-decoration: none;
            transition: background-color .2s ease, color .2s ease;
            margin-bottom: 6px;
        }
        .nav-item .icon {
            width: 28px; height: 28px; border-radius: 8px; background: var(--light-blue);
            display: grid; place-items: center; color: var(--primary-blue); font-size: 14px;
        }
        .nav-item:hover { background: var(--gray-100); color: var(--gray-900); }
        .nav-item.active { background: var(--primary-blue); color: var(--white); }
        .nav-item.active .icon { background: rgba(255,255,255,.15); color: var(--white); }

        /* Main column wrapper to keep original spacing */
        .dashboard-main { max-width: 100%; margin: 0; padding: 0; }
        section.module-section { margin-bottom: var(--spacing-8); }

        @media (max-width: 900px) {
            .content-wrapper { grid-template-columns: 1fr; }
            .sidebar { position: static; }
        }
        
        .dashboard-title {
            font-size: var(--font-size-3xl);
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: var(--spacing-2);
        }
        
        .dashboard-subtitle {
            color: var(--gray-600);
            margin-bottom: var(--spacing-8);
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--spacing-6);
            margin-bottom: var(--spacing-8);
        }
        
        .dashboard-card {
            background: var(--white);
            padding: var(--spacing-6);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--gray-200);
        }
        /* NEW: stats tiles like screenshot */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: var(--spacing-6); }
        .stat-card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius-xl); box-shadow: var(--shadow-md); padding: var(--spacing-6); display: grid; gap: var(--spacing-2); cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
        .stat-label { color: var(--gray-500); font-weight: 500; }
        .stat-value { font-size: var(--font-size-2xl); font-weight: 800; color: var(--primary-blue); }
        .stat-icon { width: 36px; height: 36px; border-radius: 10px; display: grid; place-items: center; }
        .stat-card-row{display:flex;align-items:center;justify-content:space-between;gap:var(--spacing-4);width:100%;}
        .icon-blue { background: var(--light-blue); color: var(--primary-blue); }
        .icon-green { background: #dcfce7; color: #16a34a; }
        .icon-yellow { background: #fef9c3; color: #ca8a04; }
        .icon-red { background: #fee2e2; color: #b91c1c; }
        @media (max-width: 1100px){ .stats-grid{ grid-template-columns: repeat(2, 1fr);} }
        @media (max-width: 640px){ .stats-grid{ grid-template-columns: 1fr;} }

        /* NEW: action row cards */
        .actions-grid { display: grid; grid-template-columns: 1.4fr .9fr .9fr; gap: var(--spacing-6); }
        .action-card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius-xl); box-shadow: var(--shadow-md); padding: var(--spacing-4); display: grid; gap: var(--spacing-3); }
        .progress-track { height: 6px; background: var(--gray-100); border-radius: 9999px; overflow: hidden; }
        .progress-bar { height: 100%; width: 65%; background: var(--primary-blue); }
        .link-btn { color: var(--primary-blue); font-weight: 600; text-decoration: none; }
        .link-btn:hover { text-decoration: underline; }
        @media (max-width: 1100px){ .actions-grid{ grid-template-columns: 1fr; } }
        .action-card .action-btn { padding: var(--spacing-2) var(--spacing-4); border-radius: var(--radius-md); width: fit-content; }
        .action-card .stat-icon { width: 30px; height: 30px; }

        /* NEW: recent lists */
        .two-col-grid { display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-6); }
        @media (max-width: 900px){ .two-col-grid { grid-template-columns: 1fr; } }
        .list { display: grid; gap: var(--spacing-3); }
        .list-item { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); padding: var(--spacing-4) var(--spacing-5); display: flex; align-items: center; justify-content: space-between; }
        .item-title { font-weight: 600; color: var(--gray-900); }
        .item-sub { color: var(--gray-500); font-size: var(--font-size-sm); }
        .pill { padding: 4px 10px; border-radius: 9999px; font-size: var(--font-size-xs); font-weight: 700; text-transform: lowercase; }
        .pill.confirmed { background: #dcfce7; color: #15803d; }
        .pill.completed { background: #e0e7ff; color: #3730a3; }
        .pill.scheduled { background: #dcfce7; color: #166534; }
        .pill.active { background: #dcfce7; color: #166534; }
        .pill.cancelled { background: #fee2e2; color: #b91c1c; }
        .pill.rescheduled { background: #fef3c7; color: #b45309; }
        .section-card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius-xl); box-shadow: var(--shadow-md); padding: var(--spacing-6); }
        
        .card-header {
            display: flex;
            align-items: center;
            gap: var(--spacing-3);
            margin-bottom: var(--spacing-4);
        }
        
        .card-icon {
            font-size: 2rem;
        }
        
        .card-title {
            font-size: var(--font-size-lg);
            font-weight: 600;
            color: var(--gray-900);
        }
        
        .card-content {
            color: var(--gray-600);
            line-height: 1.6;
        }
        
        .appointment-list {
            list-style: none;
            padding: 0;
        }
        
        .appointment-item {
            background: var(--gray-50);
            padding: var(--spacing-4);
            border-radius: var(--radius-md);
            margin-bottom: var(--spacing-3);
            border-left: 4px solid var(--primary-blue);
        }
        
        .appointment-date {
            font-weight: 600;
            color: var(--gray-900);
        }
        
        .appointment-doctor {
            color: var(--gray-600);
            font-size: var(--font-size-sm);
        }
        
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--spacing-4);
        }
        
        .action-btn {
            background: var(--primary-blue);
            color: var(--white);
            border: none;
            padding: var(--spacing-4);
            border-radius: var(--radius-lg);
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        
        .action-btn:hover {
            background: var(--primary-blue-dark);
        }
        
        .action-btn.secondary {
            background: var(--white);
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
        }
        
        .action-btn.secondary:hover {
            background: var(--primary-blue);
            color: var(--white);
        }
        .theme-toggle { padding: 8px 14px; border-radius: 8px; border: 1px solid var(--gray-200); background: var(--white); color: var(--gray-700); cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .theme-toggle:hover { background: var(--gray-100); }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Header -->
        <header class="dashboard-header">
            <div class="header-content">
                <div class="header-left">
                    <div class="logo" style="display:flex; align-items:center; gap:var(--spacing-2);">
                        <img src="public/assets/doxi-logo.svg?v=2" alt="DOXI" width="120" height="36" style="display:block;">
                        <span style="color: #2563eb; font-weight:700;">Patient Portal</span>
                    </div>
                </div>
                <div class="user-info">
                    <button class="theme-toggle" id="theme-toggle" onclick="toggleTheme()">
                        <span id="theme-icon">🌙</span>
                        <span id="theme-text">Dark</span>
                    </button>
                    <div class="user-avatar">P</div>
                    <div class="user-details">
                        <h3 id="patient-name">Patient</h3>
                        <p id="patient-id-label">Patient ID: —</p>
                    </div>
                    <button class="logout-btn" onclick="logout()">Logout</button>
                </div>
            </div>
        </header>
        
        <!-- Main Content with Sidebar -->
        <div class="content-wrapper">
            <!-- Sidebar -->
            <aside class="sidebar" aria-label="Patient navigation">
                <div class="sidebar-title">Navigation</div>
                <ul class="nav-list">
                    <li><a class="nav-item active" href="#dashboard"><span class="icon">🏠</span><span>Dashboard</span></a></li>
                    <li><a class="nav-item" href="book-appointment.php"><span class="icon">📅</span><span>Book Appointment</span></a></li>
                    <li><a class="nav-item" href="find-doctor.php"><span class="icon">🩺</span><span>Find Doctor</span></a></li>
                    <li><a class="nav-item" href="medical-history.php"><span class="icon">📋</span><span>Medical History</span></a></li>
                    <li><a class="nav-item" href="reviews.php"><span class="icon">⭐</span><span>Review & Rating</span></a></li>
                    <li><a class="nav-item" href="doctor-availability.php"><span class="icon">🗓️</span><span>Doctor Availability</span></a></li>
                    <li><a class="nav-item" href="patient-settings.php"><span class="icon">⚙️</span><span>Settings / Profile</span></a></li>
                </ul>
            </aside>

            <main class="dashboard-main">
                <section id="dashboard" class="module-section" tabindex="-1">
                    <h1 class="dashboard-title" id="welcome-title">Welcome back!</h1>
                    <p class="dashboard-subtitle">Here's your health overview and upcoming appointments</p>
                    <!-- Stats row -->
                    <div class="stats-grid" aria-label="Quick stats">
                        <div class="stat-card" onclick="window.location.href='book-appointment.php?filter=upcoming'">
                            <div class="stat-card-row">
                                <div>
                                    <div class="stat-label">Upcoming Appointments</div>
                                    <div class="stat-value" id="stat-upcoming">0</div>
                                </div>
                                <div class="stat-icon icon-blue">📅</div>
                            </div>
                        </div>
                        <div class="stat-card" onclick="window.location.href='book-appointment.php?filter=all'">
                            <div class="stat-card-row">
                                <div>
                                    <div class="stat-label">Total Appointments</div>
                                    <div class="stat-value" id="stat-total">0</div>
                                </div>
                                <div class="stat-icon icon-green">🩺</div>
                            </div>
                        </div>
                        <div class="stat-card" onclick="window.location.href='reviews.php'">
                            <div class="stat-card-row">
                                <div>
                                    <div class="stat-label">Doctors Reviewed</div>
                                    <div class="stat-value" id="stat-reviewed">0</div>
                                </div>
                                <div class="stat-icon icon-yellow">⭐</div>
                            </div>
                        </div>
                        <div class="stat-card" onclick="window.location.href='find-doctor.php#favorites'">
                            <div class="stat-card-row">
                                <div>
                                    <div class="stat-label">Saved Doctors</div>
                                    <div class="stat-value" id="stat-favorites">0</div>
                                </div>
                                <div class="stat-icon icon-red">👤</div>
                            </div>
                        </div>
                    </div>

                    <!-- Action row -->
                    <div class="actions-grid" style="margin: var(--spacing-8) 0;">
                        <div class="action-card">
                            <div class="stat-card-row">
                                <div>
                                    <div style="font-weight:700; color: var(--gray-900);">Book Appointment</div>
                                    <div class="item-sub">Quickly schedule a new appointment</div>
                                </div>
                                <div class="stat-icon icon-blue">📅</div>
                            </div>
                            <div class="progress-track" aria-hidden="true"><div class="progress-bar"></div></div>
                            <a class="action-btn" href="book-appointment.php" onclick="bookAppointment();return true;">Book Now</a>
                        </div>
                        <div class="action-card">
                            <div class="stat-card-row">
                                <div>
                                    <div style="font-weight:700; color: var(--gray-900);">Find Doctor</div>
                                    <div class="item-sub">Search and filter doctors</div>
                                </div>
                                <div class="stat-icon icon-green">🩺</div>
                            </div>
                            <div class="progress-track" aria-hidden="true"><div class="progress-bar"></div></div>
                            <a class="action-btn" href="find-doctor.php">Find Doctor</a>
                        </div>
                        <div class="action-card">
                            <div class="stat-card-row">
                                <div>
                                    <div style="font-weight:700; color: var(--gray-900);">Manage Profile</div>
                                    <div class="item-sub">Update your personal information</div>
                                </div>
                                <div class="stat-icon icon-yellow">⚙️</div>
                            </div>
                            <div class="progress-track" aria-hidden="true"><div class="progress-bar"></div></div>
                            <a class="action-btn" href="patient-settings.php">Go to Settings</a>
                        </div>
                    </div>

                    <!-- Bottom Section -->
                    <div class="two-col-grid">
                        <div class="section-card">
                            <div style="font-weight:700; color: var(--gray-900); margin-bottom: var(--spacing-4);">Upcoming Appointments</div>
                            <div class="list" id="upcoming-appointments">
                                <div class="list-item">
                                    <div class="list-item-info">
                                        <div class="list-item-name">Loading...</div>
                                        <div class="list-item-meta">Please wait</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="section-card">
                            <div style="font-weight:700; color: var(--gray-900); margin-bottom: var(--spacing-4);">Your Recent Reviews</div>
                            <div class="list" id="recent-reviews">
                                <div class="list-item">
                                    <div class="list-item-info">
                                        <div class="list-item-name">Loading...</div>
                                        <div class="list-item-meta">Please wait</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <script>
        // Check authentication
        if (sessionStorage.getItem('isLoggedIn') !== 'true' || sessionStorage.getItem('userRole') !== 'patient') {
            window.location.href = 'login.php';
        }
        
        let currentPatientId = null;

        function getJSON(url){ return fetch(url).then(r=>r.json()); }

        // Load patient data
        async function loadPatientData() {
            const email = sessionStorage.getItem('userEmail');
            if (!email) return;
            try{
                const res = await getJSON(`api/users.php?search=${encodeURIComponent(email)}&page=1&limit=1`);
                if (res.success && res.data && res.data.length){
                    const u = res.data[0];
                    currentPatientId = u.id;
                    const full = `${u.first_name||''} ${u.last_name||''}`.trim();
                    const nameEl = document.getElementById('patient-name');
                    if (nameEl) nameEl.textContent = full || email;
                    const wt = document.getElementById('welcome-title');
                    if (wt) wt.textContent = `Welcome back, ${full || 'Patient'}!`;
                    const idLabel = document.getElementById('patient-id-label');
                    if (idLabel) {
                        const formattedId = typeof u.id === 'number' ? `#${String(u.id).padStart(3,'0')}` : (u.patient_id ? `#${u.patient_id}` : '—');
                        idLabel.textContent = `Patient ID: ${formattedId}`;
                    }
                } else {
                    const nameEl = document.getElementById('patient-name');
                    if (nameEl) nameEl.textContent = email;
                    const idLabel = document.getElementById('patient-id-label');
                    if (idLabel) idLabel.textContent = 'Patient ID: —';
                }
            }catch(e){
                const nameEl = document.getElementById('patient-name');
                if (nameEl) nameEl.textContent = email;
                const idLabel = document.getElementById('patient-id-label');
                if (idLabel) idLabel.textContent = 'Patient ID: —';
            }
        }

async function fetchUnreadNotifications(){
    if (!currentPatientId) return;
    try{
        const res = await getJSON(`api/notifications.php?user_id=${currentPatientId}&unread=1`);
        if (res.success && res.data && res.data.length){
            const messages = res.data.map(n => `${n.title}\n${n.message}` + (n.link ? `\nLink: ${window.location.origin}/${n.link}` : ''));
            alert(messages.join('\n\n'));
            const ids = res.data.map(n=>n.id);
            await fetch('api/notifications.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ids })
            });
        }
    }catch(e){
        console.error('Notification fetch error', e);
    }
}

        function parseDateTime(d, t){
            try{ return new Date(`${d}T${(t||'00:00').substring(0,5)}:00`); }catch(_){ return null; }
        }

        const setTextContent = (id, value) => {
            const el = document.getElementById(id);
            if (el) el.textContent = String(value);
        };

        const escHtml = (value) => (value ?? '').toString().replace(/[&<>'"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[c]));

        let patientReviews = [];

        function getFavoriteDoctorIds(){
            const stores = [];
            try { stores.push(localStorage); } catch(_){}
            try { stores.push(sessionStorage); } catch(_){}
            const keys = ['favoriteDoctors','patientFavorites'];
            const seen = new Set();
            for (const store of stores){
                if (!store) continue;
                for (const key of keys){
                    try{
                        const raw = store.getItem(key);
                        if (!raw) continue;
                        const parsed = JSON.parse(raw);
                        if (Array.isArray(parsed)){
                            parsed.forEach(v => {
                                const num = Number(v);
                                if (!Number.isNaN(num)) seen.add(num);
                            });
                        } else if (parsed && typeof parsed === 'object'){
                            Object.values(parsed).forEach(v => {
                                const num = Number(v);
                                if (!Number.isNaN(num)) seen.add(num);
                            });
                        }
                    }catch(_){}
                }
            }
            return Array.from(seen);
        }

        function formatDate(dateStr){
            if (!dateStr) return '';
            try{
                const dt = new Date(dateStr);
                if (!Number.isNaN(dt.getTime())) return dt.toLocaleDateString();
            }catch(_){}
            return dateStr;
        }

        function formatTime(timeStr){
            if (!timeStr) return '';
            const trimmed = timeStr.substring(0,5);
            try{
                const dt = new Date(`1970-01-01T${trimmed}:00`);
                if (!Number.isNaN(dt.getTime())) return dt.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }catch(_){}
            return trimmed;
        }

        function getComputedStatus(appt, now){
            const dt = parseDateTime(appt.appt_date, appt.appt_time);
            const statusLower = (appt.status || '').toLowerCase();
            if (statusLower === 'cancelled' || statusLower === 'rescheduled') return statusLower;
            if (!dt) return (appt.status || 'scheduled');
            if (dt < now) return 'completed';
            return appt.status || 'scheduled';
        }

        function renderUpcomingAppointments(appointments){
            const container = document.getElementById('upcoming-appointments');
            if (!container) return;
            container.innerHTML = '';

            if (!Array.isArray(appointments) || appointments.length === 0){
                container.innerHTML = '<div class="list-item"><div class="list-item-info"><div class="list-item-name">No upcoming appointments</div><div class="list-item-meta">Schedule your next visit.</div></div></div>';
                return;
            }

            const now = new Date();
            const mapped = appointments.map(appt => ({
                appt,
                dt: parseDateTime(appt.appt_date, appt.appt_time)
            }));
            const future = mapped.filter(item => {
                const statusLower = (item.appt.status || '').toLowerCase();
                return item.dt && item.dt >= now && statusLower !== 'cancelled' && statusLower !== 'rescheduled';
            });
            const baseList = future.length ? future : mapped;
            const list = baseList
                .slice()
                .sort((a,b) => {
                    const da = a.dt ? a.dt.getTime() : Number.POSITIVE_INFINITY;
                    const db = b.dt ? b.dt.getTime() : Number.POSITIVE_INFINITY;
                    return da - db;
                })
                .slice(0,4);

            if (!list.length){
                container.innerHTML = '<div class="list-item"><div class="list-item-info"><div class="list-item-name">No upcoming appointments</div><div class="list-item-meta">Schedule your next visit.</div></div></div>';
                return;
            }

            list.forEach(({appt, dt}) => {
                const rawStatus = getComputedStatus(appt, now);
                const normalized = (rawStatus || '').toLowerCase();
                const knownStatuses = ['scheduled','completed','cancelled','confirmed','active','rescheduled'];
                const statusClass = knownStatuses.includes(normalized) ? normalized : 'scheduled';
                const statusLabel = rawStatus ? `${rawStatus.charAt(0).toUpperCase()}${rawStatus.slice(1)}` : 'Scheduled';
                const doctorName = escHtml(appt.doctor_name || `Doctor #${appt.doctor_id || '—'}`);
                const dateLabel = dt ? formatDate(appt.appt_date) : 'Date TBD';
                const timeLabel = dt && appt.appt_time ? formatTime(appt.appt_time) : '';
                const item = document.createElement('div');
                item.className = 'list-item';
                item.innerHTML = `
                    <div class="list-item-info">
                        <div class="list-item-name">${doctorName}</div>
                        <div class="list-item-meta">${escHtml(dateLabel)}${timeLabel ? ` • ${escHtml(timeLabel)}` : ''}</div>
                    </div>
                    <span class="pill ${escHtml(statusClass)}">${escHtml(statusLabel)}</span>
                `;
                container.appendChild(item);
            });
        }

        function renderRecentReviews(errorMessage){
            const container = document.getElementById('recent-reviews');
            if (!container) return;
            container.innerHTML = '';

            if (errorMessage){
                container.innerHTML = `<div class="list-item"><div class="list-item-info"><div class="list-item-name">${escHtml(errorMessage)}</div></div></div>`;
                return;
            }

            if (!patientReviews.length){
                container.innerHTML = '<div class="list-item"><div class="list-item-info"><div class="list-item-name">No reviews found</div><div class="list-item-meta">Share your experience with your doctors.</div></div></div>';
                return;
            }

            patientReviews.slice(0,5).forEach(review => {
                const doctorRaw = (review && review.doctor_name) ? review.doctor_name : `Doctor #${(review && review.doctor_id) || '—'}`;
                const doctorName = escHtml(doctorRaw);
                const ratingValue = (review && typeof review.rating === 'number') ? Math.max(0, Math.min(5, review.rating)) : null;
                const ratingDisplay = ratingValue !== null ? `${ratingValue.toFixed(1)}/5` : 'No rating';
                const reviewDate = (review && review.created_at) ? new Date(review.created_at).toLocaleDateString() : '';
                const comment = (review && review.comment) ? escHtml(review.comment) : '';
                const item = document.createElement('div');
                item.className = 'list-item';
                item.innerHTML = `
                    <div class="list-item-info">
                        <div class="list-item-name">${doctorName}</div>
                        <div class="list-item-meta">${escHtml(reviewDate)}${comment ? ` • ${comment}` : ''}</div>
                    </div>
                    <span class="pill completed">⭐ ${escHtml(ratingDisplay)}</span>
                `;
                container.appendChild(item);
            });
        }

        function updateReviewedStat(){
            const reviewedCount = new Set(
                patientReviews
                    .filter(r => r && typeof r.doctor_id !== 'undefined' && r.doctor_id !== null)
                    .map(r => r.doctor_id)
            ).size;
            setTextContent('stat-reviewed', reviewedCount);
        }

        async function loadRecentReviews(){
            if (!currentPatientId) return;
            const container = document.getElementById('recent-reviews');
            if (container){
                container.innerHTML = '<div class="list-item"><div class="list-item-info"><div class="list-item-name">Loading reviews...</div><div class="list-item-meta">Please wait</div></div></div>';
            }
            try{
                const res = await getJSON(`api/reviews.php?patient_id=${currentPatientId}&limit=5`);
                if (res.success){
                    patientReviews = Array.isArray(res.data) ? res.data : [];
                    renderRecentReviews();
                }else{
                    patientReviews = [];
                    renderRecentReviews(res.message || 'Unable to load reviews.');
                }
            }catch(e){
                console.error('Failed to load reviews', e);
                patientReviews = [];
                renderRecentReviews('Unable to load reviews.');
            }
            updateReviewedStat();
        }

        async function loadDashboardData(){
            if (!currentPatientId) return;
            const container = document.getElementById('upcoming-appointments');
            if (container){
                container.innerHTML = '<div class="list-item"><div class="list-item-info"><div class="list-item-name">Loading appointments...</div><div class="list-item-meta">Please wait</div></div></div>';
            }
            try{
                const res = await getJSON(`api/appointments.php?patient_id=${currentPatientId}`);
                if (!res.success) throw new Error(res.message || 'Failed to load appointments');
                const rows = Array.isArray(res.data) ? res.data : [];
                const now = new Date();
                const total = rows.length;
                const upcomingList = rows.filter(a=>{
                    const dt = parseDateTime(a.appt_date, a.appt_time);
                    const computedStatus = getComputedStatus(a, now);
                    return dt && dt > now && computedStatus !== 'cancelled' && computedStatus !== 'completed';
                });

                setTextContent('stat-total', total);
                setTextContent('stat-upcoming', upcomingList.length);

                const uniqueDoctors = new Set(rows.map(a => a.doctor_id).filter(Boolean));
                const favorites = getFavoriteDoctorIds();
                const favoritesCount = favorites.length ? favorites.length : uniqueDoctors.size;
                setTextContent('stat-favorites', favoritesCount || 0);

                renderUpcomingAppointments(rows);
            }catch(e){
                console.error('Dashboard data load error', e);
                setTextContent('stat-total', 0);
                setTextContent('stat-upcoming', 0);
                setTextContent('stat-favorites', 0);
                const errContainer = document.getElementById('upcoming-appointments');
                if (errContainer){
                    errContainer.innerHTML = '<div class="list-item"><div class="list-item-info"><div class="list-item-name">Unable to load appointments</div><div class="list-item-meta">Please try again later.</div></div></div>';
                }
            }
            updateReviewedStat();
        }
        
        function logout() {
            sessionStorage.clear();
            window.location.href = 'login.php';
        }
        
        function confirmDeleteAccount() {
            if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
                deletePatientRecord();
            }
        }
        
        async function deletePatientRecord() {
            const email = sessionStorage.getItem('userEmail');
            if (!email) return;
            
            try {
                const response = await fetch('api/delete_patient.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email: email })
                });
                
                const result = await response.json();
                if (result.success) {
                    alert('Your account has been successfully deleted.');
                    logout();
                } else {
                    alert('Failed to delete account: ' + result.message);
                }
            } catch (error) {
                alert('An error occurred while deleting your account. Please try again later.');
                console.error('Delete error:', error);
            }
        }
        
        function viewPrescriptions() {
            alert('Prescriptions feature coming soon!');
        }
        
        function viewHealthSummary() {
            alert('Health Summary feature coming soon!');
        }
        
        function bookAppointment() {
            window.location.href = 'book-appointment.php';
        }
        
        function requestPrescription() {
            alert('Prescription request feature coming soon!');
        }
        
        function contactDoctor() {
            alert('Contact doctor feature coming soon!');
        }
        
        function updateProfile() {
            alert('Profile update feature coming soon!');
        }
        
        // Load data on page load
document.addEventListener('DOMContentLoaded', async ()=>{
    await loadPatientData();
    await loadRecentReviews();
    await loadDashboardData();
    await fetchUnreadNotifications();
});

        // Sidebar navigation: active link states and click handling
        document.addEventListener('DOMContentLoaded', () => {
            const links = Array.from(document.querySelectorAll('.nav-item[href^="#"]'));
            const sections = links
                .map(a => document.querySelector(a.getAttribute('href'))) 
                .filter(Boolean);

            function setActiveById(id){
                links.forEach(l => l.classList.toggle('active', l.getAttribute('href') === `#${id}`));
            }

            function onScroll(){
                let current = sections[0];
                const fromTop = window.scrollY + 100;
                for (const section of sections){
                    if (section.offsetTop <= fromTop) current = section;
                }
                if (current && current.id) setActiveById(current.id);
            }

            links.forEach(link => link.addEventListener('click', () => {
                const targetId = link.getAttribute('href').substring(1);
                setActiveById(targetId);
            }));

            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
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
            if (icon && text){
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

        // Initialize theme on load
        (function(){
            try{
                const savedTheme = localStorage.getItem('theme') || 'light';
                updateThemeToggle(savedTheme);
            }catch(_e){}
        })();
    </script>
</body>
</html>
