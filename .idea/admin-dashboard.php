<?php
$allowedSections = ['dashboard','patients','doctors','appointments','reviews','availability','analytics','logs','settings'];
$defaultSection = 'dashboard';
if (isset($forceSection) && in_array($forceSection, $allowedSections, true)) {
    $defaultSection = $forceSection;
} elseif (isset($_GET['section']) && in_array($_GET['section'], $allowedSections, true)) {
    $defaultSection = $_GET['section'];
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>DOXI - Admin Dashboard</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏥</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        window.__DEFAULT_SECTION__ = '<?php echo htmlspecialchars($defaultSection, ENT_QUOTES); ?>';
    </script>
    <style>
        :root{
            --blue: #2563eb;
            --blue-600: #1d4ed8;
            --blue-50: #eff6ff;
            --white: #ffffff;
            --gray-50:#f9fafb;
            --gray-100:#f3f4f6;
            --gray-200:#e5e7eb;
            --gray-300:#d1d5db;
            --gray-500:#6b7280;
            --gray-700:#374151;
            --gray-900:#111827;
            --radius: 12px;
        }
        /* Dark theme overrides */
        [data-theme="dark"]{
            --white: #0f172a;               /* base background */
            --gray-50:#0b1220;             /* page bg */
            --gray-100:#111827;
            --gray-200:#1f2937;            /* borders */
            --gray-300:#374151;
            --gray-500:#9ca3af;
            --gray-700:#d1d5db;
            --gray-900:#e5e7eb;            /* text high contrast */
            --blue-50:#0b2a4e;
        }
        html,body{margin:0;height:100%;background: var(--gray-50); color: var(--gray-900); font-family: Inter, system-ui, Arial, sans-serif}
        .layout{display:grid; grid-template-rows:auto 1fr; min-height:100vh}
        /* Header */
        .header{background: var(--white); border-bottom:1px solid var(--gray-200)}
        .header-inner{max-width:1200px; margin:0 auto; padding:14px 20px; display:flex; align-items:center; gap:14px}
        .brand{display:flex; align-items:center; gap:10px}
        .logo{width:38px; height:38px; border-radius:10px; background: transparent; display:flex; align-items:center; justify-content:center}
        .logo svg{width:38px;height:38px;display:block}
        .brand-title{font-weight:800; color: var(--blue)}
        .searchbar{flex:1; display:flex; align-items:center}
        .searchbar input{width:100%; padding:10px 12px; border:1px solid var(--gray-200); border-radius:10px; background:#fff}
        .userbar{display:flex; align-items:center; gap:12px}
        .avatar{width:36px;height:36px;border-radius:50%; background: var(--blue); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; cursor:pointer; transition:transform .15s ease;}
        .avatar:hover{transform:scale(1.05);}
        .btn{padding:8px 14px; border-radius:8px; border:1px solid var(--gray-200); background: var(--white); color: var(--gray-700); cursor:pointer; font-weight:600}
        .btn:hover{background: var(--gray-100)}
        .btn.primary{background: var(--blue); border-color: var(--blue); color:#fff}
        .btn.primary:hover{background: var(--blue-600); border-color: var(--blue-600)}
        /* Main */
        .main{display:grid; grid-template-columns:260px 1fr; max-width:1200px; width:100%; margin:0 auto}
        .sidebar{background: var(--white); border-right:1px solid var(--gray-200); padding:16px}
        .side-title{font-weight:700; color:var(--gray-700); margin-bottom:10px}
        .nav{display:flex; flex-direction:column; gap:6px}
        .nav a{display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:8px; color:var(--gray-700); text-decoration:none}
        .nav a:hover{background: var(--gray-100)}
        .nav a.active{background: var(--blue-50); color: var(--blue); font-weight:600}
        .content{padding:22px}
        .page-title{font-size:22px; font-weight:800; margin:0}
        .section-header{display:flex; justify-content:space-between; align-items:center; margin-bottom:12px}
        .back-to-dashboard{display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border-radius:8px; border:1px solid var(--gray-300); background:var(--white); color:var(--gray-700); cursor:pointer; font-weight:600; text-decoration:none; transition:all 0.2s; font-size:14px}
        .back-to-dashboard:hover{background:var(--gray-100); border-color:var(--gray-400); color:var(--gray-900)}
        .page-sub{margin:6px 0 16px; color: var(--gray-500)}
        /* KPI pills */
        .kpi-grid{display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:14px; margin: 14px 0 18px}
        .kpi{background: var(--white); border:1px solid var(--gray-200); border-radius: 14px; padding:14px; display:flex; align-items:center; gap:12px; cursor:pointer; transition:all 0.2s ease}
        .kpi:hover{transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,0.1); border-color:var(--blue-300)}
        .kpi .icon{width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; font-size:16px}
        .kpi .meta{display:flex; flex-direction:column}
        .kpi .lbl{color: var(--gray-500); font-weight:600; font-size:12px}
        .kpi .val{font-size:20px; font-weight:800; color: var(--gray-900)}
        .kpi.red .icon{background:#ef4444}
        .kpi.orange .icon{background:#f59e0b}
        .kpi.yellow .icon{background:#eab308}
        .kpi.green .icon{background:#10b981}
        .kpi.blue .icon{background:#3b82f6}
        /* Charts area */
        .charts-grid{display:grid; grid-template-columns: 2fr 1fr; gap:16px; margin-bottom:16px}
        @media(max-width: 900px){ .charts-grid{grid-template-columns: 1fr} }
        @media(max-width: 768px){ 
            #section-availability .filters{grid-template-columns: 1fr !important} 
            #section-availability .filters > div:last-child{grid-column: 1; justify-content:stretch;}
            #section-availability .filters > div:last-child button{flex:1;}
        }
        .chart-card{background: var(--white); border:1px solid var(--gray-200); border-radius: var(--radius); padding:16px}
        .chart-title{font-weight:700; margin-bottom:10px}
        .chart-placeholder{height:220px; background: linear-gradient(180deg, var(--blue-50), #fff); border:1px dashed var(--gray-200); border-radius:10px}
        /* Bottom row */
        .bottom-grid{display:grid; grid-template-columns: 2fr 1fr; gap:16px}
        @media(max-width: 900px){ .bottom-grid{grid-template-columns:1fr} }
        .review-item{display:flex; align-items:center; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--gray-200)}
        .review-item:last-child{border-bottom:none}
        .review-left{display:flex; align-items:center; gap:10px}
        .mini-avatar{width:30px; height:30px; border-radius:50%; background: var(--blue-50); display:flex; align-items:center; justify-content:center; color:var(--blue); font-weight:700}
        /* Cards / tables */
        .card{background: var(--white); border:1px solid var(--gray-200); border-radius: var(--radius); padding:16px}
        .table{width:100%; border-collapse:collapse}
        .table th, .table td{padding:10px 12px; border-bottom:1px solid var(--gray-200); text-align:left}
        .filters{display:flex; gap:8px; flex-wrap:wrap; margin-bottom:10px}
        .filters input, .filters select{padding:8px 10px; border:1px solid var(--gray-300); border-radius:8px}
        /* Sections */
        .section{display:none}
        .section.active{display:block}
        
        /* Validation Styles */
        #patientEditModal h2{
            color: var(--gray-900);
            font-weight: 700;
        }
        #patientEditModal label{
            display:block;
            margin-bottom:6px;
            font-weight:600;
            color: var(--gray-700);
        }
        #patientEditModal input,
        #patientEditModal select{
            width:100%;
            padding:10px 12px;
            border:1px solid var(--gray-300);
            border-radius:8px;
            background: var(--white);
            color: var(--gray-900);
            font-size:14px;
        }
        #patientEditModal input::placeholder{
            color: var(--gray-500);
        }
        #patientEditModal input.error, #patientEditModal select.error {
            border-color: #ef4444;
            background-color: #fef2f2;
        }
        
        #patientEditModal input.valid, #patientEditModal select.valid {
            border-color: #10b981;
        }
        
        #patientEditModal .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
        
        #patientEditModal .error-message.show {
            display: block;
        }
        
        /* Doctor Validation Styles */
        #doctorEditModal input.error, #doctorEditModal select.error {
            border-color: #ef4444;
            background-color: #fef2f2;
        }
        
        #doctorEditModal input.valid, #doctorEditModal select.valid {
            border-color: #10b981;
        }
        
        #doctorEditModal .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
        
        #doctorEditModal .error-message.show {
            display: block;
        }
        #doctorEditModal input.error, #doctorEditModal select.error {
            border-color: #ef4444;
            background-color: #fef2f2;
        }
        
        #doctorEditModal input.valid, #doctorEditModal select.valid {
            border-color: #10b981;
        }
        
        #doctorEditModal .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
        
        #doctorEditModal .error-message.show {
            display: block;
        }
        #patientEditModal {
            display:none;
            position:fixed;
            inset:0;
            background:rgba(15,23,42,0.45);
            z-index:1200;
            padding:40px 20px;
            overflow-y:auto;
        }

        #patientEditModal .patient-modal-card {
            width:100%;
            max-width:720px;
            margin:0 auto;
            background:var(--white);
            border-radius:24px;
            box-shadow:var(--shadow-lg);
            padding:32px 36px;
            display:flex;
            flex-direction:column;
            gap:24px;
        }

        #patientEditModal h2 {
            margin:0;
            font-size:var(--font-size-2xl);
            font-weight:700;
        }

        #patientEditModal p.modal-sub {
            margin:4px 0 16px;
            color:var(--gray-500);
            font-size:var(--font-size-sm);
        }

        .patient-form-grid {
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
            gap:18px 20px;
        }

        .patient-field label {display:block;font-weight:600;margin-bottom:6px;color:var(--gray-700);} 
        .patient-field input,
        .patient-field select,
        .patient-field textarea {width:100%;padding:10px 12px;border:1px solid var(--gray-300);border-radius:10px;font-size:var(--font-size-sm);} 

        .patient-modal-actions {display:flex;justify-content:flex-end;gap:12px;} 
        .patient-modal-actions .btn-secondary{background:var(--white);border:1px solid var(--gray-300);color:var(--gray-600);} 
        .patient-modal-actions .btn-secondary:hover{background:var(--gray-100);} 
        .modal-overlay {
            display:none;
            position:fixed;
            inset:0;
            background:rgba(17,24,39,0.4);
            z-index:1200;
            padding:40px 20px;
            overflow-y:auto;
            align-items:flex-start;
            justify-content:center;
        }

        .modal-panel {
            width:100%;
            max-width:660px;
            background:var(--white);
            border-radius:18px;
            box-shadow:var(--shadow-lg);
            padding:28px 30px 26px;
            display:flex;
            flex-direction:column;
            gap:16px;
        }

        .modal-panel h2 {margin:0;font-size:1.6rem;font-weight:700;color:var(--gray-900);} 

        .modal-form-grid {
            display:grid;
            grid-template-columns:repeat(2,minmax(220px,1fr));
            column-gap:40px;
            row-gap:16px;
        }

        .modal-field label {display:block;font-weight:600;margin-bottom:6px;color:var(--gray-700);} 
        .modal-field input,
        .modal-field select,
        .modal-field textarea {width:100%;padding:10px 12px;border:1px solid var(--gray-300);border-radius:8px;font-size:var(--font-size-sm);} 
        .modal-field textarea{min-height:70px;resize:vertical;}

        .modal-actions {display:flex;justify-content:flex-end;gap:10px;margin-top:8px;} 

        @media(max-width:720px){
            .modal-panel{padding:26px;}
            .modal-form-grid{grid-template-columns:1fr;}
        }

        .reviews-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px;}
        .review-card{border:1px solid var(--gray-200);border-radius:18px;padding:18px 20px;background:var(--gray-50);display:flex;flex-direction:column;gap:12px;box-shadow:var(--shadow-sm);} 
        .review-header{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;} 
        .review-doctor{font-weight:700;color:var(--gray-900);} 
        .review-meta{color:var(--gray-500);font-size:var(--font-size-xs);} 
        .review-rating{font-size:18px;display:flex;gap:4px;} 
        .review-star{color:var(--gray-300);} 
        .review-star.filled{color:#f59e0b;} 
        .review-comment{color:var(--gray-700);font-size:var(--font-size-sm);line-height:1.5;} 
        .review-actions{display:flex;gap:8px;} 
        .mini-btn{padding:6px 12px;border-radius:10px;border:1px solid var(--gray-300);background:var(--white);font-size:var(--font-size-xs);font-weight:600;cursor:pointer;} 
        .mini-btn:hover{background:var(--gray-100);} 
        .mini-btn.danger{border-color:#ef4444;color:#ef4444;} 

        .analytics-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px;margin-top:18px;}
        .analytics-card{border:1px solid var(--gray-200);border-radius:20px;padding:24px;background:var(--white);box-shadow:var(--shadow-sm);display:flex;flex-direction:column;gap:16px;}
        .analytics-card h3{margin:0;font-size:1.15rem;font-weight:700;color:var(--gray-900);} 
        .analytics-chart{width:100%;height:240px;}
        .analytics-chart svg text{font-family:'Inter', sans-serif;}
 
        .log-meta{margin-top:6px;font-size:var(--font-size-xs);color:var(--gray-500);display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:6px;}
        .log-meta span{background:var(--gray-50);border:1px solid var(--gray-200);border-radius:8px;padding:4px 8px;display:flex;flex-direction:column;}
        .log-meta span strong{color:var(--gray-600);} 
        .settings-password-card{margin-top:24px;padding:0;border-radius:24px;background:var(--white);border:1px solid var(--gray-200);box-shadow:var(--shadow-md);display:grid;grid-template-columns:minmax(240px,320px) 1fr;overflow:hidden;}
        .settings-password-card .settings-password-info{background:linear-gradient(135deg,var(--primary-blue) 0%,#4f46e5 100%);color:#fff;padding:32px;display:flex;flex-direction:column;gap:20px;justify-content:center;min-height:100%;}
        .settings-password-card .icon-pill{width:60px;height:60px;border-radius:20px;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:28px;color:#fff;box-shadow:none;}
        .settings-password-card h2{margin:0;font-size:26px;color:#fff;line-height:1.2;}
        .settings-password-card p{margin:0;color:rgba(255,255,255,0.85);line-height:1.6;}
        .settings-password-benefits{display:flex;flex-direction:column;gap:10px;}
        .settings-password-benefits span{display:flex;align-items:center;gap:10px;font-size:14px;color:rgba(255,255,255,0.85);}
        .settings-password-benefits span::before{content:'✔';display:inline-flex;width:22px;height:22px;border-radius:50%;background:rgba(255,255,255,0.15);color:#fff;align-items:center;justify-content:center;font-size:12px;}
        .settings-password-form{padding:32px;display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;background:var(--white);}
        .settings-password-form .field{display:flex;flex-direction:column;gap:8px;}
        .settings-password-form label{font-weight:600;color:var(--gray-700);}
        .settings-password-form input{padding:12px 14px;border-radius:12px;border:2px solid var(--gray-200);font-size:15px;transition:border-color .2s ease, box-shadow .2s ease;background:var(--white);}
        .settings-password-form input:focus{border-color:var(--primary-blue);box-shadow:0 0 0 3px rgba(37,99,235,0.2);outline:none;}
        .settings-password-actions{display:flex;align-items:center;gap:16px;margin-top:4px;grid-column:1/-1;}
        #changePasswordStatus{font-size:14px;}
        .settings-password-compact{margin-top:20px;padding:24px;border-radius:20px;border:1px solid var(--gray-200);box-shadow:var(--shadow-md);display:flex;flex-direction:column;gap:20px;max-width:760px;}
        .settings-password-compact .compact-header{display:flex;align-items:flex-start;gap:16px;}
        .settings-password-compact .compact-icon{width:46px;height:46px;border-radius:14px;background:rgba(37,99,235,0.12);display:flex;align-items:center;justify-content:center;font-size:22px;color:var(--primary-blue);}
        .settings-password-compact .compact-text h2{margin:0 0 6px 0;font-size:22px;color:var(--gray-900);}
        .settings-password-compact .compact-text p{margin:0;color:var(--gray-500);line-height:1.5;font-size:14px;}
        .settings-password-compact form{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;}
        .settings-password-compact .field{display:flex;flex-direction:column;gap:6px;}
        .settings-password-compact .field label{font-size:14px;font-weight:600;color:var(--gray-700);}
        .settings-password-compact .field input{padding:10px 12px;border-radius:10px;border:1.5px solid var(--gray-200);font-size:14px;transition:border-color .2s ease, box-shadow .2s ease;}
        .settings-password-compact .field input:focus{border-color:var(--primary-blue);box-shadow:0 0 0 2px rgba(37,99,235,0.18);outline:none;}
        .settings-password-compact .compact-actions{display:flex;align-items:center;gap:14px;grid-column:1/-1;}
        .settings-password-compact .compact-actions .btn{padding:10px 20px;font-size:14px;border-radius:10px;}
        .settings-password-compact .password-hints{grid-column:1/-1;display:flex;flex-wrap:wrap;gap:8px;font-size:13px;color:var(--gray-500);}
        .settings-password-compact .password-hints span{display:flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;background:var(--gray-100);}
        @media(max-width:640px){.settings-password-compact form{grid-template-columns:1fr;}}
        @media(max-width:920px){.settings-password-card{grid-template-columns:1fr;}.settings-password-form{padding:24px;}}
    </style>
</head>
<body>
    <div class="layout">
        <header class="header">
            <div class="header-inner">
                <div class="brand">
                    <div class="logo" aria-label="DOXI logo">
                        <img src="public/assets/doxi-logo.svg?v=2" alt="DOXI Logo" width="120" height="36">
                    </div>
                    <div class="brand-title"></div>
                </div>
                <div class="searchbar"><input placeholder="Search for anything" id="topSearch" /></div>
                <div class="userbar">
                    <div class="avatar" id="avatar">A</div>
                    <button class="btn" id="logoutBtn">Logout</button>
                </div>
            </div>
        </header>
        <div class="main">
            <aside class="sidebar">
                <div class="side-title">Navigation</div>
                <nav class="nav" id="sideNav">
                    <a href="admin-dashboard.php" data-target="dashboard" class="active">🏠 Dashboard</a>
                    <a href="patient-management.php" data-target="patients">👤 Patient Management</a>
                    <a href="doctor-management.php" data-target="doctors">🩺 Doctors</a>
                    <a href="appointments-management.php" data-target="appointments">📅 Appointments</a>
                    <a href="admin-reviews.php" data-target="reviews">⭐ Reviews</a>
                    <a href="availability-management.php" data-target="availability">🗓️ Availability</a>
                    <a href="admin-analytics.php" data-target="analytics">📊 Analytics</a>
                    <a href="system-logs.php" data-target="logs">📝 System Logs</a>
                    <a href="admin-settings.php" data-target="settings">⚙️ Settings</a>
                </nav>
            </aside>
            <main class="content">
                <!-- Dashboard -->
                <section id="section-dashboard" class="section active">
                    <h1 class="page-title">System Administration</h1>

                    <div class="kpi-grid">
                        <div class="kpi red" onclick="setSection('patients')" title="Click to view Patient Management"><div class="icon">🧑</div><div class="meta"><div class="lbl">Total Patients</div><div class="val" id="kpiPatients">0</div></div></div>
                        <div class="kpi yellow" onclick="setSection('doctors')" title="Click to view Doctors"><div class="icon">🧑‍⚕️</div><div class="meta"><div class="lbl">Staff Members</div><div class="val" id="kpiStaff">0</div></div></div>
                        <div class="kpi blue" onclick="setSection('appointments')" title="Click to view Appointments"><div class="icon">📅</div><div class="meta"><div class="lbl">Appointments</div><div class="val" id="kpiAppts">0</div></div></div>
                    </div>

                    <div class="charts-grid">
                        <div class="chart-card">
                            <div class="chart-title">Patient visit</div>
                            <div class="chart-placeholder" id="chartVisits"></div>
                        </div>
                        <div class="chart-card">
                            <div class="chart-title">Patients</div>
                            <div class="chart-placeholder" id="chartPatients"></div>
                        </div>
                    </div>

                    <div class="bottom-grid">
                        <div class="card">
                            <div class="chart-title">Appointment</div>
                            <table class="table" id="dashApptTable">
                                <thead><tr><th>Name</th><th>Gender</th><th>Date</th><th>Time</th><th>Action</th></tr></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="card" id="patientReviewCard">
                            <div class="chart-title">Patient Review</div>
                            <div id="patientReviewList">
                                <div class="review-item"><div class="review-left"><div class="mini-avatar">--</div><div><div style="font-weight:600">Loading...</div><div style="color:var(--gray-500); font-size:12px">Please wait</div></div></div><div style="color:var(--gray-500); font-size:12px">-</div></div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Patients -->
                <section id="section-patients" class="section">
                    <div class="section-header">
                        <div>
                            <h1 class="page-title">Patient Management</h1>
                            <div class="page-sub">Manage patient records</div>
                        </div>
                        <a href="#" class="back-to-dashboard" onclick="setSection('dashboard'); return false;">
                            ← Back to Dashboard
                        </a>
                    </div>
                    <div class="card">
                        <div class="filters">
                            <input placeholder="Search patients" id="searchPatients">
                            <select id="sortPatients"><option value="name">Sort by Name</option><option value="email">Email</option></select>
                            <button class="btn primary" id="addPatient">+ Add Patient</button>
                        </div>
                        <table class="table" id="patientsTable">
                            <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Last Login</th><th>Actions</th></tr></thead>
                            <tbody>
                                <tr><td colspan="5" style="text-align:center; padding:2rem; color:var(--gray-500);">Loading patients...</td></tr>
                            </tbody>
                        </table>
                        
                        <!-- Patient Records Modal -->
                        <div id="patientRecordsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
                            <div style="position: relative; width: 80%; max-width: 800px; margin: 50px auto; background: white; padding: 20px; border-radius: var(--radius);">
                                <h2>Patient Medical Records</h2>
                                <div id="patientRecordsContent">
                                    <h3>Medical History</h3>
                                    <ul>
                                        <li>Blood Type: A+</li>
                                        <li>Allergies: None</li>
                                        <li>Chronic Conditions: None</li>
                                    </ul>
                                    
                                    <h3>Recent Visits</h3>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Doctor</th>
                                                <th>Diagnosis</th>
                                                <th>Prescription</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>2023-05-15</td>
                                                <td>Dr. Smith</td>
                                                <td>Common Cold</td>
                                                <td>Paracetamol, Rest</td>
                                            </tr>
                                            <tr>
                                                <td>2023-03-10</td>
                                                <td>Dr. Johnson</td>
                                                <td>Annual Checkup</td>
                                                <td>None</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button class="btn primary" style="margin-top: 20px;" onclick="closePatientRecords()">Close</button>
                            </div>
                        </div>

                        <!-- Add/Edit Patient Modal -->
                        <div id="patientEditModal" class="modal-overlay">
                            <div class="modal-panel">
                                <h2 id="formTitle">Add Patient</h2>
                                <form id="patientForm" onsubmit="savePatientForm(event)">
                                    <input type="hidden" id="patientId" name="patientId">
                                    <div class="modal-form-grid">
                                        <div class="modal-field">
                                            <label>First Name</label>
                                            <input id="firstName" name="firstName" required>
                                            <div class="error-message" id="firstName-error" style="display:none;"></div>
                                        </div>
                                        <div class="modal-field">
                                            <label>Last Name</label>
                                            <input id="lastName" name="lastName" required>
                                            <div class="error-message" id="lastName-error" style="display:none;"></div>
                                        </div>
                                        <div class="modal-field">
                                            <label>Email</label>
                                            <input id="email" name="email" type="email" required>
                                            <div class="error-message" id="email-error" style="display:none;"></div>
                                        </div>
                                        <div class="modal-field">
                                            <label>Password</label>
                                            <input id="password" name="password" type="password" placeholder="Set only for new patient">
                                            <div class="error-message" id="password-error" style="display:none;"></div>
                                        </div>
                                        <div class="modal-field">
                                            <label>Phone</label>
                                            <input id="phone" name="phone">
                                            <div class="error-message" id="phone-error" style="display:none;"></div>
                                        </div>
                                        <div class="modal-field">
                                            <label>Date of Birth</label>
                                            <input id="dob" name="dob" type="date">
                                            <div class="error-message" id="dob-error" style="display:none;"></div>
                                        </div>
                                        <div class="modal-field">
                                            <label>Gender</label>
                                            <select id="gender" name="gender">
                                                <option value="">Select</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <div class="error-message" id="gender-error" style="display:none;"></div>
                                        </div>
                                        <div class="modal-field" style="grid-column:1 / -1;">
                                            <label>Address</label>
                                            <input id="address" name="address">
                                            <div class="error-message" id="address-error" style="display:none;"></div>
                                        </div>
                                    </div>
                                    <div class="modal-actions">
                                        <button type="button" class="btn" onclick="closePatientModal()">Cancel</button>
                                        <button type="submit" class="btn primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Doctors -->
                <section id="section-doctors" class="section">
                    <div class="section-header">
                        <div>
                            <h1 class="page-title">Doctor Management</h1>
                            <div class="page-sub">Manage doctors and specialties</div>
                        </div>
                        <a href="#" class="back-to-dashboard" onclick="setSection('dashboard'); return false;">
                            ← Back to Dashboard
                        </a>
                    </div>
                    <div class="card">
                        <div class="filters">
                            <input placeholder="Search doctors" id="searchDoctors">
                            <select id="sortDoctors"><option value="name">Sort by Name</option><option value="email">Email</option></select>
                            <button class="btn primary" id="addDoctor">+ Add Doctor</button>
                        </div>
                         <table class="table" id="doctorsTable">
                             <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Specialty</th><th>Actions</th></tr></thead>
                             <tbody></tbody>
                         </table>
                    </div>
                </section>

                <!-- Add/Edit Doctor Modal -->
                <div id="doctorEditModal" class="modal-overlay">
                    <div class="modal-panel">
                        <h2 id="doctorFormTitle">Add Doctor</h2>
                        <form id="doctorForm">
                            <input type="hidden" id="doctorId" name="doctorId">
                            <div class="modal-form-grid">
                                <div class="modal-field">
                                    <label>First Name</label>
                                    <input id="docFirstName" name="first_name" required>
                                    <div class="error-message" id="docFirstName-error" style="display:none;"></div>
                                </div>
                                <div class="modal-field">
                                    <label>Last Name</label>
                                    <input id="docLastName" name="last_name" required>
                                    <div class="error-message" id="docLastName-error" style="display:none;"></div>
                                </div>
                                <div class="modal-field">
                                    <label>Email</label>
                                    <input id="docEmail" name="email" type="email" required>
                                    <div class="error-message" id="docEmail-error" style="display:none;"></div>
                                </div>
                                <div class="modal-field">
                                    <label>Password</label>
                                    <input id="docPassword" name="password" type="password" placeholder="Set only for new doctor">
                                    <div class="error-message" id="docPassword-error" style="display:none;"></div>
                                </div>
                                <div class="modal-field">
                                    <label>Phone</label>
                                    <input id="docPhone" name="phone">
                                    <div class="error-message" id="docPhone-error" style="display:none;"></div>
                                </div>
                                <div class="modal-field">
                                    <label>Specialty</label>
                                    <select id="docSpecialty" name="specialty">
                                        <option value="">Select specialty</option>
                                        <option>General Medicine</option>
                                        <option>General Surgery</option>
                                        <option>Cardiology</option>
                                        <option>Dermatology</option>
                                        <option>Pediatrics</option>
                                        <option>Orthopedics</option>
                                        <option>Gynecology</option>
                                        <option>Neurology</option>
                                        <option>Psychiatry</option>
                                        <option>Oncology</option>
                                        <option>Radiology</option>
                                        <option>Anesthesiology</option>
                                        <option>Ophthalmology</option>
                                        <option>ENT</option>
                                        <option>Urology</option>
                                        <option>Nephrology</option>
                                        <option>Gastroenterology</option>
                                        <option>Endocrinology</option>
                                        <option>Pulmonology</option>
                                        <option>Rheumatology</option>
                                        <option>Dentistry</option>
                                        <option>Physiotherapy</option>
                                        <option>Emergency Medicine</option>
                                    </select>
                                    <div class="error-message" id="docSpecialty-error" style="display:none;"></div>
                                </div>
                                <div class="modal-field">
                                    <label>Gender</label>
                                    <select id="docGender" name="gender">
                                        <option value="">Select</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <div class="error-message" id="docGender-error" style="display:none;"></div>
                                </div>
                                <div class="modal-field" style="grid-column:1 / -1;">
                                    <label>Address</label>
                                    <input id="docAddress" name="address">
                                    <div class="error-message" id="docAddress-error" style="display:none;"></div>
                                </div>
                            </div>
                            <div class="modal-actions">
                                <button type="button" class="btn" onclick="closeDoctorModal()">Cancel</button>
                                <button type="submit" class="btn primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- View Doctor Modal -->
                <div id="doctorViewModal" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
                    <div style="position: relative; width: 90%; max-width: 680px; margin: 50px auto; background: white; padding: 20px; border-radius: var(--radius);">
                        <h2 style="margin-top:0">Doctor Details</h2>
                        <div id="doctorViewContent" style="padding: 8px 0; color:#374151"></div>
                        <div style="margin-top:16px; display:flex; gap:8px; justify-content:flex-end;">
                            <button type="button" class="btn" onclick="closeDoctorView()">Close</button>
                        </div>
                    </div>
                </div>

                <!-- Appointments -->
                <section id="section-appointments" class="section">
                    <div class="section-header">
                        <div>
                            <h1 class="page-title">Appointments</h1>
                            <div class="page-sub">View and manage appointments</div>
                        </div>
                        <a href="#" class="back-to-dashboard" onclick="setSection('dashboard'); return false;">
                            ← Back to Dashboard
                        </a>
                    </div>
                    <div class="card">
                        <div class="filters">
                            <input type="date" id="apptDate">
                            <select id="apptStatus"><option value="">All</option><option value="scheduled">scheduled</option><option value="rescheduled">rescheduled</option><option value="completed">completed</option><option value="cancelled">cancelled</option></select>
                            <button class="btn primary" id="addAppt">+ Add Appointment</button>
                        </div>
                        <table class="table" id="apptsTable">
                            <thead><tr><th>Date</th><th>Time</th><th>Patient</th><th>Doctor</th><th>Status</th><th>Actions</th></tr></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </section>

                <!-- Add/Edit Appointment Modal -->
                <div id="apptModal" class="modal-overlay">
                    <div class="modal-panel">
                        <h2 id="apptFormTitle">Add Appointment</h2>
                        <form id="apptForm">
                            <input type="hidden" name="apptId" id="apptId">
                            <div class="modal-form-grid">
                                <div class="modal-field">
                                    <label>Patient</label>
                                    <select id="apptPatient" name="patientId" required><option>Loading...</option></select>
                                </div>
                                <div class="modal-field">
                                    <label>Doctor</label>
                                    <select id="apptDoctor" name="doctorId" required><option>Loading...</option></select>
                                </div>
                                <div class="modal-field">
                                    <label>Date</label>
                                    <input type="date" id="apptDateInput" name="apptDate" required>
                                </div>
                                <div class="modal-field">
                                    <label>Time</label>
                                    <input type="time" id="apptTimeInput" name="apptTime" required>
                                </div>
                                <div class="modal-field">
                                    <label>Status</label>
                                    <select name="status" id="apptStatusInput">
                                        <option value="scheduled">scheduled</option>
                                        <option value="rescheduled">rescheduled</option>
                                        <option value="completed">completed</option>
                                        <option value="cancelled">cancelled</option>
                                    </select>
                                </div>
                                <div class="modal-field" style="grid-column:1 / -1;">
                                    <label>Notes</label>
                                    <textarea name="notes" id="apptNotes" placeholder="Optional"></textarea>
                                </div>
                            </div>
                            <div class="modal-actions">
                                <button type="button" class="btn" onclick="closeApptModal()">Cancel</button>
                                <button type="submit" class="btn primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- View Appointment Modal -->
                <div id="apptViewModal" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
                    <div style="position: relative; width: 90%; max-width: 720px; margin: 50px auto; background: white; padding: 20px; border-radius: var(--radius);">
                        <h2 style="margin-top:0">Appointment Details</h2>
                        <div id="apptViewContent" style="padding:16px">
                            <div>Loading appointment details...</div>
                        </div>
                        <div style="margin-top:16px; display:flex; gap:8px; justify-content:flex-end;">
                            <button type="button" class="btn" onclick="closeApptViewModal()">Close</button>
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                <section id="section-reviews" class="section">
                    <div class="section-header">
                        <div>
                            <h1 class="page-title">Reviews</h1>
                            <div class="page-sub">Latest feedback</div>
                        </div>
                        <a href="#" class="back-to-dashboard" onclick="setSection('dashboard'); return false;">
                            ← Back to Dashboard
                        </a>
                    </div>
                    <div class="card">
                        <table class="table" id="reviewsTable">
                            <thead><tr><th>Doctor</th><th>Rating</th><th>Comment</th><th>By</th><th>Date</th><th>Actions</th></tr></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </section>

                <!-- Availability -->
                <section id="section-availability" class="section">
                    <div class="section-header">
                        <div>
                            <h1 class="page-title">Doctor Availability</h1>
                            <div class="page-sub">View and manage doctor schedules and time slots</div>
                        </div>
                        <a href="#" class="back-to-dashboard" onclick="setSection('dashboard'); return false;">
                            ← Back to Dashboard
                        </a>
                    </div>
                    <div class="card">
                        <div class="filters" style="display:grid; grid-template-columns: 1.5fr 1fr auto; gap: 16px; margin-bottom: 16px; align-items:end;">
                            <div style="display:flex; flex-direction:column;">
                                <label style="display:block; font-weight:600; margin-bottom:8px; color:var(--gray-700); font-size:14px;">Doctor <span style="font-weight:400; color:var(--gray-500);">(Required)</span></label>
                                <select id="avDoctor" style="width:100%; padding:10px 12px; border:2px solid var(--gray-200); border-radius:8px; font-size:14px; background:var(--white); color:var(--gray-900);">
                                    <option value="" selected disabled>Select Doctor...</option>
                                </select>
                            </div>
                            <div style="display:flex; flex-direction:column;">
                                <label style="display:block; font-weight:600; margin-bottom:8px; color:var(--gray-700); font-size:14px;">Date</label>
                                <input type="date" id="avDate" style="width:100%; padding:10px 12px; border:2px solid var(--gray-200); border-radius:8px; font-size:14px; background:var(--white); color:var(--gray-900);">
                            </div>
                            <div style="display:flex; flex-direction:column; gap:8px; min-width:140px;">
                                <button class="btn primary" id="btnSearchAv" style="padding:10px 16px; font-size:14px; white-space:nowrap;">Search</button>
                                <button class="btn" id="btnResetAv" style="padding:10px 16px; font-size:14px; white-space:nowrap;">Reset</button>
                            </div>
                        </div>
                        <div style="display:flex; gap:16px; margin-top:16px; padding-top:16px; border-top:1px solid var(--gray-200); font-size:14px;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div style="width:20px; height:20px; border-radius:6px; background:#dcfce7; border:2px solid #86efac;"></div>
                                <span>Available</span>
                            </div>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div style="width:20px; height:20px; border-radius:6px; background:#fee2e2; border:2px solid #fca5a5;"></div>
                                <span>Booked</span>
                            </div>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div style="width:20px; height:20px; border-radius:6px; background:var(--gray-200); border:2px solid var(--gray-300);"></div>
                                <span>Past Time</span>
                            </div>
                        </div>
                    </div>
                    <div id="availabilityResults" style="margin-top:16px;"></div>
                </section>

                <!-- Edit Review Modal -->
                <!-- View Review Modal -->
                <div id="reviewViewModal" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
                    <div style="position: relative; width: 90%; max-width: 720px; margin: 50px auto; background: white; padding: 20px; border-radius: var(--radius);">
                        <h2 style="margin-top:0">Review Details</h2>
                        <div id="reviewViewContent" style="padding:16px">
                            <div>Loading review details...</div>
                        </div>
                        <div style="margin-top:16px; display:flex; gap:8px; justify-content:flex-end;">
                            <button type="button" class="btn" onclick="closeReviewViewModal()">Close</button>
                        </div>
                    </div>
                </div>

                <!-- Edit Review Modal -->
                <div id="reviewModal" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
                    <div style="position: relative; width: 90%; max-width: 560px; margin: 50px auto; background: white; padding: 20px; border-radius: var(--radius);">
                        <h2 style="margin-top:0">Edit Review</h2>
                        <form id="reviewForm">
                            <input type="hidden" id="reviewId">
                            <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                <div>
                                    <label>Rating</label>
                                    <select id="reviewRating">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
                                </div>
                                <div style="grid-column: 1 / span 2;">
                                    <label>Comment</label>
                                    <textarea id="reviewComment" style="width:100%; height:120px"></textarea>
                                </div>
                            </div>
                            <div style="margin-top:16px; display:flex; gap:8px; justify-content:flex-end;">
                                <button type="button" class="btn" onclick="closeReviewModal()">Cancel</button>
                                <button type="submit" class="btn primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Analytics -->
                <section id="section-analytics" class="section">
                    <div class="section-header">
                        <div>
                            <h1 class="page-title">Analytics</h1>
                            <div class="page-sub">Reports & metrics</div>
                        </div>
                        <a href="#" class="back-to-dashboard" onclick="setSection('dashboard'); return false;">
                            ← Back to Dashboard
                        </a>
                    </div>
                    <div class="kpi-grid">
                        <div class="kpi"><div class="lbl">Monthly Patients</div><div class="val">+12%</div></div>
                        <div class="kpi"><div class="lbl">Avg. Wait</div><div class="val">14m</div></div>
                        <div class="kpi"><div class="lbl">Satisfaction</div><div class="val">4.7/5</div></div>
                    </div>
                    <div class="analytics-grid">
                        <div class="analytics-card">
                            <h3>Appointments Trend</h3>
                            <p style="margin:0;color:var(--gray-500);font-size:var(--font-size-sm);">Last 6 months of scheduled appointments.</p>
                            <div class="analytics-chart" id="analyticsChart"></div>
                        </div>
                    </div>
                </section>

                <!-- Logs -->
                <section id="section-logs" class="section">
                    <div class="section-header">
                        <div>
                            <h1 class="page-title">System Logs</h1>
                            <div class="page-sub">Security & audit trail</div>
                        </div>
                        <a href="#" class="back-to-dashboard" onclick="setSection('dashboard'); return false;">
                            ← Back to Dashboard
                        </a>
                    </div>
                    <div class="card">
                        <div class="filters" style="margin-bottom:12px">
                            <select id="logLevel">
                                <option value="INFO">INFO</option>
                                <option value="WARN">WARN</option>
                                <option value="ERROR">ERROR</option>
                            </select>
                            <input id="logMessage" placeholder="Log message" style="flex:1">
                            <input id="logUserEmail" placeholder="User email (optional)">
                            <button class="btn primary" id="createLogBtn">+ Add Log</button>
                        </div>
                        <table class="table" id="logsTable">
                            <thead><tr><th>Time</th><th>Level</th><th>Message</th><th>User</th><th>Actions</th></tr></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </section>

                <!-- Settings -->
                <section id="section-settings" class="section">
                    <div class="section-header">
                        <div>
                            <h1 class="page-title">Settings</h1>
                            <div class="page-sub">System preferences</div>
                        </div>
                        <a href="#" class="back-to-dashboard" onclick="setSection('dashboard'); return false;">
                            ← Back to Dashboard
                        </a>
                    </div>
                    <div class="card">
                        <div class="filters" style="flex-wrap:wrap; gap:10px">
                            <label>Timezone
                                <select id="tz">
                                    <option value="UTC">UTC</option>
                                    <option value="Asia/Kolkata">India (IST)</option>
                                    <option value="Asia/Dubai">Asia/Dubai (GST)</option>
                                    <option value="Asia/Tokyo">Asia/Tokyo (JST)</option>
                                    <option value="Asia/Singapore">Asia/Singapore (SGT)</option>
                                    <option value="Europe/London">Europe/London (GMT/BST)</option>
                                    <option value="Europe/Berlin">Europe/Berlin (CET/CEST)</option>
                                    <option value="Africa/Johannesburg">Africa/Johannesburg (SAST)</option>
                                    <option value="America/New_York">America/New_York (ET)</option>
                                    <option value="America/Chicago">America/Chicago (CT)</option>
                                    <option value="America/Denver">America/Denver (MT)</option>
                                    <option value="America/Los_Angeles">America/Los_Angeles (PT)</option>
                                    <option value="America/Sao_Paulo">America/Sao_Paulo (BRT)</option>
                                    <option value="Australia/Sydney">Australia/Sydney (AET)</option>
                                </select>
                            </label>
                            <label>Theme
                                <select id="theme"><option value="light">Light</option><option value="dark">Dark</option></select>
                            </label>
                            <label>Language
                                <select id="lang"><option value="en">English</option></select>
                            </label>
                            <button class="btn primary" id="saveSettings">Save</button>
                            <div id="settingsStatus" style="color:#10b981; display:none">Saved</div>
                        </div>
                        <div class="card" style="margin-top:20px; border:1px solid var(--gray-200); border-radius:16px; padding:24px;">
                            <h2 style="margin:0 0 16px 0; font-size:20px; color:var(--gray-800);">Change Password</h2>
                            <p style="margin:0 0 20px 0; color:var(--gray-500);">Update the admin password to keep the account secure.</p>
                            <div class="settings-password-compact">
                                <div class="compact-header">
                                    <div class="compact-icon">🔒</div>
                                    <div class="compact-text">
                                        <h2>Change Password</h2>
                                        <p>Keep your administrator account secure by updating the password regularly. Make sure you choose something memorable but hard to guess.</p>
                                    </div>
                                </div>
                                <form id="changePasswordForm">
                                    <div class="field">
                                        <label for="currentPassword">Current Password</label>
                                        <input type="password" id="currentPassword" autocomplete="current-password" required>
                                    </div>
                                    <div class="field">
                                        <label for="newPassword">New Password</label>
                                        <input type="password" id="newPassword" autocomplete="new-password" minlength="6" required>
                                    </div>
                                    <div class="field">
                                        <label for="confirmPassword">Confirm New Password</label>
                                        <input type="password" id="confirmPassword" autocomplete="new-password" minlength="6" required>
                                    </div>
                                    <div class="password-hints">
                                        <span>• At least 6 characters</span>
                                        <span>• Mix of letters & numbers</span>
                                        <span>• Avoid using your old password</span>
                                    </div>
                                    <div class="compact-actions">
                                        <button type="submit" class="btn primary" id="changePasswordBtn">Update Password</button>
                                        <div id="changePasswordStatus" style="display:none; font-weight:600;"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                     </div>
                 </section>

            </main>
        </div>
    </div>

    <script>
        // Auth guard
        if (sessionStorage.getItem('isLoggedIn') !== 'true' || sessionStorage.getItem('userRole') !== 'admin') {
            window.location.href = 'admin-login.php';
        }

        // Theme from localStorage early
        (function(){
            try{
                const savedTheme = localStorage.getItem('theme');
                if (savedTheme){ document.documentElement.setAttribute('data-theme', savedTheme); }
            }catch(_e){}
        })();

        // Header user
        (function(){
            const email = sessionStorage.getItem('userEmail') || '';
            const avatar = document.getElementById('avatar');
            avatar.textContent = (email || 'A').charAt(0).toUpperCase();
        })();

        // Logout
        document.getElementById('logoutBtn').addEventListener('click', function(){
            sessionStorage.clear();
            window.location.href = 'admin-login.php';
        });
        
        // Patient Records Functions
        async function viewPatientRecords(patientId) {
            // Show the modal
            const modal = document.getElementById('patientRecordsModal');
            modal.style.display = 'block';
            const container = document.getElementById('patientRecordsContent');
            if (!container) return;
            container.innerHTML = '<div style="padding:12px">Loading records...</div>';
            try{
                const res = await fetch(`api/patients.php?id=${patientId}`);
                const json = await res.json();
                if (!json.success) throw new Error(json.message||'Failed');
                const p = json.data;
                const name = `${p.first_name||''} ${p.last_name||''}`.trim();
                const records = Array.isArray(p.medical_records) ? p.medical_records : [];
                container.innerHTML = `
                    <div style="padding:16px">
                        <h3 style="margin:0 0 8px 0">${name || p.email}</h3>
                        <div style="color:#64748b; margin-bottom:12px">Email: ${p.email||'-'} • Phone: ${p.phone||'-'} • DOB: ${p.date_of_birth||'-'}</div>
                        <h4 style="margin:8px 0">Medical Records (${records.length})</h4>
                        ${records.length ? `<table class="table"><thead><tr><th>Date</th><th>Type</th><th>Title</th></tr></thead><tbody>`+records.map(r=>`<tr><td>${r.created_at||'-'}</td><td>${r.record_type||'-'}</td><td>${r.title||'-'}</td></tr>`).join('')+`</tbody></table>` : '<div>No records found</div>'}
                    </div>
                `;
            }catch(e){
                container.innerHTML = '<div style="padding:12px;color:#ef4444">Failed to load records</div>';
            }
        }
        
        function closePatientRecords() {
            document.getElementById('patientRecordsModal').style.display = 'none';
        }
        
        async function editPatient(patientId) {
            try{
                const res = await fetch(`api/patients.php?id=${patientId}`);
                const json = await res.json();
                if(!json.success) throw new Error(json.message||'Failed');
                const p = json.data;
                openPatientModal('edit', p);
            }catch(e){ alert('Failed to load patient'); }
        }
        
        async function deletePatient(patientId) {
            if (!confirm(`Delete patient ${patientId}? This cannot be undone.`)) return;
            try{
                const res = await fetch(`api/patients.php?id=${patientId}`, { method:'DELETE' });
                const json = await res.json();
                if(json.success){ alert('Patient deleted'); renderPatients(); }
                else{ alert('Delete failed: '+(json.message||'Unknown')); }
            }catch(e){ alert('Delete error'); }
        }

        // Sidebar navigation
        const links = document.querySelectorAll('#sideNav a');
        const sectionPathMap = {
            dashboard: 'admin-dashboard.php',
            patients: 'patient-management.php',
            doctors: 'doctor-management.php',
            appointments: 'appointments-management.php',
            reviews: 'admin-reviews.php',
            availability: 'availability-management.php',
            analytics: 'admin-analytics.php',
            logs: 'system-logs.php',
            settings: 'admin-settings.php'
        };

        let adminProfileCache = null;
        let adminProfilePromise = null;

        async function ensureAdminProfile(){
            if (adminProfileCache && adminProfileCache.id) {
                return adminProfileCache;
            }
            if (adminProfilePromise) {
                return adminProfilePromise;
            }

            const email = sessionStorage.getItem('userEmail');
            if (!email) {
                return null;
            }

            adminProfilePromise = fetch(`api/users.php?role=admin&search=${encodeURIComponent(email)}&limit=1`)
                .then(res => res.json())
                .then(json => {
                    if (json && json.success && Array.isArray(json.data) && json.data.length) {
                        adminProfileCache = json.data[0];
                        return adminProfileCache;
                    }
                    return null;
                })
                .catch(err => {
                    console.error('Failed to load admin profile', err);
                    return null;
                })
                .finally(() => {
                    adminProfilePromise = null;
                });

            return adminProfilePromise;
        }

        function activateSection(target){
            document.querySelectorAll('.section').forEach(s=>{
                s.classList.remove('active');
                s.style.display = '';
            });

            const el = document.getElementById('section-' + target);
            if (el) {
                el.classList.add('active');
                el.style.display = '';
            }

            links.forEach(a=>a.classList.toggle('active', a.getAttribute('data-target')===target));

            if (target === 'patients') {
                setTimeout(() => {
                    const section = document.getElementById('section-patients');
                    if (section && section.classList.contains('active')) {
                        section.style.display = '';
                        renderPatients(true);
                    }
                }, 50);
            }
            if (target === 'doctors') {
                const tbody = document.querySelector('#doctorsTable tbody');
                if (tbody && (!tbody.innerHTML || tbody.innerHTML.includes('Loading') || tbody.innerHTML.includes('No doctors found') || tbody.innerHTML.trim() === '')) {
                    renderDoctors();
                }
            }
            if (target === 'appointments') {
                const tbody = document.querySelector('#apptsTable tbody');
                if (tbody && (!tbody.innerHTML || tbody.innerHTML.includes('Loading') || tbody.innerHTML.includes('No appointments found') || tbody.innerHTML.trim() === '')) {
                    renderAppts();
                }
            }
            if (target === 'logs') {
                const tbody = document.querySelector('#logsTable tbody');
                if (tbody && (!tbody.innerHTML || tbody.innerHTML.includes('Loading') || tbody.innerHTML.includes('No logs found') || tbody.innerHTML.trim() === '')) {
                    renderLogs();
                }
            }
            if (target === 'availability') {
                renderAvailability();
            }
            if (target === 'settings') {
                ensureAdminProfile();
            }
        }

        function setSection(target, skipNavigation = false, skipHash = false){
            if (!target) return;
            if (!skipNavigation){
                const basePath = window.location.pathname.replace(/[^/]*$/, '');
                const desired = basePath + (sectionPathMap[target] || sectionPathMap.dashboard);
                if (window.location.pathname !== desired){
                    window.location.href = desired;
                    return;
                }
            }

            activateSection(target);

            if (!skipHash){
                window.location.hash = target;
                storeActiveSection(target);
            }
        }

        document.getElementById('sideNav').addEventListener('click', function(e){
            const a = e.target.closest('a');
            if (!a) return;
            if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) {
                return; // allow default browser behavior for new tabs/windows
            }
            e.preventDefault();
            setSection(a.getAttribute('data-target'));
        });

        // Module grid clicks
        document.querySelectorAll('.module').forEach(m=>{
            m.addEventListener('click', ()=> setSection(m.getAttribute('data-go')));
        });

        // Live data helpers
        async function fetchSummary(role){
            const url = `api/users.php?role=${encodeURIComponent(role)}&page=1&limit=1`;
            const res = await fetch(url);
            const json = await res.json();
            if (!json.success) throw new Error(json.message || 'Failed');
            return json.pagination.total || 0;
        }
        async function fetchRecent(role, limit=8){
            const url = `api/users.php?role=${encodeURIComponent(role)}&page=1&limit=${limit}`;
            const res = await fetch(url);
            const json = await res.json();
            if (!json.success) throw new Error(json.message || 'Failed');
            return json.data || [];
        }

        // Demo data renderers (fallbacks)
        function renderKPIs(){
            document.getElementById('kpiPatients').textContent = '2,040';
            document.getElementById('kpiDoctors').textContent = '150';
            document.getElementById('kpiAppts').textContent = '225';
            const costs = document.getElementById('kpiCosts'); if(costs) costs.textContent = '2,600';
            const vehicles = document.getElementById('kpiVehicles'); if(vehicles) vehicles.textContent = '50';
        }
        function setKPIs(totals){
            if (typeof totals?.patients === 'number') {
                const el = document.getElementById('kpiPatients');
                if(el) el.textContent = totals.patients.toLocaleString();
            }
            if (typeof totals?.doctors === 'number') {
                const el = document.getElementById('kpiStaff');
                if(el) el.textContent = totals.doctors.toLocaleString();
            }
            if (typeof totals?.appointments === 'number') {
                const el = document.getElementById('kpiAppts');
                if(el) el.textContent = totals.appointments.toLocaleString();
            }
            const costs = document.getElementById('kpiCosts');
            if(costs) costs.textContent = (totals?.costs || 0).toLocaleString();
            const vehicles = document.getElementById('kpiVehicles');
            if(vehicles) vehicles.textContent = (totals?.vehicles || 0).toLocaleString();
        }
        // Cache for patients data
        let patientsDataCache = null;
        let isRenderingPatients = false;
        
        async function renderPatients(forceReload = false){
            try{
                const tbody = document.querySelector('#patientsTable tbody');
                if (!tbody) {
                    console.error('Patients table tbody not found');
                    return;
                }
                
                // Prevent multiple simultaneous renders
                if (isRenderingPatients && !forceReload) {
                    return;
                }
                
                // Check if section is active
                const section = document.getElementById('section-patients');
                const isActive = section && section.classList.contains('active');
                
                // Only prevent render if section is not active AND we have data AND we're not forcing reload
                if (!isActive && !forceReload && patientsDataCache && tbody.innerHTML && !tbody.innerHTML.includes('Loading') && !tbody.innerHTML.includes('No patients found') && !tbody.innerHTML.includes('Error')) {
                    // Keep existing data, don't reload
                    return;
                }
                
                isRenderingPatients = true;
                
                // Always show loading state when fetching
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:2rem; color:var(--gray-500);">Loading patients...</td></tr>';
                
                const q = document.getElementById('searchPatients')?.value || '';
                const res = await fetch('api/patients.php');
                
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                
                const json = await res.json();
                
                if (json.success){
                    let rows = Array.isArray(json.data) ? json.data : [];
                    
                    // Cache the raw data
                    patientsDataCache = rows;
                    
                    // Apply search filter if query exists
                    if (q){ 
                        const ql = q.toLowerCase(); 
                        rows = rows.filter(p => {
                            const fullName = `${(p.first_name||'')} ${(p.last_name||'')}`.toLowerCase();
                            const email = (p.email||'').toLowerCase();
                            const phone = (p.phone||'').toLowerCase();
                            return fullName.includes(ql) || email.includes(ql) || phone.includes(ql);
                        }); 
                    }
                    
                    // Sort rows
                    const sortSel = document.getElementById('sortPatients');
                    const sortBy = sortSel ? sortSel.value : 'name';
                    rows.sort((a,b)=>{
                        const nameA = (`${a.first_name||''} ${a.last_name||''}`).trim().toLowerCase();
                        const nameB = (`${b.first_name||''} ${b.last_name||''}`).trim().toLowerCase();
                        const emailA = (a.email||'').toLowerCase();
                        const emailB = (b.email||'').toLowerCase();
                        if (sortBy==='email') return emailA.localeCompare(emailB);
                        return nameA.localeCompare(nameB);
                    });
                    
                    // Render rows - always render
                    if (rows.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:2rem; color:var(--gray-500);">No patients found</td></tr>';
                    } else {
                        const html = rows.map(p => {
                            const fullName = `${(p.first_name||'')} ${(p.last_name||'')}`.trim() || 'Unknown';
                            const email = p.email || '-';
                            const phone = p.phone || '-';
                            const lastLogin = p.last_login ? new Date(p.last_login).toLocaleDateString() : '-';
                            return `<tr>
                                <td>${fullName}</td>
                                <td>${email}</td>
                                <td>${phone}</td>
                                <td>${lastLogin}</td>
                                <td>
                                    <button class="btn" onclick="viewPatientRecords(${p.id})">View</button>
                                    <button class="btn" onclick="editPatient(${p.id})">Edit</button>
                                    <button class="btn" onclick="deletePatient(${p.id})">Delete</button>
                                </td>
                            </tr>`;
                        }).join('');
                        // Directly set innerHTML to ensure data is rendered
                        tbody.innerHTML = html;
                        
                        // Ensure section stays active (but only if it should be)
                        const sectionCheck = document.getElementById('section-patients');
                        if (sectionCheck && sectionCheck.classList.contains('active')) {
                            // Just ensure it has the active class, let CSS handle display
                            sectionCheck.classList.add('active');
                            // Remove any inline display styles that might interfere
                            sectionCheck.style.display = '';
                        }
                    }
                } else { 
                    const errorMsg = json.message || 'Failed to load patients';
                    tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; padding:2rem; color:#ef4444;">${errorMsg}</td></tr>`; 
                }
                
                isRenderingPatients = false;
            } catch(e) { 
                isRenderingPatients = false;
                console.error('Error rendering patients:', e);
                const tbody = document.querySelector('#patientsTable tbody');
                if (tbody) {
                    tbody.innerHTML = `<tr><td colspan="5" style="text-align:center; padding:2rem; color:#ef4444;">Error loading patients: ${e.message || 'Unknown error'}</td></tr>`; 
                }
            }
        }
        // Doctors management
        let doctors = [];
        let currentDoctorsPage = 1; // unused after pagination removal; kept for compatibility
        const doctorsPerPage = 10;   // unused

        async function renderDoctors(){
            try{
                const res = await fetch('api/users.php?role=doctor&page=1&limit=500');
                const json = await res.json();
                if (!json.success) throw new Error(json.message||'Failed');
                doctors = Array.isArray(json.data) ? json.data : [];
                displayDoctors();
            }catch(e){
                const tbody = document.querySelector('#doctorsTable tbody');
                if (tbody) tbody.innerHTML = '<tr><td colspan="5">Error loading doctors</td></tr>';
            }
        }

        function getFilteredSortedDoctors(){
            const q = (document.getElementById('searchDoctors')?.value||'').toLowerCase();
            const sortBy = (document.getElementById('sortDoctors')?.value||'name');
            let rows = doctors.slice();
            if (q){
                rows = rows.filter(d=>`${(d.first_name||'')} ${(d.last_name||'')}`.toLowerCase().includes(q) || (d.email||'').toLowerCase().includes(q) || (d.phone||'').toLowerCase().includes(q));
            }
            rows.sort((a,b)=>{
                const nameA = (`${a.first_name||''} ${a.last_name||''}`).trim().toLowerCase();
                const nameB = (`${b.first_name||''} ${b.last_name||''}`).trim().toLowerCase();
                const emailA = (a.email||'').toLowerCase();
                const emailB = (b.email||'').toLowerCase();
                if (sortBy==='email') return emailA.localeCompare(emailB);
                return nameA.localeCompare(nameB);
            });
            return rows;
        }

        function displayDoctors(){
            const tbody = document.querySelector('#doctorsTable tbody');
            if (!tbody){ return; }
            const rows = getFilteredSortedDoctors();
            if (!rows.length){ tbody.innerHTML = '<tr><td colspan="5">No doctors found</td></tr>'; return; }
            tbody.innerHTML = rows.map(d=>`<tr>
                <td>${(d.first_name||'')} ${(d.last_name||'')}</td>
                <td>${d.email||''}</td>
                <td>${d.phone||'-'}</td>
                <td>${d.specialty||'-'}</td>
                <td>
                    <button class="btn" onclick="viewDoctorDetails(${d.id})">View</button>
                    <button class="btn" onclick="openDoctorModal('edit', ${d.id})">Edit</button>
                    <button class="btn" onclick="deleteDoctor(${d.id})">Delete</button>
                </td>
            </tr>`).join('');
        }

        document.getElementById('addDoctor').addEventListener('click', ()=> openDoctorModal('add'));
        // No delegation needed; using inline onclick for reliability (matches patient view pattern)
        const addApptBtn = document.getElementById('addAppt'); if (addApptBtn) addApptBtn.addEventListener('click', ()=> openApptModal('add'));
        const apptDate = document.getElementById('apptDate'); if (apptDate) apptDate.addEventListener('change', loadAppointments);
        const apptStatus = document.getElementById('apptStatus'); if (apptStatus) apptStatus.addEventListener('change', loadAppointments);

        function openDoctorModal(mode, id){
            const modal = document.getElementById('doctorEditModal');
            if (!modal) return;
            // Ensure view modal is closed if open
            closeDoctorView();
            document.getElementById('doctorFormTitle').textContent = mode==='edit' ? 'Edit Doctor' : 'Add Doctor';
            const doc = mode==='edit' ? (doctors.find(d=>d.id===id) || {}) : {};
            document.getElementById('doctorId').value = doc.id||'';
            document.getElementById('docFirstName').value = doc.first_name||'';
            document.getElementById('docLastName').value = doc.last_name||'';
            document.getElementById('docEmail').value = doc.email||'';
            document.getElementById('docPassword').value = '';
            document.getElementById('docPhone').value = doc.phone||'';
            document.getElementById('docSpecialty').value = doc.specialty||'';
            document.getElementById('docGender').value = doc.gender||'';
            document.getElementById('docAddress').value = doc.address||'';
            
            // Clear all validation errors when opening modal
            clearAllDoctorValidationErrors();
            
            // Set up validation listeners
            setupDoctorFormValidation();
            
            modal.style.display='flex';
        }
        
        function clearAllDoctorValidationErrors() {
            const errorMessages = document.querySelectorAll('#doctorEditModal .error-message');
            errorMessages.forEach(err => {
                err.classList.remove('show');
                err.textContent = '';
            });
            const inputs = document.querySelectorAll('#doctorEditModal input, #doctorEditModal select');
            inputs.forEach(input => {
                input.classList.remove('error', 'valid');
            });
        }
        
        function showDoctorError(inputId, message) {
            const input = document.getElementById(inputId);
            const errorDiv = document.getElementById(inputId + '-error');
            if (input && errorDiv) {
                input.classList.add('error');
                input.classList.remove('valid');
                errorDiv.textContent = message;
                errorDiv.classList.add('show');
            }
        }
        
        function showDoctorValid(inputId) {
            const input = document.getElementById(inputId);
            const errorDiv = document.getElementById(inputId + '-error');
            if (input && errorDiv) {
                input.classList.remove('error');
                input.classList.add('valid');
                errorDiv.classList.remove('show');
            }
        }
        
        function setupDoctorFormValidation() {
            // First Name validation
            const firstName = document.getElementById('docFirstName');
            if (firstName) {
                firstName.addEventListener('input', function(e) {
                    // Remove numbers in real-time
                    const originalValue = this.value;
                    const withoutNumbers = originalValue.replace(/\d/g, '');
                    if (originalValue !== withoutNumbers) {
                        this.value = withoutNumbers;
                        showDoctorError('docFirstName', 'Numbers are not allowed in name fields');
                        setTimeout(() => {
                            const result = validateName(withoutNumbers, 'First name');
                            if (result.valid) {
                                showDoctorValid('docFirstName');
                            }
                        }, 100);
                    } else {
                        const result = validateName(this.value, 'First name');
                        if (result.valid) {
                            showDoctorValid('docFirstName');
                        } else if (this.value.trim() !== '') {
                            showDoctorError('docFirstName', result.message);
                        } else {
                            showDoctorValid('docFirstName'); // Clear error when empty (will be caught by required)
                        }
                    }
                });
                firstName.addEventListener('keypress', function(e) {
                    // Prevent typing numbers
                    if (/\d/.test(e.key)) {
                        e.preventDefault();
                        showDoctorError('docFirstName', 'Numbers are not allowed');
                    }
                });
                firstName.addEventListener('blur', function() {
                    const result = validateName(this.value, 'First name');
                    if (!result.valid) {
                        showDoctorError('docFirstName', result.message);
                    } else {
                        showDoctorValid('docFirstName');
                    }
                });
            }
            
            // Last Name validation
            const lastName = document.getElementById('docLastName');
            if (lastName) {
                lastName.addEventListener('input', function(e) {
                    // Remove numbers in real-time
                    const originalValue = this.value;
                    const withoutNumbers = originalValue.replace(/\d/g, '');
                    if (originalValue !== withoutNumbers) {
                        this.value = withoutNumbers;
                        showDoctorError('docLastName', 'Numbers are not allowed in name fields');
                        setTimeout(() => {
                            const result = validateName(withoutNumbers, 'Last name');
                            if (result.valid) {
                                showDoctorValid('docLastName');
                            }
                        }, 100);
                    } else {
                        const result = validateName(this.value, 'Last name');
                        if (result.valid) {
                            showDoctorValid('docLastName');
                        } else if (this.value.trim() !== '') {
                            showDoctorError('docLastName', result.message);
                        } else {
                            showDoctorValid('docLastName'); // Clear error when empty (will be caught by required)
                        }
                    }
                });
                lastName.addEventListener('keypress', function(e) {
                    // Prevent typing numbers
                    if (/\d/.test(e.key)) {
                        e.preventDefault();
                        showDoctorError('docLastName', 'Numbers are not allowed');
                    }
                });
                lastName.addEventListener('blur', function() {
                    const result = validateName(this.value, 'Last name');
                    if (!result.valid) {
                        showDoctorError('docLastName', result.message);
                    } else {
                        showDoctorValid('docLastName');
                    }
                });
            }
            
            // Phone validation
            const phone = document.getElementById('docPhone');
            if (phone) {
                phone.addEventListener('input', function(e) {
                    // Remove letters and invalid special characters in real-time
                    const originalValue = this.value;
                    const cleaned = originalValue.replace(/[a-zA-Z@#$%^&*!]/g, '');
                    if (originalValue !== cleaned) {
                        this.value = cleaned;
                        showDoctorError('docPhone', 'Phone number cannot contain letters or invalid characters');
                        setTimeout(() => {
                            const result = validatePhone(cleaned);
                            if (result.valid) {
                                showDoctorValid('docPhone');
                            }
                        }, 100);
                    } else {
                        const result = validatePhone(this.value);
                        if (result.valid) {
                            showDoctorValid('docPhone');
                        } else if (this.value.trim() !== '') {
                            showDoctorError('docPhone', result.message);
                        } else {
                            showDoctorValid('docPhone'); // Clear error when empty (phone is optional)
                        }
                    }
                });
                phone.addEventListener('keypress', function(e) {
                    // Only allow numbers, +, -, spaces, parentheses, dots
                    if (!/[0-9\+\-\(\)\.\s]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                        e.preventDefault();
                        showDoctorError('docPhone', 'Only numbers and phone formatting characters allowed');
                    }
                });
                phone.addEventListener('blur', function() {
                    const result = validatePhone(this.value);
                    if (!result.valid) {
                        showDoctorError('docPhone', result.message);
                    } else {
                        showDoctorValid('docPhone');
                    }
                });
            }
        }
        function closeDoctorModal(){ const m=document.getElementById('doctorEditModal'); if (m) m.style.display='none'; }
        document.getElementById('doctorForm').addEventListener('submit', saveDoctorForm);

        function viewDoctorDetails(id){
            const doc = doctors.find(d=>d.id===id);
            const c = document.getElementById('doctorViewContent');
            if (!doc || !c){ return; }
            // Ensure edit modal is closed before opening view
            closeDoctorModal();
            const name = `${doc.first_name||''} ${doc.last_name||''}`.trim();
            const email = doc.email||'-';
            const phone = doc.phone||'-';
            const dob = doc.date_of_birth||'-';
            const gender = doc.gender||'-';
            const specialty = doc.specialty||'-';
            const address = doc.address||'-';
            const lastLogin = doc.last_login||'-';
            const joined = doc.created_at||'-';
            c.innerHTML = `
                <div style="padding:16px">
                    <h3 style="margin:0 0 8px 0; text-transform:capitalize">${name || email}</h3>
                    <div style="color:#64748b; margin-bottom:12px">Email: ${email} • Phone: ${phone} • DOB: ${dob}</div>
                    <h4 style="margin:8px 0">Profile Details</h4>
                    <table class="table" style="width:100%">
                        <tbody>
                            <tr><td style="width:180px"><strong>Specialty</strong></td><td>${specialty}</td></tr>
                            <tr><td><strong>Gender</strong></td><td>${gender}</td></tr>
                            <tr><td><strong>Address</strong></td><td>${address}</td></tr>
                            <tr><td><strong>Joined</strong></td><td>${joined}</td></tr>
                            <tr><td><strong>Last Login</strong></td><td>${lastLogin}</td></tr>
                        </tbody>
                    </table>
                </div>
            `;
            const m = document.getElementById('doctorViewModal');
            if (m) m.style.display='block';
        }
        function closeDoctorView(){ const m=document.getElementById('doctorViewModal'); if (m) m.style.display='none'; }

        async function saveDoctorForm(e){
            e.preventDefault();
            const form = e.target || document.getElementById('doctorForm');
            
            // Validate all fields before submission
            const firstName = document.getElementById('docFirstName').value.trim();
            const lastName = document.getElementById('docLastName').value.trim();
            const phone = document.getElementById('docPhone').value.trim();
            
            const validations = {
                docFirstName: validateName(firstName, 'First name'),
                docLastName: validateName(lastName, 'Last name'),
                docPhone: validatePhone(phone)
            };
            
            let isValid = true;
            Object.keys(validations).forEach(fieldId => {
                const result = validations[fieldId];
                if (!result.valid) {
                    showDoctorError(fieldId, result.message);
                    isValid = false;
                } else {
                    showDoctorValid(fieldId);
                }
            });
            
            if (!isValid) {
                return; // Don't submit if validation fails
            }
            
            const payload = {
                id: document.getElementById('doctorId').value.trim(),
                first_name: firstName,
                last_name: lastName,
                email: document.getElementById('docEmail').value.trim(),
                password: document.getElementById('docPassword').value.trim(),
                phone: phone,
                gender: document.getElementById('docGender').value.trim(),
                address: document.getElementById('docAddress').value.trim(),
                specialty: document.getElementById('docSpecialty').value.trim(),
                role: 'doctor'
            };
            const isEdit = !!payload.id;

            // Basic client-side validation matching API requirements
            if (!isEdit) {
                if (!payload.first_name || !payload.last_name || !payload.email || !payload.password) {
                    alert('Please fill First name, Last name, Email and Password.');
                    return;
                }
            }

            const url = 'api/users.php';
            const method = isEdit ? 'PUT' : 'POST';
            const body = Object.assign({}, payload);
            if (isEdit && !payload.password){ delete body.password; }
            if (isEdit){ delete body.role; }
            try{
                const res = await fetch(url, { method, headers:{'Content-Type':'application/json'}, body: JSON.stringify(body) });
                const text = await res.text();
                let json;
                try { json = text ? JSON.parse(text) : {}; } catch(parseErr){
                    throw new Error(`Unexpected response (${res.status}): ${text.substring(0,300)}`);
                }
                if (res.ok && json.success){
                    closeDoctorModal();
                    renderDoctors();
                } else {
                    const msg = json && (json.message||json.error) ? (json.message||json.error) : `HTTP ${res.status}`;
                    alert('Save failed: ' + msg);
                }
            }catch(err){
                alert('Save error: ' + (err?.message || err));
            }
        }

        async function deleteDoctor(id){
            if(!confirm('Delete this doctor?')) return;
            try{
                const res = await fetch('api/users.php', { method:'DELETE', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ id }) });
                const json = await res.json();
                if (json.success){ renderDoctors(); }
                else { alert('Delete failed: '+(json.message||'Unknown')); }
            }catch(e){ alert('Delete error'); }
        }

        // Appointments
        let apptPatients = [];
        let apptDoctors = [];

        async function loadAppointments(){
            const tbody = document.querySelector('#apptsTable tbody');
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="6">Loading...</td></tr>';
            const params = new URLSearchParams();
            const d = document.getElementById('apptDate')?.value || '';
            const s = document.getElementById('apptStatus')?.value || '';
            if (d) params.append('date', d);
            if (s) params.append('status', s);
            try{
                const res = await fetch('api/appointments.php'+(params.toString()?`?${params.toString()}`:''));
                const json = await res.json();
                if (!json.success){ tbody.innerHTML = '<tr><td colspan="6">Failed to load</td></tr>'; return; }
                const rows = json.data||[];
                tbody.innerHTML = rows.map(a=>`<tr>
                    <td>${a.appt_date}</td>
                    <td>${a.appt_time}</td>
                    <td>${a.patient_name||a.patient_id}</td>
                    <td>${a.doctor_name||a.doctor_id}</td>
                    <td>${a.status}</td>
                    <td>
                        <button class="btn" onclick="viewAppointment(${a.id})">View</button>
                        <button class="btn" onclick="openApptModal('edit', ${a.id})">Edit</button>
                        <button class="btn" onclick="cancelAppointment(${a.id})">Cancel</button>
                    </td>
                </tr>`).join('') || '<tr><td colspan="6">No appointments</td></tr>';
            }catch(e){ tbody.innerHTML = '<tr><td colspan="6">Error loading</td></tr>'; }
        }

        function openApptModal(mode, id){
            const modal = document.getElementById('apptModal');
            if (!modal) return;
            document.getElementById('apptFormTitle').textContent = mode==='edit' ? 'Edit Appointment' : 'Add Appointment';
            modal.style.display = 'flex';
            // Reset
            const form = document.getElementById('apptForm');
            form.reset();
            form.apptId.value = '';
            // Load lists, then if edit fetch details
            Promise.all([ensureApptLists(), mode==='edit' ? fetch(`api/appointments.php?id=${id}`).then(r=>r.json()) : null])
                .then(([_, details])=>{
                    if (mode==='edit' && details && details.success){
                        const a = details.data;
                        form.apptId.value = a.id;
                        form.patientId.value = a.patient_id;
                        form.doctorId.value = a.doctor_id;
                        form.apptDate.value = a.appt_date;
                        form.apptTime.value = a.appt_time;
                        form.status.value = a.status||'scheduled';
                        form.notes.value = a.notes||'';
                    }
                });
        }
        function closeApptModal(){ const m=document.getElementById('apptModal'); if (m) m.style.display='none'; }

        async function ensureApptLists(){
            if (apptPatients.length && apptDoctors.length){ return; }
            const [pRes, dRes] = await Promise.all([
                fetch('api/users.php?role=patient&page=1&limit=500'),
                fetch('api/users.php?role=doctor&page=1&limit=500')
            ]);
            const pJson = await pRes.json();
            const dJson = await dRes.json();
            apptPatients = pJson.success ? (pJson.data||[]) : [];
            apptDoctors = dJson.success ? (dJson.data||[]) : [];
            const pSel = document.getElementById('apptPatient');
            const dSel = document.getElementById('apptDoctor');
            if (pSel) pSel.innerHTML = '<option value="">Select patient</option>'+apptPatients.map(u=>`<option value="${u.id}">${(u.first_name||'')+' '+(u.last_name||'')} • ${u.email||''}</option>`).join('');
            if (dSel) dSel.innerHTML = '<option value="">Select doctor</option>'+apptDoctors.map(u=>`<option value="${u.id}">${(u.first_name||'')+' '+(u.last_name||'')} • ${u.email||''}</option>`).join('');
        }

        function toMinutes(timeString){
            if (!timeString) return null;
            const [hh, mm] = timeString.split(':');
            const hours = parseInt(hh, 10);
            const minutes = parseInt(mm, 10);
            if (Number.isNaN(hours) || Number.isNaN(minutes)) return null;
            return hours * 60 + minutes;
        }

        function minutesWithinRanges(totalMinutes, ranges){
            return ranges.some(range => {
                const start = toMinutes((range.start_time || '').substring(0,5));
                const end = toMinutes((range.end_time || '').substring(0,5));
                return start !== null && end !== null && totalMinutes >= start && totalMinutes < end;
            });
        }

        async function ensureSlotWithinAvailability(doctorId, date, time, excludeId){
            if (!doctorId || !date || !time) return { ok: true };
            try{
                const res = await fetch(`api/availability.php?doctor_id=${doctorId}&date=${date}`);
                const json = await res.json();
                if (!json.success){
                    return { ok: true }; // fallback to allow if API fails
                }
                const entries = json.data || [];
                const availableRanges = entries.filter(entry => entry.status === 'available');
                const unavailableRanges = entries.filter(entry => entry.status !== 'available');
                const minutes = toMinutes(time);
                if (minutes === null){
                    return { ok: false, message: 'Invalid time format.' };
                }
                const withinOfficeHours = minutes >= (10*60) && minutes <= (19*60);
                if (!withinOfficeHours){
                    return { ok: false, message: 'Selected time is outside the default clinic hours (10:00 – 19:00).' };
                }
                if (!availableRanges.length){
                    // No explicit availability; treat default clinic hours minus unavailable ranges
                    const withinUnavailable = minutesWithinRanges(minutes, unavailableRanges);
                    if (withinUnavailable){
                        return { ok: false, message: 'Selected time falls within an unavailable period.' };
                    }
                    return { ok: true };
                }
                const withinAvailable = minutesWithinRanges(minutes, availableRanges);
                if (!withinAvailable){
                    return { ok: false, message: 'Selected time is outside the doctor\'s available hours.' };
                }
                const withinUnavailable = minutesWithinRanges(minutes, unavailableRanges);
                if (withinUnavailable){
                    return { ok: false, message: 'Selected time falls within an unavailable period.' };
                }
                return { ok: true };
            }catch(e){
                return { ok: true };
            }
        }

        async function saveApptForm(e){
            e.preventDefault();
            const form = e.target;
            const payload = {
                id: form.apptId.value.trim(),
                patient_id: parseInt(form.patientId.value, 10),
                doctor_id: parseInt(form.doctorId.value, 10),
                appt_date: form.apptDate.value,
                appt_time: form.apptTime.value,
                status: form.status.value,
                notes: form.notes.value.trim()
            };
            const isEdit = !!payload.id;
            if (!payload.patient_id || !payload.doctor_id || !payload.appt_date || !payload.appt_time){ alert('Please select patient, doctor, date and time'); return; }
            const availabilityCheck = await ensureSlotWithinAvailability(payload.doctor_id, payload.appt_date, payload.appt_time, payload.id ? parseInt(payload.id, 10) : null);
            if (!availabilityCheck.ok){
                alert(availabilityCheck.message || 'Selected time is not within doctor availability.');
                return;
            }
            const method = isEdit ? 'PUT' : 'POST';
            const url = 'api/appointments.php';
            try{
                const res = await fetch(url, { method, headers:{'Content-Type':'application/json'}, body: JSON.stringify(payload) });
                const json = await res.json();
                if (json.success){ closeApptModal(); loadAppointments(); }
                else { alert('Save failed: '+(json.message||'Unknown')); }
            }catch(err){ alert('Save error'); }
        }

        async function cancelAppointment(id){
            if (!confirm('Cancel this appointment?')) return;
            try{
                const res = await fetch('api/appointments.php', { method:'PUT', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ id, status:'cancelled' }) });
                const json = await res.json();
                if (json.success){ loadAppointments(); }
                else { alert('Cancel failed: '+(json.message||'Unknown')); }
            }catch(e){ alert('Cancel error'); }
        }
        function renderAppts(){
            loadAppointments();
        }
        async function renderReviews(){
            const tbody = document.querySelector('#reviewsTable tbody');
            if (!tbody) return;
            try{
                const res = await fetch('api/reviews.php?limit=100');
                const json = await res.json();
                if (!json.success){ tbody.innerHTML = '<tr><td colspan="6">Failed to load reviews</td></tr>'; return; }
                const rows = json.data||[];
                tbody.innerHTML = rows.map(r=>`<tr>
                    <td>${r.doctor_name||('Dr #'+r.doctor_id)}</td>
                    <td>${'★'.repeat(Math.max(0, Math.min(5, parseInt(r.rating||0))))}</td>
                    <td>${r.comment||''}</td>
                    <td>${(r.patient_name||'') || (r.patient_email||'-')}</td>
                    <td>${r.created_at||''}</td>
                    <td>
                        <button class="btn" onclick="viewReview(${r.id})">View</button>
                        <button class="btn" onclick="openReviewModal(${r.id}, ${parseInt(r.rating||0)}, ${JSON.stringify((r.comment||'')).replace(/"/g,'&quot;')})">Edit</button>
                        <button class="btn" onclick="deleteReview(${r.id})">Delete</button>
                    </td>
                </tr>`).join('') || '<tr><td colspan="6">No reviews yet</td></tr>';
            }catch(e){ tbody.innerHTML = '<tr><td colspan="6">Error loading reviews</td></tr>'; }
        }

        // Add/Edit Patient Modal and handlers
        document.getElementById('addPatient').addEventListener('click', ()=>openPatientModal('add'));

        function openPatientModal(mode, patient){
            const modal = document.getElementById('patientEditModal');
            if(!modal) return;
            modal.style.display='flex';
            modal.querySelector('#formTitle').textContent = mode==='edit' ? 'Edit Patient' : 'Add Patient';
            modal.querySelector('#patientId').value = patient?.id||'';
            modal.querySelector('#firstName').value = patient?.first_name||'';
            modal.querySelector('#lastName').value = patient?.last_name||'';
            modal.querySelector('#email').value = patient?.email||'';
            modal.querySelector('#password').value = '';
            modal.querySelector('#phone').value = patient?.phone||'';
            modal.querySelector('#dob').value = patient?.date_of_birth||'';
            modal.querySelector('#gender').value = patient?.gender||'';
            modal.querySelector('#address').value = patient?.address||'';
            
            // Clear all validation errors when opening modal
            clearAllValidationErrors();
            
            // Set up validation listeners
            setupPatientFormValidation();
        }
        
        function clearAllValidationErrors() {
            const errorMessages = document.querySelectorAll('#patientEditModal .error-message');
            errorMessages.forEach(err => {
                err.classList.remove('show');
                err.textContent = '';
            });
            const inputs = document.querySelectorAll('#patientEditModal input, #patientEditModal select');
            inputs.forEach(input => {
                input.classList.remove('error', 'valid');
            });
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
        
        function validatePhone(phone) {
            if (!phone || phone.trim() === '') {
                return { valid: true, message: '' }; // Phone is optional
            }
            // Remove allowed formatting characters and check if only numbers remain
            const digitsOnly = phone.trim().replace(/[\+\s\-\(\)\.]/g, '');
            if (!/^\d+$/.test(digitsOnly)) {
                return { valid: false, message: 'Phone number can only contain numbers and formatting characters (+, -, spaces, parentheses, dots)' };
            }
            if (digitsOnly.length < 7 || digitsOnly.length > 15) {
                return { valid: false, message: 'Phone number must be between 7 and 15 digits' };
            }
            return { valid: true, message: '' };
        }
        
        function setupPatientFormValidation() {
            // First Name validation
            const firstName = document.getElementById('firstName');
            if (firstName) {
                firstName.addEventListener('input', function(e) {
                    // Remove numbers in real-time
                    const originalValue = this.value;
                    const withoutNumbers = originalValue.replace(/\d/g, '');
                    if (originalValue !== withoutNumbers) {
                        this.value = withoutNumbers;
                        showError('firstName', 'Numbers are not allowed in name fields');
                        setTimeout(() => {
                            const result = validateName(withoutNumbers, 'First name');
                            if (result.valid) {
                                showValid('firstName');
                            }
                        }, 100);
                    } else {
                        const result = validateName(this.value, 'First name');
                        if (result.valid) {
                            showValid('firstName');
                        } else if (this.value.trim() !== '') {
                            showError('firstName', result.message);
                        } else {
                            showValid('firstName'); // Clear error when empty (will be caught by required)
                        }
                    }
                });
                firstName.addEventListener('keypress', function(e) {
                    // Prevent typing numbers
                    if (/\d/.test(e.key)) {
                        e.preventDefault();
                        showError('firstName', 'Numbers are not allowed');
                    }
                });
                firstName.addEventListener('blur', function() {
                    const result = validateName(this.value, 'First name');
                    if (!result.valid) {
                        showError('firstName', result.message);
                    } else {
                        showValid('firstName');
                    }
                });
            }
            
            // Last Name validation
            const lastName = document.getElementById('lastName');
            if (lastName) {
                lastName.addEventListener('input', function(e) {
                    // Remove numbers in real-time
                    const originalValue = this.value;
                    const withoutNumbers = originalValue.replace(/\d/g, '');
                    if (originalValue !== withoutNumbers) {
                        this.value = withoutNumbers;
                        showError('lastName', 'Numbers are not allowed in name fields');
                        setTimeout(() => {
                            const result = validateName(withoutNumbers, 'Last name');
                            if (result.valid) {
                                showValid('lastName');
                            }
                        }, 100);
                    } else {
                        const result = validateName(this.value, 'Last name');
                        if (result.valid) {
                            showValid('lastName');
                        } else if (this.value.trim() !== '') {
                            showError('lastName', result.message);
                        } else {
                            showValid('lastName'); // Clear error when empty (will be caught by required)
                        }
                    }
                });
                lastName.addEventListener('keypress', function(e) {
                    // Prevent typing numbers
                    if (/\d/.test(e.key)) {
                        e.preventDefault();
                        showError('lastName', 'Numbers are not allowed');
                    }
                });
                lastName.addEventListener('blur', function() {
                    const result = validateName(this.value, 'Last name');
                    if (!result.valid) {
                        showError('lastName', result.message);
                    } else {
                        showValid('lastName');
                    }
                });
            }
            
            // Phone validation
            const phone = document.getElementById('phone');
            if (phone) {
                phone.addEventListener('input', function(e) {
                    // Remove letters and invalid special characters in real-time
                    const originalValue = this.value;
                    const cleaned = originalValue.replace(/[a-zA-Z@#$%^&*!]/g, '');
                    if (originalValue !== cleaned) {
                        this.value = cleaned;
                        showError('phone', 'Phone number cannot contain letters or invalid characters');
                        setTimeout(() => {
                            const result = validatePhone(cleaned);
                            if (result.valid) {
                                showValid('phone');
                            }
                        }, 100);
                    } else {
                        const result = validatePhone(this.value);
                        if (result.valid) {
                            showValid('phone');
                        } else if (this.value.trim() !== '') {
                            showError('phone', result.message);
                        } else {
                            showValid('phone'); // Clear error when empty (phone is optional)
                        }
                    }
                });
                phone.addEventListener('keypress', function(e) {
                    // Only allow numbers, +, -, spaces, parentheses, dots
                    if (!/[0-9\+\-\(\)\.\s]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                        e.preventDefault();
                        showError('phone', 'Only numbers and phone formatting characters allowed');
                    }
                });
                phone.addEventListener('blur', function() {
                    const result = validatePhone(this.value);
                    if (!result.valid) {
                        showError('phone', result.message);
                    } else {
                        showValid('phone');
                    }
                });
            }
        }

        function closePatientModal(){ const m=document.getElementById('patientEditModal'); if(m) m.style.display='none'; }

        async function savePatientForm(e){
            e.preventDefault();
            const form = e.target;
            
            // Validate all fields before submission
            const firstName = form.firstName.value.trim();
            const lastName = form.lastName.value.trim();
            const phone = form.phone.value.trim();
            
            const validations = {
                firstName: validateName(firstName, 'First name'),
                lastName: validateName(lastName, 'Last name'),
                phone: validatePhone(phone)
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
                return; // Don't submit if validation fails
            }
            
            const payload = {
                id: form.patientId.value.trim(),
                first_name: firstName,
                last_name: lastName,
                email: form.email.value.trim(),
                password: form.password.value.trim(),
                phone: phone,
                date_of_birth: form.dob.value.trim(),
                gender: form.gender.value.trim(),
                address: form.address.value.trim()
            };
            const isEdit = !!payload.id;
            const url = isEdit ? `api/patients.php?id=${payload.id}` : 'api/patients.php';
            const method = isEdit ? 'PUT' : 'POST';
            try{
                const res = await fetch(url, { method, headers:{'Content-Type':'application/json'}, body: JSON.stringify(payload)});
                const json = await res.json();
                if(json.success){ closePatientModal(); renderPatients(); }
                else{ alert('Save failed: '+(json.message||'Unknown')); }
            }catch(e){ alert('Save error'); }
        }

        // Bind appointment form submit
        document.getElementById('apptForm').addEventListener('submit', saveApptForm);
        let allDoctorsAv = [];
        let allAppointmentsAv = [];

        async function loadDoctorsAv(){
            try{
                const res = await fetch('api/users.php?role=doctor&page=1&limit=100');
                const json = await res.json();
                if (json.success){
                    allDoctorsAv = json.data || [];
                    const sel = document.getElementById('avDoctor');
                    if (sel){
                        sel.innerHTML = '<option value="" selected disabled>Select Doctor...</option>';
                        allDoctorsAv.forEach(d=>{
                            const o = document.createElement('option');
                            o.value = d.id;
                            o.textContent = `Dr. ${(d.first_name||'')} ${(d.last_name||'')}`.trim() || d.email;
                            sel.appendChild(o);
                        });
                    }
                }
            }catch(e){
                console.warn('Failed to load doctors:', e);
            }
        }

        async function loadAppointmentsAv(doctorId, date){
            try{
                let url = 'api/appointments.php?limit=500';
                if (doctorId) url += `&doctor_id=${doctorId}`;
                if (date) url += `&date=${date}`;
                
                const res = await fetch(url);
                const json = await res.json();
                if (json.success){
                    allAppointmentsAv = json.data || [];
                } else {
                    allAppointmentsAv = [];
                }
            }catch(e){
                console.warn('Failed to load appointments:', e);
                allAppointmentsAv = [];
            }
        }

        function generateTimeSlots(){
            const slots = [];
            for (let h = 10; h < 19; h++){
                for (let m = 0; m < 60; m += 15){
                    const hh = String(h).padStart(2,'0');
                    const mm = String(m).padStart(2,'0');
                    slots.push(`${hh}:${mm}`);
                }
            }
            slots.push('19:00');
            return slots;
        }

        function isSlotBooked(doctorId, date, time){
            if (!date || !time) return false;
            return allAppointmentsAv.some(a=>{
                const apptDate = a.appt_date || a.appointment_date;
                const apptTime = (a.appt_time || a.appointment_time || '').substring(0,5);
                const status = (a.status || '').toLowerCase();
                return a.doctor_id == doctorId && 
                       apptDate === date && 
                       apptTime === time && 
                       status !== 'cancelled';
            });
        }

        function isSlotPast(date, time){
            if (!date || !time) return false;
            const slotDateTime = new Date(`${date}T${time}:00`);
            return slotDateTime < new Date();
        }

        function escapeHtml(text){
            return (text||'').toString().replace(/[&<>'"]/g, c=>({"&":"&amp;","<":"&lt;",">":"&gt;","'":"&#39;",'"':"&quot;"}[c]));
        }

        function displayAvailability(doctorId, date){
            const container = document.getElementById('availabilityResults');
            if (!container) return;
            container.innerHTML = '';
            
            if (!doctorId){
                container.innerHTML = '<div class="card" style="padding:40px; text-align:center; color:var(--gray-500);">Please select a doctor to view availability.</div>';
                return;
            }

            if (!date){
                container.innerHTML = '<div class="card" style="padding:40px; text-align:center; color:var(--gray-500);">Please select a date to view availability.</div>';
                return;
            }

            const doctor = allDoctorsAv.find(d=> d.id == doctorId);
            if (!doctor){
                container.innerHTML = '<div class="card" style="padding:40px; text-align:center; color:var(--gray-500);">Doctor not found.</div>';
                return;
            }

            const timeSlots = generateTimeSlots();
            const bookedSlots = [];
            const availableSlots = [];
            
            timeSlots.forEach(slot=>{
                if (isSlotPast(date, slot)){
                    // Skip past slots
                } else if (isSlotBooked(doctor.id, date, slot)){
                    bookedSlots.push(slot);
                } else {
                    availableSlots.push(slot);
                }
            });

            const specialty = doctor.specialty || 'General Medicine';
            const doctorName = `Dr. ${(doctor.first_name||'')} ${(doctor.last_name||'')}`.trim() || doctor.email;
            
            let slotsHTML = '';
            timeSlots.forEach(slot=>{
                if (isSlotPast(date, slot)){
                    slotsHTML += `<div class="time-slot past" style="padding:8px 12px; border-radius:8px; text-align:center; font-size:13px; font-weight:600; background:var(--gray-200); color:var(--gray-500); border:2px solid var(--gray-300);">${slot}</div>`;
                } else if (isSlotBooked(doctor.id, date, slot)){
                    slotsHTML += `<div class="time-slot booked" style="padding:8px 12px; border-radius:8px; text-align:center; font-size:13px; font-weight:600; background:#fee2e2; color:#991b1b; border:2px solid #fca5a5; cursor:not-allowed; opacity:0.6;" title="Booked">${slot}</div>`;
                } else {
                    slotsHTML += `<div class="time-slot available" style="padding:8px 12px; border-radius:8px; text-align:center; font-size:13px; font-weight:600; background:#dcfce7; color:#166534; border:2px solid #86efac;" title="Available">${slot}</div>`;
                }
            });

            const cardHTML = `
                <div class="card" style="padding:20px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                        <div>
                            <div style="font-weight:700; color:var(--gray-900); font-size:18px;">${escapeHtml(doctorName)}</div>
                            <div style="color:var(--gray-600); font-size:14px;">${escapeHtml(specialty)}</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:14px; color:var(--gray-600);">Available</div>
                            <div style="font-weight:700; color:#166534; font-size:20px;">${availableSlots.length} slots</div>
                        </div>
                    </div>
                    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap:8px;">
                        ${slotsHTML}
                    </div>
                </div>
            `;
            container.innerHTML = cardHTML;
        }

        async function searchAvailability(){
            const doctorId = document.getElementById('avDoctor')?.value;
            const date = document.getElementById('avDate')?.value;
            
            if (!doctorId || doctorId === ''){
                alert('Please select a doctor first.');
                return;
            }
            
            if (!date || date === ''){
                alert('Please select a date first.');
                return;
            }
            
            await loadAppointmentsAv(doctorId, date);
            displayAvailability(doctorId, date);
        }

        function resetAvailabilityFilters(){
            const doctorSel = document.getElementById('avDoctor');
            const dateInput = document.getElementById('avDate');
            if (doctorSel) doctorSel.value = '';
            if (dateInput) dateInput.value = '';
            const container = document.getElementById('availabilityResults');
            if (container) container.innerHTML = '<div class="card" style="padding:40px; text-align:center; color:var(--gray-500);">Please select a doctor and date, then click Search to view availability.</div>';
        }

        function renderAvailability(){
            const container = document.getElementById('availabilityResults');
            if (container){
                container.innerHTML = '<div class="card" style="padding:40px; text-align:center; color:var(--gray-500);">Please select a doctor and date, then click Search to view availability.</div>';
            }
            
            // Set date constraints
            const dateInput = document.getElementById('avDate');
            if (dateInput){
                const today = new Date();
                const maxDate = new Date(today.getTime() + 180*24*60*60*1000);
                dateInput.min = today.toISOString().slice(0,10);
                dateInput.max = maxDate.toISOString().slice(0,10);
            }
            
            // Load doctors
            loadDoctorsAv();
        }
        function formatLogMessage(msg){
            if (!msg) return '';
            const parts = msg.split('|');
            if (parts.length === 2){
                const title = parts[0].trim();
                try{
                    const data = JSON.parse(parts[1]);
                    const rows = Object.entries(data)
                        .filter(([_,v])=>v!==null && v!=='' && v!==undefined)
                        .map(([k,v])=>`<span><strong>${k}</strong>${v}</span>`)
                        .join('');
                    return `<div><strong>${title}</strong><div class="log-meta">${rows}</div></div>`;
                }catch(_e){ return msg; }
            }
            return msg;
        }

        async function renderLogs(){
            try{
                const res = await fetch('api/logs.php?limit=20');
                const text = await res.text();
                let json; try{ json = text ? JSON.parse(text) : { success:false, message:'Empty response' }; }catch(e){ json = { success:false, message: text || 'Invalid response' }; }
                const tbody = document.querySelector('#logsTable tbody');
                if (json.success && Array.isArray(json.data) && json.data.length){
                    tbody.innerHTML = json.data.map(l=>`<tr>
                        <td>${l.created_at}</td>
                        <td>${l.level}</td>
                        <td>${formatLogMessage(l.message)}</td>
                        <td>${l.user_email||l.user_id||''}</td>
                        <td>
                            <button class="btn" onclick="editLog(${l.id}, '${l.level}', ${JSON.stringify(l.message).replace(/"/g,'&quot;')}, '${l.user_email||''}')">Edit</button>
                            <button class="btn" onclick="deleteLog(${l.id})">Delete</button>
                        </td>
                    </tr>`).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="4">No logs yet</td></tr>';
                }
                return !!json.success;
            }catch(e){
                const tbody = document.querySelector('#logsTable tbody');
                tbody.innerHTML = `<tr><td colspan="4">Failed to load logs</td></tr>`;
                return false;
            }
        }
        async function createLog(){
            const level = (document.getElementById('logLevel')?.value||'INFO').toUpperCase();
            const message = document.getElementById('logMessage')?.value?.trim()||'';
            const user_email = document.getElementById('logUserEmail')?.value?.trim()||'';
            if(!message){ alert('Please enter a log message'); return; }
            try{
                const btn = document.getElementById('createLogBtn'); if(btn){ btn.disabled = true; btn.textContent = 'Adding...'; }
                const res = await fetch('api/logs.php', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ level, message, user_email }) });
                const text = await res.text();
                let json; let isSuccess=false;
                try{ json = text ? JSON.parse(text) : {success:false,message:'Empty'}; isSuccess = !!json.success; }
                catch(e){ isSuccess = /"success"\s*:\s*true/i.test(text); json = { success: isSuccess, message: text || ('Parse error: '+(e&&e.message)) }; }
                if(isSuccess){
                    const loaded = await renderLogs();
                    if (loaded){ document.getElementById('logMessage').value=''; }
                } else {
                    alert('Add log failed: ' + (json.message||'Unknown'));
                }
            }catch(e){ alert('Add log error: '+(e&&e.message||e)); }
            finally{ const btn = document.getElementById('createLogBtn'); if(btn){ btn.disabled=false; btn.textContent = '+ Add Log'; } }
        }
        // Review handlers
        function closeReviewViewModal(){
            const modal = document.getElementById('reviewViewModal');
            if (modal) modal.style.display = 'none';
        }

        async function viewReview(reviewId){
            const modal = document.getElementById('reviewViewModal');
            const content = document.getElementById('reviewViewContent');
            if (!modal || !content) return;
            
            // Close edit modal if open
            closeReviewModal();
            
            // Show modal and loading state
            modal.style.display = 'block';
            content.innerHTML = '<div>Loading review details...</div>';
            
            try {
                const res = await fetch(`api/reviews.php?id=${reviewId}`);
                const json = await res.json();
                
                if (!json.success) {
                    content.innerHTML = `<div style="color:#ef4444">Failed to load review: ${json.message || 'Unknown error'}</div>`;
                    return;
                }
                
                const r = json.data;
                const doctorName = r.doctor_name || `Doctor #${r.doctor_id || '-'}`;
                const patientName = r.patient_name || r.patient_email || 'Unknown';
                const rating = parseInt(r.rating || 0);
                const stars = '★'.repeat(Math.max(0, Math.min(5, rating))) + '☆'.repeat(Math.max(0, 5 - rating));
                const comment = r.comment || 'No comment provided';
                const createdAt = r.created_at ? new Date(r.created_at).toLocaleString() : '-';
                const updatedAt = r.updated_at ? new Date(r.updated_at).toLocaleString() : '-';
                
                content.innerHTML = `
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <h3 style="margin:0 0 12px 0; color:var(--gray-700)">Review Information</h3>
                            <table class="table" style="width:100%; font-size:14px">
                                <tbody>
                                    <tr><td style="width:120px; font-weight:600">Rating:</td><td><span style="font-size:18px; color:#fbbf24;">${stars}</span> <span style="color:var(--gray-600);">(${rating}/5)</span></td></tr>
                                    <tr><td style="font-weight:600">Comment:</td><td style="padding:12px; background:var(--gray-50); border-radius:8px; line-height:1.6;">${escapeHtml(comment)}</td></tr>
                                    <tr><td style="font-weight:600">Submitted:</td><td>${createdAt}</td></tr>
                                    ${updatedAt !== '-' && updatedAt !== createdAt ? `<tr><td style="font-weight:600">Last Updated:</td><td>${updatedAt}</td></tr>` : ''}
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <h3 style="margin:0 0 12px 0; color:var(--gray-700)">Doctor Details</h3>
                            <table class="table" style="width:100%; font-size:14px">
                                <tbody>
                                    <tr><td style="width:120px; font-weight:600">Name:</td><td>${escapeHtml(doctorName)}</td></tr>
                                    <tr><td style="font-weight:600">ID:</td><td>#${r.doctor_id || '-'}</td></tr>
                                </tbody>
                            </table>
                            <h3 style="margin:16px 0 12px 0; color:var(--gray-700)">Patient Details</h3>
                            <table class="table" style="width:100%; font-size:14px">
                                <tbody>
                                    <tr><td style="width:120px; font-weight:600">Name:</td><td>${escapeHtml(patientName)}</td></tr>
                                    <tr><td style="font-weight:600">ID:</td><td>#${r.patient_id || '-'}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            } catch(e) {
                content.innerHTML = `<div style="color:#ef4444">Error loading review: ${e.message || 'Unknown error'}</div>`;
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function openReviewModal(id, rating, comment){
            // Close view modal if open
            closeReviewViewModal();
            
            document.getElementById('reviewId').value = id;
            document.getElementById('reviewRating').value = String(rating||0);
            document.getElementById('reviewComment').value = comment||'';
            const m=document.getElementById('reviewModal'); if(m) m.style.display='block';
        }
        function closeReviewModal(){ const m=document.getElementById('reviewModal'); if(m) m.style.display='none'; }
        document.getElementById('reviewForm').addEventListener('submit', async function(e){
            e.preventDefault();
            const id = parseInt(document.getElementById('reviewId').value, 10);
            const rating = parseInt(document.getElementById('reviewRating').value, 10);
            const comment = document.getElementById('reviewComment').value.trim();
            try{
                const res = await fetch('api/reviews.php', { method:'PUT', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ id, rating, comment }) });
                const json = await res.json();
                if (json.success){ closeReviewModal(); renderReviews(); loadDashboardFromAPI(); }
                else { alert('Update failed: '+(json.message||'Unknown')); }
            }catch(e){ alert('Update error'); }
        });
        async function deleteReview(id){
            if(!confirm('Delete this review?')) return;
            try{
                const res = await fetch('api/reviews.php', { method:'DELETE', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ id }) });
                const json = await res.json();
                if (json.success){ renderReviews(); loadDashboardFromAPI(); }
                else { alert('Delete failed: '+(json.message||'Unknown')); }
            }catch(e){ alert('Delete error'); }
        }
        async function editLog(id, currentLevel, currentMessage, currentEmail){
            const newMessage = prompt('Edit log message:', currentMessage||'');
            if (newMessage===null) return;
            let newLevel = prompt('Edit level (INFO/WARN/ERROR):', (currentLevel||'INFO'))||'INFO';
            newLevel = newLevel.toUpperCase();
            if(!['INFO','WARN','ERROR'].includes(newLevel)) newLevel='INFO';
            const newEmail = prompt('Edit user email (optional):', currentEmail||'')||'';
            try{
                const res = await fetch(`api/logs.php?id=${id}`, { method:'PUT', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ level:newLevel, message:newMessage, user_email:newEmail }) });
                const json = await res.json();
                if(json.success){ renderLogs(); }
                else{ alert('Update log failed'); }
            }catch(e){ alert('Update log error'); }
        }
        async function deleteLog(id){
            if(!confirm('Delete this log entry?')) return;
            try{
                const res = await fetch(`api/logs.php?id=${id}`, { method:'DELETE' });
                const json = await res.json();
                if(json.success){ renderLogs(); }
                else{ alert('Delete log failed'); }
            }catch(e){ alert('Delete log error'); }
        }

        async function loadDashboardFromAPI(){
            try{
                // Fetch all required data in parallel
                // For chart, fetch all patients (or at least a large number to ensure we get all)
                const [patientsTotal, doctorsTotal, recentPatients, allPatientsForChart, appointmentsData, doctorsData, reviewsData] = await Promise.all([
                    fetchSummary('patient'),
                    fetchSummary('doctor'),
                    fetchRecent('patient', 12).catch(() => []), // Return empty array if fetch fails - used for dashboard table
                    fetch('api/patients.php').then(r => r.json()).then(data => data.success ? (data.data || []) : []).catch(() => []), // Fetch ALL patients for chart
                    fetch('api/appointments.php').then(r => r.json()).catch(() => ({success: false, data: []})),
                    fetch('api/users.php?role=doctor&page=1&limit=100').then(r => r.json()).catch(() => ({success: false, data: []})),
                    fetch('api/reviews.php?limit=5').then(r => r.json()).catch(() => ({success: false, data: []}))
                ]);

                // Get actual appointments count
                const appointmentsTotal = appointmentsData.success && Array.isArray(appointmentsData.data) ? appointmentsData.data.length : 0;
                
                // Set KPIs with real data
                setKPIs({
                    patients: patientsTotal,
                    doctors: doctorsTotal,
                    appointments: appointmentsTotal,
                    costs: 0, // Average costs - placeholder (no cost API available)
                    vehicles: 0 // Total vehicles - placeholder (no vehicles API available)
                });

                // Fill dashboard appointment table with actual appointments
                const dash = document.querySelector('#dashApptTable tbody');
                if (dash){
                    const recentAppts = appointmentsData.success && Array.isArray(appointmentsData.data) 
                        ? appointmentsData.data.slice(0, 5).sort((a, b) => {
                            const dateA = new Date(a.appt_date + ' ' + a.appt_time);
                            const dateB = new Date(b.appt_date + ' ' + b.appt_time);
                            return dateB - dateA;
                        })
                        : [];
                    
                    if (recentAppts.length > 0) {
                        // Create a map of patient IDs to their data for gender lookup
                        const patientMap = {};
                        if (Array.isArray(recentPatients)) {
                            recentPatients.forEach(p => {
                                patientMap[p.id] = p;
                            });
                        }
                        
                        dash.innerHTML = recentAppts.map(a => {
                            const name = a.patient_name || `Patient #${a.patient_id}`;
                            const date = new Date(a.appt_date);
                            const dateStr = date.toLocaleDateString(undefined, {day:'2-digit', month:'short', year:'numeric'});
                            const timeStr = a.appt_time ? a.appt_time.substring(0, 5) : '-';
                            const patientData = patientMap[a.patient_id] || {};
                            const gender = patientData.gender || '-';
                            return `<tr><td>${name}</td><td>${gender}</td><td>${dateStr}</td><td>${timeStr}</td><td><button class="btn" onclick="viewAppointment(${a.id})">View</button></td></tr>`;
                        }).join('');
                    } else {
                        dash.innerHTML = '<tr><td colspan="5">No appointments found</td></tr>';
                    }
                }

                // Fill Patient Review section with real doctors and their patient counts
                const reviewList = document.getElementById('patientReviewList');
                if (reviewList) {
                    if (reviewsData && reviewsData.success && Array.isArray(reviewsData.data) && reviewsData.data.length) {
                        const reviews = reviewsData.data.slice(0, 5);
                        let reviewHTML = '';

                        reviews.forEach(review => {
                            const doctorName = (review.doctor_name || '').trim() || `Doctor #${review.doctor_id || '-'}`;
                            const nameParts = doctorName.split(' ').filter(Boolean);
                            const initials = nameParts.length === 0
                                ? 'DR'
                                : (nameParts[0].charAt(0) + (nameParts[1]?.charAt(0) || '')).toUpperCase();
                            const reviewer = (review.patient_name || review.patient_email || 'Anonymous').trim();
                            const rating = Math.max(0, Math.min(5, parseInt(review.rating || 0, 10)));
                            const stars = '★'.repeat(rating) + '☆'.repeat(5 - rating);
                            const comment = (review.comment || '').trim();
                            const displayComment = comment.length > 90 ? comment.slice(0, 87) + '…' : comment || 'No comment provided';

                            reviewHTML += `
                                <div class="review-item">
                                    <div class="review-left">
                                        <div class="mini-avatar">${escapeHtml(initials)}</div>
                                        <div>
                                            <div style="font-weight:600">${escapeHtml(doctorName)}</div>
                                            <div style="color:var(--gray-500); font-size:12px">${escapeHtml(reviewer)}</div>
                                        </div>
                                    </div>
                                    <div style="display:flex; align-items:center; gap:12px; color:var(--gray-500); font-size:12px; text-align:right; max-width:220px;">
                                        <div style="flex:1 1 auto;">
                                            <div style="color:#f59e0b; font-size:14px;">${stars}</div>
                                            <div style="margin-top:4px; color:var(--gray-600); line-height:1.4;">${escapeHtml(displayComment)}</div>
                                        </div>
                                        <button class="btn" style="flex:0 0 auto; padding:6px 12px; font-size:12px;" onclick="viewReview(${parseInt(review.id, 10) || 0}); return false;">View</button>
                                    </div>
                                </div>
                            `;
                        });

                        reviewList.innerHTML = reviewHTML;
                    } else {
                        reviewList.innerHTML = '<div class="review-item"><div style="color:var(--gray-500);">No reviews yet</div></div>';
                    }
                }

                // Build charts from actual patient registration data - use ALL patients for chart
                const chartPatients = Array.isArray(allPatientsForChart) && allPatientsForChart.length > 0 ? allPatientsForChart : [];
                const months = new Array(12).fill(0);
                
                if (chartPatients.length > 0) {
                    chartPatients.forEach(p => {
                        if (p && p.created_at) {
                            const d = new Date(p.created_at);
                            if (!isNaN(d.getTime())) {
                                months[d.getMonth()]++;
                            }
                        }
                    });
                }
                
                const visits = document.getElementById('chartVisits');
                if (visits){
                    const maxVal = Math.max(...months, 1);
                    const points = months.map((v, i) => {
                        const x = i * 50;
                        const y = 170 - Math.min(140, (v / maxVal) * 140);
                        return `${x},${y}`;
                    }).join(' ');
                    visits.innerHTML = `<svg viewBox="0 0 600 220" width="100%" height="100%"><polyline fill="none" stroke="#60a5fa" stroke-width="3" points="${points}"/><polygon fill="rgba(96, 165, 250, 0.1)" points="0,170 ${points} 550,170"/></svg>`;
                }
                
                const pats = document.getElementById('chartPatients');
                if (pats){
                    // Show the most recent 6 months based on current date
                    const now = new Date();
                    const currentMonth = now.getMonth(); // 0-11
                    
                    // Get the last 6 months including current month
                    let monthsToShow = [];
                    for (let i = 5; i >= 0; i--) {
                        let monthIndex = currentMonth - i;
                        if (monthIndex < 0) monthIndex += 12; // Wrap around to previous year
                        monthsToShow.push({
                            value: months[monthIndex] || 0,
                            index: monthIndex,
                            monthName: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][monthIndex]
                        });
                    }
                    
                    // Calculate max value from months to show
                    const maxVal = Math.max(...monthsToShow.map(m => m.value), 1);
                    let x = 20;
                    let svgBars = '';
                    
                    // Render bars for the selected months
                    monthsToShow.forEach(m => {
                        const v = m.value;
                        // Calculate bar height based on actual value
                        // Use full height if this month has the max value, otherwise proportional
                        const barHeight = maxVal > 0 ? Math.min(140, (v / maxVal) * 140) : 10;
                        // Ensure minimum height for bars with data
                        const h = v > 0 ? Math.max(30, barHeight) : 10;
                        svgBars += `<rect x="${x}" y="${200-h}" width="26" height="${h}" rx="6" fill="#60a5fa" opacity="${v > 0 ? '1' : '0.3'}"/>`;
                        // Add value label on top of bar if it has data
                        if (v > 0) {
                            svgBars += `<text x="${x + 13}" y="${200-h - 5}" text-anchor="middle" fill="#1e40af" font-size="11" font-weight="700" font-family="Arial, sans-serif">${v}</text>`;
                        }
                        // Add month label below bar
                        svgBars += `<text x="${x + 13}" y="${215}" text-anchor="middle" fill="#64748b" font-size="10" font-family="Arial, sans-serif">${m.monthName}</text>`;
                        x += 40;
                    });
                    
                    // If no data, show a message
                    if (maxVal === 0 || chartPatients.length === 0) {
                        pats.innerHTML = `<svg viewBox="0 0 300 220" width="100%" height="100%">${svgBars}<text x="150" y="110" text-anchor="middle" fill="#64748b" font-size="14" font-family="Arial, sans-serif">No patient data yet</text></svg>`;
                    } else {
                        pats.innerHTML = `<svg viewBox="0 0 300 220" width="100%" height="100%">${svgBars}</svg>`;
                    }
                }

                renderAnalyticsChart(appointmentsData.success && Array.isArray(appointmentsData.data) ? appointmentsData.data : []);
            }catch(e){
                console.warn('Dashboard API load failed, using placeholders', e);
                renderKPIs();
            }
        }
        
        function closeApptViewModal(){
            const modal = document.getElementById('apptViewModal');
            if (modal) modal.style.display = 'none';
        }
        
        async function viewAppointment(apptId){
            const modal = document.getElementById('apptViewModal');
            const content = document.getElementById('apptViewContent');
            if (!modal || !content) return;
            
            // Close edit modal if open
            closeApptModal();
            
            // Show modal and loading state
            modal.style.display = 'block';
            content.innerHTML = '<div>Loading appointment details...</div>';
            
            try {
                const res = await fetch(`api/appointments.php?id=${apptId}`);
                const json = await res.json();
                
                if (!json.success) {
                    content.innerHTML = `<div style="color:#ef4444">Failed to load appointment: ${json.message || 'Unknown error'}</div>`;
                    return;
                }
                
                const a = json.data;
                const date = a.appt_date || '-';
                const time = a.appt_time || '-';
                const patientName = a.patient_name || `Patient #${a.patient_id || '-'}`;
                const patientEmail = a.patient_email || '-';
                const doctorName = a.doctor_name || `Doctor #${a.doctor_id || '-'}`;
                const doctorEmail = a.doctor_email || '-';
                const status = a.status || '-';
                const notes = a.notes || 'No notes';
                const createdAt = a.created_at ? new Date(a.created_at).toLocaleString() : '-';
                const updatedAt = a.updated_at ? new Date(a.updated_at).toLocaleString() : '-';
                
                content.innerHTML = `
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div>
                            <h3 style="margin:0 0 12px 0; color:var(--gray-700)">Appointment Information</h3>
                            <table class="table" style="width:100%; font-size:14px">
                                <tbody>
                                    <tr><td style="width:120px; font-weight:600">Date:</td><td>${date}</td></tr>
                                    <tr><td style="font-weight:600">Time:</td><td>${time}</td></tr>
                                    <tr><td style="font-weight:600">Status:</td><td><span style="padding:4px 8px; border-radius:4px; background:${status === 'completed' ? '#10b981' : status === 'cancelled' ? '#ef4444' : status === 'scheduled' ? '#3b82f6' : '#f59e0b'}; color:white; font-size:12px">${status}</span></td></tr>
                                    <tr><td style="font-weight:600">Notes:</td><td>${notes}</td></tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <h3 style="margin:0 0 12px 0; color:var(--gray-700)">Patient Details</h3>
                            <table class="table" style="width:100%; font-size:14px">
                                <tbody>
                                    <tr><td style="width:120px; font-weight:600">Name:</td><td>${patientName}</td></tr>
                                    <tr><td style="font-weight:600">Email:</td><td>${patientEmail}</td></tr>
                                    <tr><td style="font-weight:600">ID:</td><td>#${a.patient_id || '-'}</td></tr>
                                </tbody>
                            </table>
                            <h3 style="margin:16px 0 12px 0; color:var(--gray-700)">Doctor Details</h3>
                            <table class="table" style="width:100%; font-size:14px">
                                <tbody>
                                    <tr><td style="width:120px; font-weight:600">Name:</td><td>${doctorName}</td></tr>
                                    <tr><td style="font-weight:600">Email:</td><td>${doctorEmail}</td></tr>
                                    <tr><td style="font-weight:600">ID:</td><td>#${a.doctor_id || '-'}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div style="margin-top:16px; padding-top:16px; border-top:1px solid var(--gray-200); font-size:12px; color:var(--gray-500)">
                        <div>Created: ${createdAt}</div>
                        <div>Last Updated: ${updatedAt}</div>
                    </div>
                `;
            } catch(e) {
                content.innerHTML = `<div style="color:#ef4444">Error loading appointment: ${e.message || 'Unknown error'}</div>`;
            }
        }

        // Store active section in sessionStorage as backup
        function storeActiveSection(section) {
            try {
                sessionStorage.setItem('activeSection', section);
            } catch(e) {}
        }
        
        function getStoredActiveSection() {
            try {
                return sessionStorage.getItem('activeSection') || null;
            } catch(e) {
                return null;
            }
        }
        
        // Init
        document.addEventListener('DOMContentLoaded', function(){
            const hash = window.location.hash.replace('#', '').trim();
            const stored = getStoredActiveSection();
            const defaultSection = window.__DEFAULT_SECTION__ || 'dashboard';
            const targetSection = hash || defaultSection || stored || 'dashboard';

            const avatarBtn = document.getElementById('avatar');
            if (avatarBtn) {
                avatarBtn.addEventListener('click', () => setSection('settings'));
            }

            setSection(targetSection, true, true);
            storeActiveSection(targetSection);
 
            // Load dashboard data (always loads)
            loadDashboardFromAPI();
 
            // Load data for active section after a short delay
            setTimeout(() => {
                if (targetSection === 'patients') {
                    const section = document.getElementById('section-patients');
                    if (section && section.classList.contains('active')) {
                        renderPatients(true);
                    }
                } else if (targetSection === 'doctors') {
                    const section = document.getElementById('section-doctors');
                    if (section && section.classList.contains('active')) {
                        renderDoctors();
                    }
                } else if (targetSection === 'appointments') {
                    const section = document.getElementById('section-appointments');
                    if (section && section.classList.contains('active')) {
                        renderAppts();
                    }
                } else if (targetSection === 'logs') {
                    const section = document.getElementById('section-logs');
                    if (section && section.classList.contains('active')) {
                        renderLogs();
                    }
                } else if (targetSection === 'availability') {
                    const section = document.getElementById('section-availability');
                    if (section && section.classList.contains('active')) {
                        renderAvailability();
                    }
                }
            }, 100);
             
            // These are always loaded (not section-specific)
            renderReviews();
            renderAvailability();
             
            // Final persistence check - run multiple times to ensure data stays
            let checkCount = 0;
            const persistenceCheck = setInterval(() => {
                checkCount++;
                const section = document.getElementById('section-patients');
                const tbody = document.querySelector('#patientsTable tbody');
                
                if (section && section.classList.contains('active')) {
                    // Ensure section stays active (let CSS handle display)
                    section.classList.add('active');
                    section.style.display = '';
                    
                    // Check if data is missing and reload if needed
                    if (tbody && (!tbody.innerHTML || tbody.innerHTML.includes('Loading') || tbody.innerHTML.trim() === '')) {
                        if (!isRenderingPatients) {
                            renderPatients(true);
                        }
                    }
                }
                
                // Stop checking after 3 seconds
                if (checkCount >= 6) {
                    clearInterval(persistenceCheck);
                }
            }, 500);
            const sp = document.getElementById('searchPatients'); 
            if (sp) {
                sp.addEventListener('input', function() {
                    // Re-render with current cache but apply new search filter
                    if (patientsDataCache) {
                        renderPatients(true);
                    } else {
                        renderPatients();
                    }
                });
            }
            const sortSel = document.getElementById('sortPatients'); 
            if (sortSel) {
                sortSel.addEventListener('change', function() {
                    // Re-render with current cache but apply new sort
                    if (patientsDataCache) {
                        renderPatients(true);
                    } else {
                        renderPatients();
                    }
                });
            }
        const sd = document.getElementById('searchDoctors'); if (sd) sd.addEventListener('input', ()=>{ displayDoctors(); });
        const sortDoc = document.getElementById('sortDoctors'); if (sortDoc) sortDoc.addEventListener('change', ()=>{ displayDoctors(); });
            const clb = document.getElementById('createLogBtn'); if (clb) clb.addEventListener('click', createLog);
            const saveSettingsBtn = document.getElementById('saveSettings'); if (saveSettingsBtn) saveSettingsBtn.addEventListener('click', saveSettings);
            const btnSearchAv = document.getElementById('btnSearchAv'); if (btnSearchAv) btnSearchAv.addEventListener('click', searchAvailability);
            const btnResetAv = document.getElementById('btnResetAv'); if (btnResetAv) btnResetAv.addEventListener('click', resetAvailabilityFilters);
            loadSettings();
            // Fallback charts if API didn't render
            const visits = document.getElementById('chartVisits');
            if (visits){
                visits.innerHTML = `
                    <svg viewBox="0 0 600 220" width="100%" height="100%">
                        <defs>
                            <linearGradient id="gradLine" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#3b82f6" stop-opacity="0.35"/>
                                <stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/>
                            </linearGradient>
                        </defs>
                        <polyline fill="none" stroke="#60a5fa" stroke-width="3" points="0,150 60,140 120,130 180,120 240,150 300,115 360,130 420,110 480,125 540,100 600,120"/>
                        <polygon fill="url(#gradLine)" points="0,150 60,140 120,130 180,120 240,150 300,115 360,130 420,110 480,125 540,100 600,120 600,220 0,220"/>
                    </svg>
                `;
            }
            const pats = document.getElementById('chartPatients');
            if (pats){
                pats.innerHTML = `
                    <svg viewBox="0 0 300 220" width="100%" height="100%">
                        <rect x="20" y="120" width="26" height="80" rx="6" fill="#bfdbfe"/>
                        <rect x="60" y="60" width="26" height="140" rx="6" fill="#60a5fa"/>
                        <rect x="100" y="90" width="26" height="110" rx="6" fill="#bfdbfe"/>
                        <rect x="140" y="70" width="26" height="130" rx="6" fill="#60a5fa"/>
                        <rect x="180" y="140" width="26" height="60" rx="6" fill="#bfdbfe"/>
                        <rect x="220" y="80" width="26" height="120" rx="6" fill="#60a5fa"/>
                    </svg>
                `;
            }
        });

        // Settings load/save
        async function loadSettings(){
            try{
                const res = await fetch('api/settings.php');
                const json = await res.json();
                if(json.success && json.data){
                    const { timezone, theme, language } = json.data;
                    if (timezone) document.getElementById('tz').value = timezone;
                    if (theme) document.getElementById('theme').value = theme;
                    if (language) document.getElementById('lang').value = language;
                }
            }catch(e){ console.warn('Load settings failed', e); }
        }
        async function saveSettings(){
            const payload = { timezone: document.getElementById('tz').value, language: document.getElementById('lang').value, theme: document.getElementById('theme').value };
            const btn = document.getElementById('saveSettings'); const status = document.getElementById('settingsStatus');
            try{
                if(btn){ btn.disabled=true; btn.textContent='Saving...'; }
                const res = await fetch('api/settings.php', { method:'PUT', headers:{'Content-Type':'application/json'}, body: JSON.stringify(payload) });
                const json = await res.json();
                if (json.success){ if(status){ status.style.display='block'; setTimeout(()=>status.style.display='none',1500); } applyTheme(payload.theme); try{ localStorage.setItem('timezone', payload.timezone); }catch(_e){} }
                else { alert('Failed to save settings: '+(json.message||'Unknown')); }
            }catch(e){ alert('Save settings error'); }
            finally{ if(btn){ btn.disabled=false; btn.textContent='Save'; } }
        }
        function applyTheme(theme){
            const t = (theme==='dark') ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', t);
            try{ localStorage.setItem('theme', t); }catch(_e){}
        }

        function renderAnalyticsChart(appointments){
            const container = document.getElementById('analyticsChart');
            if (!container) return;
            if (!Array.isArray(appointments) || !appointments.length){
                container.innerHTML = '<div class="empty-state">No appointment data yet.</div>';
                return;
            }
            const months = new Array(12).fill(0);
            appointments.forEach(apt => {
                if (apt && apt.appt_date){
                    const d = new Date(apt.appt_date);
                    if (!isNaN(d.getTime())){
                        months[d.getMonth()]++;
                    }
                }
            });
            const now = new Date();
            const currentMonth = now.getMonth();
            const monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            const monthsToShow = [];
            for (let i=5;i>=0;i--){
                let idx = currentMonth - i;
                if (idx < 0) idx += 12;
                monthsToShow.push({ name: monthNames[idx], value: months[idx] || 0 });
            }
            const maxVal = Math.max(...monthsToShow.map(m => m.value), 1);
            let svg = '<svg viewBox="0 0 360 220" width="100%" height="100%">';
            let x = 30;
            monthsToShow.forEach(m => {
                const barHeight = m.value > 0 ? Math.max(24, (m.value / maxVal) * 140) : 12;
                const y = 180 - barHeight;
                svg += `<rect x="${x}" y="${y}" width="36" height="${barHeight}" rx="8" fill="#38bdf8" />`;
                svg += `<text x="${x + 18}" y="${y - 8}" text-anchor="middle" fill="#0f172a" font-size="11" font-weight="600">${m.value}</text>`;
                svg += `<text x="${x + 18}" y="202" text-anchor="middle" fill="#64748b" font-size="11">${m.name}</text>`;
                x += 55;
            });
            svg += '</svg>';
            container.innerHTML = svg;
        }

        document.getElementById('saveSettings').addEventListener('click', async ()=>{
            const timezone = document.getElementById('tz').value;
            const theme = document.getElementById('theme').value;
            const language = document.getElementById('lang').value;
            try{
                const res = await fetch('api/settings.php', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({ timezone, theme, language }) });
                const json = await res.json();
                if(json.success){
                    const status = document.getElementById('settingsStatus');
                    status.style.display='block';
                    status.textContent='Saved';
                    setTimeout(()=> status.style.display='none', 3000);
                }
            }catch(e){ console.warn('Save settings failed', e); }
        });

        function showChangePasswordStatus(message, isSuccess){
            const status = document.getElementById('changePasswordStatus');
            if (!status) return;
            if (!message){ status.style.display = 'none'; status.textContent=''; return; }
            status.textContent = message;
            status.style.display = 'block';
            status.style.color = isSuccess ? '#16a34a' : '#dc2626';
            status.style.marginLeft = '12px';
            if (isSuccess){
                setTimeout(()=>{ status.style.display='none'; }, 4000);
            }
        }

        function validateChangePasswordForm(){
            const currentPassword = document.getElementById('currentPassword');
            const newPassword = document.getElementById('newPassword');
            const confirmPassword = document.getElementById('confirmPassword');
            const curr = currentPassword.value.trim();
            const next = newPassword.value.trim();
            const confirm = confirmPassword.value.trim();

            if (!curr){ showChangePasswordStatus('Current password is required.', false); currentPassword.focus(); return false; }
            if (next.length < 8){ showChangePasswordStatus('New password must be at least 8 characters.', false); newPassword.focus(); return false; }
            if (!/[A-Z]/.test(next)){ showChangePasswordStatus('New password must contain at least one uppercase letter.', false); newPassword.focus(); return false; }
            if (!/[0-9]/.test(next)){ showChangePasswordStatus('New password must contain at least one number.', false); newPassword.focus(); return false; }
            if (!/[!@#$%^&*(),.?":{}|<>]/.test(next)){ showChangePasswordStatus('New password must contain at least one special character.', false); newPassword.focus(); return false; }
            if (curr === next){ showChangePasswordStatus('New password must be different from current password.', false); newPassword.focus(); return false; }
            if (next !== confirm){ showChangePasswordStatus('New password and confirm password must match.', false); confirmPassword.focus(); return false; }
            return true;
        }

        document.getElementById('changePasswordForm').addEventListener('submit', async (e)=>{
            e.preventDefault();
            if (!validateChangePasswordForm()) return;
            const profile = await ensureAdminProfile();
            if (!profile || !profile.id){
                showChangePasswordStatus('Unable to verify admin account. Please refresh and try again.', false);
                return;
            }
            const btn = document.getElementById('changePasswordBtn');
            btn.disabled = true;
            const prevText = btn.textContent;
            btn.textContent = 'Updating...';
            showChangePasswordStatus('', true);
            try{
                const payload = {
                    user_id: profile.id,
                    current_password: document.getElementById('currentPassword').value,
                    new_password: document.getElementById('newPassword').value,
                    confirm_password: document.getElementById('confirmPassword').value
                };
                const res = await fetch('api/change-password.php', {
                    method:'POST',
                    headers:{ 'Content-Type':'application/json' },
                    body: JSON.stringify(payload)
                });
                const text = await res.text();
                let json = null;
                try{ json = text ? JSON.parse(text) : null; }catch(parseErr){ console.error('Change password parse error', parseErr, text); }
                if (json && json.success){
                    document.getElementById('changePasswordForm').reset();
                    showChangePasswordStatus(json.message || 'Password updated successfully.', true);
                } else {
                    const message = (json && json.message) ? json.message : (text || 'Failed to change password.');
                    showChangePasswordStatus(message, false);
                }
            }catch(error){
                console.error('Change password error', error);
                showChangePasswordStatus('Error updating password. Please try again.', false);
            } finally {
                btn.disabled = false;
                btn.textContent = prevText;
            }
        });
    </script>
</body>
</html>

