<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Doctor Dashboard</title>
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
        /* ============================================
           DOCTOR DASHBOARD STYLES
           Matching Patient Dashboard Layout & Design
           ============================================ */

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

        html { 
            scroll-behavior: smooth; 
        }

        html, body { 
            margin:0; 
            height:100%; 
            background: var(--gray-50); 
            color: var(--gray-900); 
            transition: background 0.3s, color 0.3s; 
        }

        body {
            font-family: var(--font-family);
        }

        /* Dashboard Container */
        .dashboard-container {
            min-height: 100vh;
            background: var(--gray-50);
        }

        /* Header Section - Matching Patient Portal */
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
        
        .header-left .logo { display:flex; align-items:center; }
        .header-left .logo img { display:block; height:36px; width:auto; }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: var(--spacing-3);
        }
        
        .theme-toggle { padding: 8px 14px; border-radius: 8px; border: 1px solid var(--gray-200); background: var(--white); color: var(--gray-700); cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .theme-toggle:hover { background: var(--gray-100); }
        
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

        /* Content Wrapper with Sidebar */
        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: var(--spacing-8) var(--spacing-6);
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: var(--spacing-6);
        }

        /* Left Sidebar */
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

        .nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-3);
            padding: 10px 12px;
            border-radius: var(--radius-lg);
            color: var(--gray-700);
            text-decoration: none;
            transition: background-color 0.2s ease, color 0.2s ease;
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
        
        /* Status badges and notifications */
        .status-confirmed,
        .status-completed {
            background: var(--gray-100);
            color: var(--gray-700);
        }

        .status-published {
            background: #10B981;
            color: var(--white);
        }

        .status-new {
            background: #FBBF24;
            color: var(--white);
        }

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
        
        /* Support for existing action card structure */
        .action-card-content { flex: 1; }
        .action-card-title { font-size: 1rem; font-weight: 600; color: var(--gray-900); margin-bottom: 0.5rem; }
        .action-card-desc { font-size: 0.875rem; color: var(--gray-600); margin-bottom: 1rem; line-height: 1.5; }
        .action-card-icon { width: 36px; height: 36px; border-radius: 10px; display: grid; place-items: center; font-size: 1.125rem; }
        .action-card-icon.blue { background: var(--light-blue); color: var(--primary-blue); }
        .action-card-icon.green { background: #dcfce7; color: #16a34a; }
        .action-card-icon.red { background: #fee2e2; color: #b91c1c; }

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
        
        /* List item styles for appointments and reviews */
        .list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .list-item-info {
            flex: 1;
        }

        .list-item-name {
            font-weight: 600;
            color: var(--gray-900);
        }

        .list-item-meta {
            color: var(--gray-500);
            font-size: var(--font-size-sm);
        }

        .list-item-status {
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: var(--font-size-xs);
            font-weight: 500;
        }

.logo-icon {
    width: 36px;
    height: 36px;
    border-radius: 12px;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 18px;
    font-weight: 700;
    box-shadow: 0 6px 14px rgba(37, 99, 235, 0.25);
}

.logo-text {
    font-weight: 800;
    font-size: 20px;
    color: #1d4ed8;
    letter-spacing: 0.5px;
}

        /* Responsive Design */
        @media (max-width: 900px) {
            .content-wrapper { grid-template-columns: 1fr; }
            .sidebar { position: static; }
        }
    </style>
</head>
<body>

    <div id="profileSetupOverlay" style="position:fixed; inset:0; background:rgba(15,23,42,0.55); backdrop-filter:blur(4px); display:none; align-items:center; justify-content:center; z-index:3000;">
        <div style="background:white; padding:32px; border-radius:24px; width:90%; max-width:420px; text-align:center; box-shadow:0 20px 60px rgba(15,23,42,0.35);">
            <div style="font-size:42px; margin-bottom:16px;">🛠️</div>
            <h2 style="margin:0 0 12px 0; color:#111827;">Complete Your Profile</h2>
            <p style="color:#6b7280; line-height:1.6; margin:0 0 24px 0;">
                Thanks for registering! Please complete your profile before accessing your dashboard.
            </p>
            <button class="btn primary" style="padding:12px 24px;" onclick="window.location.href='doctor-settings.php?setup=1'">
                Go to Doctor Settings
            </button>
        </div>
    </div>

    <div class="dashboard-container">
        <!-- Header -->
        <header class="dashboard-header">
            <div class="header-content">
                <div class="header-left">
                    <div class="logo" style="display:flex; align-items:center; gap:var(--spacing-2);">
                        <img src="public/assets/doxi-logo.svg?v=2" alt="DOXI" width="120" height="36" style="display:block;">
                        <span style="color: #2563eb; font-weight:700;">Doctor Portal</span>
                    </div>
        </div>
                <div class="user-info">
                    <button class="theme-toggle" id="theme-toggle" onclick="toggleTheme()">
                        <span id="theme-icon">🌙</span>
                        <span id="theme-text">Dark</span>
                    </button>
                    <div class="user-avatar" id="doctor-avatar">D</div>
                    <div class="user-details">
                        <h3 id="doctor-name">Dr. [Name]</h3>
                        <p id="doctor-id">Doctor ID: D001</p>
                    </div>
            <button class="logout-btn" onclick="logout()">Logout</button>
        </div>
    </div>
        </header>
        
        <!-- Main Content with Sidebar -->
        <div class="content-wrapper">
            <!-- Sidebar -->
            <aside class="sidebar" aria-label="Doctor navigation">
                <div class="sidebar-title">Navigation</div>
            <ul class="nav-list">
                    <li><a class="nav-item active" href="#dashboard"><span class="icon">🏠</span><span>Dashboard</span></a></li>
                    <li><a class="nav-item" href="doctor-appointment.php"><span class="icon">📅</span><span>Appointment</span></a></li>
                    <li><a class="nav-item" href="doctor-reviews.php"><span class="icon">⭐</span><span>Review & Rating</span></a></li>
                    <li><a class="nav-item" href="doctor-manage-availability.php"><span class="icon">🗓️</span><span>Availability</span></a></li>
                    <li><a class="nav-item" href="doctor-calendar.php"><span class="icon">📆</span><span>Calendar</span></a></li>
                    <li><a class="nav-item" href="doctor-settings.php"><span class="icon">⚙️</span><span>Settings / Profile</span></a></li>
            </ul>
        </aside>

            <main class="dashboard-main">
                <section id="dashboard" class="module-section" tabindex="-1">
                    <h1 class="dashboard-title" id="welcome-title">Welcome back!</h1>
                    <p class="dashboard-subtitle" id="welcome-subtitle">Here's an overview of your patients and today's appointments.</p>

                    <!-- Stats row -->
                    <div class="stats-grid" aria-label="Quick stats">
                        <div class="stat-card" onclick="window.location.href='doctor-appointment.php?filter=today'">
                            <div class="stat-card-row">
                                <div>
                                    <div class="stat-label">Today's Appointments</div>
                                    <div class="stat-value" id="today-appointments">0</div>
                                </div>
                                <div class="stat-icon icon-blue">📅</div>
                            </div>
                        </div>
                        <div class="stat-card" onclick="window.location.href='doctor-appointment.php?filter=all'">
                            <div class="stat-card-row">
                                <div>
                                    <div class="stat-label">Total Appointments</div>
                                    <div class="stat-value" id="total-appointments">0</div>
                                </div>
                                <div class="stat-icon icon-green">🩺</div>
                            </div>
                        </div>
                        <div class="stat-card" onclick="window.location.href='doctor-reviews.php'">
                            <div class="stat-card-row">
                                <div>
                                    <div class="stat-label">Reviews Received</div>
                                    <div class="stat-value" id="reviews-received">0</div>
                                </div>
                                <div class="stat-icon icon-yellow">⭐</div>
                            </div>
                        </div>
                        <div class="stat-card" onclick="navigateToPatients()">
                            <div class="stat-card-row">
                                <div>
                                    <div class="stat-label">Total Patients</div>
                                    <div class="stat-value" id="total-patients">0</div>
                                </div>
                                <div class="stat-icon icon-red">👥</div>
                            </div>
                        </div>
                    </div>

                    <!-- Action row -->
                    <div class="actions-grid" style="margin: var(--spacing-8) 0;">
                        <div class="action-card">
                            <div class="stat-card-row">
                                <div>
                                    <div style="font-weight:700; color: var(--gray-900);">Manage Availability</div>
                                    <div class="item-sub">Set your available slots for appointments</div>
                                </div>
                                <div class="stat-icon icon-blue">🗓️</div>
                            </div>
                            <div class="progress-track" aria-hidden="true"><div class="progress-bar"></div></div>
                            <button class="action-btn" onclick="navigateToAvailability()">Manage Now</button>
                        </div>
                        <div class="action-card">
                            <div class="stat-card-row">
                                <div>
                                    <div style="font-weight:700; color: var(--gray-900);">View Reviews</div>
                                    <div class="item-sub">Check patient feedback</div>
                                </div>
                                <div class="stat-icon icon-green">⭐</div>
                            </div>
                            <div class="progress-track" aria-hidden="true"><div class="progress-bar"></div></div>
                            <button class="action-btn" onclick="navigateToReviews()">View Now</button>
                        </div>
                        <div class="action-card">
                            <div class="stat-card-row">
                                <div>
                                    <div style="font-weight:700; color: var(--gray-900);">Calendar Overview</div>
                                    <div class="item-sub">Quick access to your schedule</div>
                                </div>
                                <div class="stat-icon icon-red">📆</div>
                            </div>
                            <div class="progress-track" aria-hidden="true"><div class="progress-bar"></div></div>
                            <button class="action-btn" onclick="navigateToCalendar()">Open Calendar</button>
                        </div>
                    </div>

                    <!-- Bottom Section (Two columns) -->
                    <div class="two-col-grid">
                        <!-- Recent Appointments -->
                        <div class="section-card">
                            <div style="font-weight:700; color: var(--gray-900); margin-bottom: var(--spacing-4);">Recent Appointments</div>
                            <div class="list" id="recent-appointments-list">
                                <!-- Dynamically loaded from API -->
                                <div class="list-item">
                                    <div class="list-item-info">
                                        <div class="list-item-name">Loading...</div>
                                        <div class="list-item-meta">Please wait</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Reviews -->
                        <div class="section-card">
                            <div style="font-weight:700; color: var(--gray-900); margin-bottom: var(--spacing-4);">Recent Reviews</div>
                            <div class="list" id="recent-reviews-list">
                                <!-- Dynamically loaded from API -->
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
        </main>
        </div>
    </div>

    <script>
        // ============================================
        // DOCTOR DASHBOARD JAVASCRIPT
        // Real-time data loading from API
        // ============================================

        // Global variables for doctor information and data refresh
        let doctorId = null;
        let refreshInterval = null;

        /**
         * Authentication Check
         * Verifies if doctor is logged in, redirects to login if not
         */
        function checkAuthentication() {
            const isLoggedIn = sessionStorage.getItem('isLoggedIn');
            const userRole = sessionStorage.getItem('userRole');
            const userId = sessionStorage.getItem('userId');
            
            // Check if user is logged in and is a doctor
            if (isLoggedIn !== 'true' || userRole !== 'doctor') {
                sessionStorage.clear();
                window.location.href = 'login.php';
                return false;
            }
            
            // Set doctor ID from session
            doctorId = userId ? parseInt(userId) : null;
            
            // If no doctor ID, try to get it from email
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
                    updateDoctorName(result.data[0]);
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
         * Update Doctor Name in Header and Dashboard
         * Updates the header name, ID, avatar, and welcome message
         * Also saves doctor data to localStorage for persistence after page refresh
         */
        function updateDoctorName(doctor) {
            const fullName = `${doctor.first_name || ''} ${doctor.last_name || ''}`.trim();
            const doctorTitle = fullName ? `Dr. ${fullName}` : 'Dr. [Name]';
            const doctorIdText = `Doctor ID: D${String(doctor.id || '001').padStart(3, '0')}`;
            const welcomeTitle = fullName ? `Welcome back, ${doctorTitle}!` : 'Welcome back!';
            
            // Save doctor data to localStorage for persistence after page refresh
            const doctorData = {
                id: doctor.id || null,
                first_name: doctor.first_name || '',
                last_name: doctor.last_name || '',
                email: doctor.email || '',
                fullName: fullName,
                doctorTitle: doctorTitle,
                doctorIdText: doctorIdText,
                welcomeTitle: welcomeTitle,
                avatarInitial: (doctor.first_name || 'D').charAt(0).toUpperCase()
            };
            localStorage.setItem('doctorData', JSON.stringify(doctorData));
            
            // Update header
            const doctorNameElement = document.getElementById('doctor-name');
            const doctorIdElement = document.getElementById('doctor-id');
            const doctorAvatarElement = document.getElementById('doctor-avatar');
            
            if (doctorNameElement) {
                doctorNameElement.textContent = doctorTitle;
            }
            if (doctorIdElement) {
                doctorIdElement.textContent = doctorIdText;
            }
            if (doctorAvatarElement && fullName) {
                // Set avatar to first letter of first name
                doctorAvatarElement.textContent = (doctor.first_name || 'D').charAt(0).toUpperCase();
            }
            
            // Update dashboard welcome message
            const welcomeTitleElement = document.getElementById('welcome-title');
            if (welcomeTitleElement) {
                welcomeTitleElement.textContent = welcomeTitle;
            }
        }

        /**
         * Load Doctor Data from LocalStorage
         * Restores doctor name and ID from localStorage for immediate display after page refresh
         */
        function loadDoctorDataFromStorage() {
            try {
                const savedDoctorData = localStorage.getItem('doctorData');
                if (savedDoctorData) {
                    const doctorData = JSON.parse(savedDoctorData);
                    
                    // Update header immediately
                    const doctorNameElement = document.getElementById('doctor-name');
                    const doctorIdElement = document.getElementById('doctor-id');
                    const doctorAvatarElement = document.getElementById('doctor-avatar');
                    
                    if (doctorNameElement && doctorData.doctorTitle) {
                        doctorNameElement.textContent = doctorData.doctorTitle;
                    }
                    if (doctorIdElement && doctorData.doctorIdText) {
                        doctorIdElement.textContent = doctorData.doctorIdText;
                    }
                    if (doctorAvatarElement && doctorData.avatarInitial) {
                        doctorAvatarElement.textContent = doctorData.avatarInitial;
                    }
                    
                    // Update dashboard welcome message
                    const welcomeTitleElement = document.getElementById('welcome-title');
                    if (welcomeTitleElement && doctorData.welcomeTitle) {
                        welcomeTitleElement.textContent = doctorData.welcomeTitle;
                    }
                    
                    return doctorData;
                }
            } catch (error) {
                console.error('Error loading doctor data from localStorage:', error);
            }
            return null;
        }

        /**
         * Logout Function
         * Clears all session data and redirects to login page
         * Allows doctor to log in or register again after logout
         */
        function logout() {
            // Clear session storage
            sessionStorage.clear();
            
            // Clear profile completion flags from localStorage
            localStorage.removeItem('doctorProfileCompleted');
            localStorage.removeItem('doctorProfileData');
            
            // Clear doctor data from localStorage
            localStorage.removeItem('doctorData');
            
            // Clear refresh interval if exists
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
            
            // Redirect to login page
            window.location.href = 'login.php';
        }

        /**
         * Load Dashboard Statistics
         * Fetches and updates all dashboard stat cards from API
         */
        async function loadDashboardStats() {
            if (!doctorId) {
                console.error('Doctor ID not available');
                return;
            }

            try {
                // Get today's date in YYYY-MM-DD format
                const today = new Date().toISOString().split('T')[0];
                
                // Fetch today's appointments
                const todayAppointmentsRes = await fetch(`api/appointments.php?doctor_id=${doctorId}&date=${today}`);
                const todayAppointmentsData = await todayAppointmentsRes.json();
                const todayCount = todayAppointmentsData.success && todayAppointmentsData.data 
                    ? todayAppointmentsData.data.length 
                    : 0;
                document.getElementById('today-appointments').textContent = todayCount;

                // Fetch total appointments
                const totalAppointmentsRes = await fetch(`api/appointments.php?doctor_id=${doctorId}`);
                const totalAppointmentsData = await totalAppointmentsRes.json();
                const totalCount = totalAppointmentsData.success && totalAppointmentsData.data 
                    ? totalAppointmentsData.data.length 
                    : 0;
                document.getElementById('total-appointments').textContent = totalCount;

                // Fetch reviews received
                const reviewsRes = await fetch(`api/reviews.php?doctor_id=${doctorId}`);
                const reviewsData = await reviewsRes.json();
                const reviewsCount = reviewsData.success && reviewsData.data 
                    ? reviewsData.data.length 
                    : 0;
                document.getElementById('reviews-received').textContent = reviewsCount;

                // Calculate unique patients from appointments
                const uniquePatients = new Set();
                if (totalAppointmentsData.success && totalAppointmentsData.data) {
                    totalAppointmentsData.data.forEach(apt => {
                        if (apt.patient_id) {
                            uniquePatients.add(apt.patient_id);
                        }
                    });
                }
                document.getElementById('total-patients').textContent = uniquePatients.size;

            } catch (error) {
                console.error('Error loading dashboard stats:', error);
                // Set default values on error
                document.getElementById('today-appointments').textContent = '0';
                document.getElementById('total-appointments').textContent = '0';
                document.getElementById('reviews-received').textContent = '0';
                document.getElementById('total-patients').textContent = '0';
            }
        }

        /**
         * Load Recent Appointments
         * Fetches and displays the most recent appointments
         */
        async function loadRecentAppointments() {
            if (!doctorId) {
                document.getElementById('recent-appointments-list').innerHTML = 
                    '<div class="list-item"><div class="list-item-info"><div class="list-item-name">Doctor ID not available</div></div></div>';
                return;
            }

            try {
                const response = await fetch(`api/appointments.php?doctor_id=${doctorId}`);
                const result = await response.json();
                
                const appointmentsList = document.getElementById('recent-appointments-list');
                
                if (result.success && result.data && result.data.length > 0) {
                    // Sort by date (most recent first) and take top 3
                    const sortedAppointments = result.data
                        .sort((a, b) => {
                            const dateA = new Date(`${a.appt_date} ${a.appt_time || '00:00:00'}`);
                            const dateB = new Date(`${b.appt_date} ${b.appt_time || '00:00:00'}`);
                            return dateB - dateA;
                        })
                        .slice(0, 3);
                    
                    if (sortedAppointments.length === 0) {
                        appointmentsList.innerHTML = 
                            '<div class="list-item"><div class="list-item-info"><div class="list-item-name">No appointments found</div></div></div>';
                        return;
                    }
                    
                    // Build HTML for appointments list
                    appointmentsList.innerHTML = sortedAppointments.map(apt => {
                        const patientName = apt.patient_name || 'Unknown Patient';
                        const appointmentDate = apt.appt_date ? formatDate(apt.appt_date) : 'N/A';
                        const status = apt.status || 'scheduled';
                        const statusClass = getStatusClass(status);
                        const statusText = status.charAt(0).toUpperCase() + status.slice(1);
                        
                        return `
                            <div class="list-item">
                                <div class="list-item-info">
                                    <div class="list-item-name">${patientName}</div>
                                    <div class="list-item-meta">${appointmentDate}</div>
                                </div>
                                <span class="list-item-status ${statusClass}">${statusText}</span>
                            </div>
                        `;
                    }).join('');
                } else {
                    appointmentsList.innerHTML = 
                        '<div class="list-item"><div class="list-item-info"><div class="list-item-name">No appointments found</div></div></div>';
                }
            } catch (error) {
                console.error('Error loading recent appointments:', error);
                document.getElementById('recent-appointments-list').innerHTML = 
                    '<div class="list-item"><div class="list-item-info"><div class="list-item-name">Error loading appointments</div></div></div>';
            }
        }

        /**
         * Load Recent Reviews
         * Fetches and displays the most recent patient reviews
         */
        async function loadRecentReviews() {
            if (!doctorId) {
                document.getElementById('recent-reviews-list').innerHTML = 
                    '<div class="list-item"><div class="list-item-info"><div class="list-item-name">Doctor ID not available</div></div></div>';
                return;
            }

            try {
                const response = await fetch(`api/reviews.php?doctor_id=${doctorId}&limit=3`);
                const result = await response.json();
                
                const reviewsList = document.getElementById('recent-reviews-list');
                
                if (result.success && result.data && result.data.length > 0) {
                    // Sort by created_at (most recent first) and take top 3
                    const sortedReviews = result.data
                        .sort((a, b) => {
                            const dateA = new Date(a.created_at || 0);
                            const dateB = new Date(b.created_at || 0);
                            return dateB - dateA;
                        })
                        .slice(0, 3);
                    
                    // Build HTML for reviews list
                    reviewsList.innerHTML = sortedReviews.map(review => {
                        const patientName = review.patient_name || 'Anonymous';
                        const rating = review.rating || 0;
                        const status = review.status || 'published';
                        const statusClass = status === 'published' ? 'status-published' : 'status-new';
                        const statusText = status === 'published' ? 'published' : 'new';
                        
                        return `
                            <div class="list-item">
                                <div class="list-item-info">
                                    <div class="list-item-name">${patientName}</div>
                                    <div class="list-item-meta">Rating: ${rating}/5</div>
                                </div>
                                <span class="list-item-status ${statusClass}">${statusText}</span>
                            </div>
                        `;
                    }).join('');
                } else {
                    reviewsList.innerHTML = 
                        '<div class="list-item"><div class="list-item-info"><div class="list-item-name">No reviews found</div></div></div>';
                }
            } catch (error) {
                console.error('Error loading recent reviews:', error);
                document.getElementById('recent-reviews-list').innerHTML = 
                    '<div class="list-item"><div class="list-item-info"><div class="list-item-name">Error loading reviews</div></div></div>';
            }
        }

        /**
         * Format Date
         * Converts YYYY-MM-DD to MM/DD/YYYY format
         */
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            try {
                const date = new Date(dateString);
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const day = date.getDate().toString().padStart(2, '0');
                const year = date.getFullYear();
                return `${month}/${day}/${year}`;
            } catch (error) {
                return dateString;
            }
        }

        /**
         * Get Status Class
         * Returns appropriate CSS class for appointment status
         */
        function getStatusClass(status) {
            const statusLower = status.toLowerCase();
            if (statusLower === 'completed') return 'status-completed';
            if (statusLower === 'confirmed' || statusLower === 'scheduled') return 'status-confirmed';
            if (statusLower === 'cancelled') return 'status-cancelled';
            return 'status-confirmed';
        }

        /**
         * Navigation Functions
         * Redirect to respective modules when buttons are clicked
         */
        function navigateToAvailability() {
            window.location.href = 'doctor-manage-availability.php';
        }

        function navigateToReviews() {
            window.location.href = 'doctor-reviews.php';
        }

        function navigateToCalendar() {
            window.location.href = 'doctor-calendar.php';
        }

        function navigateToPatients() {
            window.location.href = 'doctor-patients.php';
        }

        /**
         * Refresh Dashboard Data
         * Reloads all dashboard data from API
         */
        async function refreshDashboard() {
            await loadDashboardStats();
            await loadRecentAppointments();
            await loadRecentReviews();
        }

        /**
         * Check Profile Completion
         * Checks if doctor profile is complete and redirects if not
         * Only checks and redirects if NOT on the settings page itself
         * Also checks sessionStorage flag for already completed profiles
         */
        async function checkProfileCompletion() {
            // Skip check if already on settings page (where you complete profile)
            const currentPage = window.location.pathname.split('/').pop();
            if (currentPage === 'doctor-settings.php') {
                return true; // Always allow access to settings page
            }

            // Check both sessionStorage and localStorage for profile completion flag
            const profileCompleteFlag = sessionStorage.getItem('profileComplete') || localStorage.getItem('doctorProfileCompleted');
            
            // If flag is set to 'true', trust it immediately (especially after redirect from settings page)
            // This prevents the alert from appearing right after profile save and redirect
            if (profileCompleteFlag === 'true') {
                // Ensure both flags are set for consistency
                sessionStorage.setItem('profileComplete', 'true');
                localStorage.setItem('doctorProfileCompleted', 'true');
                console.log('✅ Profile completion flag found. Allowing dashboard access.');
                return true; // Profile is complete, allow access immediately
            }

            if (!doctorId) return true;
            
            try {
                // Fetch fresh data from API (don't use cached data)
                const response = await fetch(`api/users.php?id=${doctorId}`);
                const result = await response.json();
                
                if (result.success && result.data) {
                    const doctor = result.data;
                    
                    // Required fields
                    const requiredFields = {
                        'first_name': doctor.first_name,
                        'last_name': doctor.last_name,
                        'email': doctor.email,
                        'gender': doctor.gender,
                        'specialty': doctor.specialty,
                        'license_number': doctor.license_number,
                        'years_experience': doctor.years_experience
                    };

                    // Check if all required fields are filled
                    const incompleteFields = Object.keys(requiredFields).filter(key => {
                        const value = requiredFields[key];
                        if (!value) return true;
                        if (typeof value === 'string' && value.trim() === '') return true;
                        if (key === 'years_experience' && (value === null || value === '' || value === undefined || value === 0)) return true;
                        return false;
                    });

                    if (incompleteFields.length > 0) {
                        // Profile incomplete - redirect to settings
                        // Only show alert if not already on settings page
                        if (currentPage !== 'doctor-settings.php') {
                            alert('Please complete your profile before accessing the dashboard.');
                            window.location.href = 'doctor-settings.php';
                            return false;
                        }
                    } else {
                        // Profile is complete - set flags to skip future checks
                        sessionStorage.setItem('profileComplete', 'true');
                        localStorage.setItem('doctorProfileCompleted', 'true'); // Persistent flag
                    }
                }
                
                return true;
            } catch (error) {
                console.error('Error checking profile completion:', error);
                return true; // Allow access on error
            }
        }

        /**
         * Theme Toggle Functionality
         * Handles dark/light mode switching
         */
        function applyTheme(theme) {
            const t = (theme === 'dark') ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', t);
            try {
                localStorage.setItem('theme', t);
            } catch(_e) {}
            updateThemeToggle(t);
        }

        function updateThemeToggle(theme) {
            const icon = document.getElementById('theme-icon');
            const text = document.getElementById('theme-text');
            if (icon && text) {
                if (theme === 'dark') {
                    icon.textContent = '☀️';
                    text.textContent = 'Light';
                } else {
                    icon.textContent = '🌙';
                    text.textContent = 'Dark';
                }
            }
        }

        function toggleTheme() {
            const current = document.documentElement.getAttribute('data-theme') || 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            applyTheme(next);
        }

        /**
         * Initialize Dashboard
         * Sets up authentication, loads data, and starts auto-refresh
         */
        async function initializeDashboard() {
            // Initialize theme
            try {
                const savedTheme = localStorage.getItem('theme') || 'light';
                updateThemeToggle(savedTheme);
            } catch(_e) {}

            // Load doctor data from localStorage immediately for persistent display
            loadDoctorDataFromStorage();

            // Check authentication first
            if (!checkAuthentication()) {
                return;
            }

            // Load doctor data if needed
            const email = sessionStorage.getItem('userEmail');
            if (email && !doctorId) {
                await loadDoctorIdFromEmail(email);
            } else if (doctorId) {
                // If we have doctorId but no name displayed, fetch doctor data to update
                try {
                    const response = await fetch(`api/users.php?id=${doctorId}`);
                    const result = await response.json();
                    if (result.success && result.data) {
                        updateDoctorName(result.data);
                    }
                } catch (error) {
                    console.error('Error loading doctor data:', error);
                }
            }

            // Check profile completion - redirect if incomplete
            const profileComplete = await checkProfileCompletion();
            if (!profileComplete) {
                return; // Redirect already happened
            }

            // Load all dashboard data
            await refreshDashboard();

            // Set up auto-refresh every 30 seconds
            refreshInterval = setInterval(refreshDashboard, 30000);

            // Set up sidebar navigation active state
            setupSidebarNavigation();
        }

        /**
         * Setup Sidebar Navigation
         * Highlights the active navigation item based on current page
         */
        function setupSidebarNavigation() {
            const navItems = document.querySelectorAll('.nav-item');
            const currentPage = window.location.pathname.split('/').pop() || 'doctor-dashboard.php';
            
            // Remove active class from all items
            navItems.forEach(item => item.classList.remove('active'));
            
            // Set active based on current page
            navItems.forEach(item => {
                const href = item.getAttribute('href');
                if (href && (
                    (currentPage === 'doctor-dashboard.php' && href === '#dashboard') ||
                    (currentPage === 'doctor-appointment.php' && href.includes('appointment')) ||
                    (currentPage === 'doctor-reviews.php' && href.includes('reviews')) ||
                    (currentPage === 'doctor-manage-availability.php' && href.includes('availability')) ||
                    (currentPage === 'doctor-calendar.php' && href.includes('calendar')) ||
                    (currentPage === 'doctor-settings.php' && href.includes('settings'))
                )) {
                    item.classList.add('active');
                }
            });

            // Handle click events for sidebar navigation
            navItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    // If it's an internal link, update active state
                    if (this.getAttribute('href').startsWith('#')) {
                        e.preventDefault();
                        navItems.forEach(nav => nav.classList.remove('active'));
                        this.classList.add('active');
                        // Smooth scroll to section if exists
                        const targetId = this.getAttribute('href').substring(1);
                        const targetSection = document.getElementById(targetId);
                        if (targetSection) {
                            targetSection.scrollIntoView({ behavior: 'smooth' });
                        }
                    }
                });
            });
        }

        /**
         * Cleanup on Page Unload
         * Clears intervals when page is unloaded
         */
        window.addEventListener('beforeunload', function() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
        });

        // Initialize dashboard when DOM is ready
        document.addEventListener('DOMContentLoaded', initializeDashboard);
    </script>
</body>
</html>

