<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - My Patients</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
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
            --primary-blue: #2563eb;
            --primary-blue-dark: #1d4ed8;
        }
        [data-theme="dark"]{
            --primary-blue: #3b82f6;
            --primary-blue-dark: #2563eb;
            --white: #0f172a;
            --gray-50:#0b1220;
            --gray-100:#111827;
            --gray-200:#1f2937;
            --gray-300:#374151;
            --gray-500:#9ca3af;
            --gray-600:#d1d5db;
            --gray-700:#e5e7eb;
            --gray-900:#ffffff;
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.3);
        }

        html, body { margin:0; background: var(--gray-50); color: var(--gray-900); transition: background 0.3s, color 0.3s; }
        body { font-family: 'Inter', var(--font-family, 'Inter', system-ui, -apple-system); min-height: 100vh; }

        .page { max-width: 1100px; margin: 40px auto; padding: 0 var(--spacing-6); }
        .header { display:flex; align-items:center; justify-content: space-between; margin-bottom: var(--spacing-6); }
        .logo { font-size: var(--font-size-2xl); font-weight: 800; color: var(--primary-blue); }
        .logo img{display:block;height:36px;width:auto;}
        .tagline { font-size: var(--font-size-sm); color: var(--gray-500); margin-top: 2px; }
        .theme-toggle { padding: 8px 14px; border-radius: 8px; border: 1px solid var(--gray-200); background: var(--white); color: var(--gray-700); cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .theme-toggle:hover { background: var(--gray-100); }
        .back { text-decoration:none; color: var(--gray-600); font-weight:600; }
        .back:hover { text-decoration: underline; }

        .grid { display:grid; gap: var(--spacing-6); }
        .card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius-xl); box-shadow: var(--shadow-md); padding: var(--spacing-6); position:relative; }
        .card-header { display:flex; flex-wrap:wrap; gap: var(--spacing-4); justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4); }
        .card-title { margin:0; font-size: var(--font-size-xl); font-weight: 700; color: var(--gray-900); }
        .card-subtitle { margin:0; color: var(--gray-500); font-size: var(--font-size-sm); }

        .filters { display:flex; flex-wrap:wrap; gap: var(--spacing-3); }
        .search-input { flex:1; min-width: 240px; padding: 10px 16px; border: 2px solid var(--gray-200); border-radius: var(--radius-md); font-family: inherit; }
        .search-input:focus { outline:none; border-color: var(--primary-blue); }
        .filter-select { padding: 10px 16px; border: 2px solid var(--gray-200); border-radius: var(--radius-md); font-family: inherit; background: var(--white); }
        .search-btn, .clear-btn { padding: 10px 16px; border-radius: var(--radius-md); border: none; cursor:pointer; font-weight:600; font-size: var(--font-size-sm); transition: all 0.2s ease; }
        .search-btn { background: var(--primary-blue); color: var(--white); }
        .search-btn:hover { background: var(--primary-blue-dark); }
        .clear-btn { background: var(--gray-100); color: var(--gray-700); }
        .clear-btn:hover { background: var(--gray-200); }

        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 12px 14px; border-bottom: 1px solid var(--gray-200); text-align:left; }
        .table th { color: var(--gray-600); font-weight: 600; }
        .table tbody tr:hover { background: var(--gray-100); }

        .pill { padding: 4px 10px; border-radius: 999px; font-size: var(--font-size-xs); font-weight: 700; display:inline-flex; align-items:center; gap:4px; }
        .pill.upcoming { background:#dbeafe; color:#1d4ed8; }
        .pill.completed { background:#dcfce7; color:#166534; }
        .pill.none { background:#f3f4f6; color:#4b5563; }

        .empty-state, .loading-state { text-align:center; padding: 3rem 1rem; color: var(--gray-500); }
        .loading-spinner { display:inline-block; width: 2rem; height: 2rem; border: 3px solid var(--gray-200); border-top-color: var(--primary-blue); border-radius: 50%; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .actions { display:flex; gap:8px; flex-wrap:wrap; }
        .btn-link { padding: 6px 12px; border-radius: var(--radius-md); border: 1px solid var(--primary-blue); background: rgba(37,99,235,0.08); color: var(--primary-blue); font-size: var(--font-size-xs); font-weight:600; cursor:pointer; transition: all 0.2s ease; }
        .btn-link:hover { background: rgba(37,99,235,0.16); }

        @media (max-width: 900px) {
            .filters { flex-direction: column; align-items: stretch; }
            .search-input { min-width: 100%; }
            .actions { flex-direction: column; align-items:flex-start; }
            .btn-link { width: 100%; text-align:center; }
            .table { font-size: var(--font-size-xs); }
        }
        @media (max-width: 600px) {
            .table { display: block; overflow-x: auto; }
        }
        .modal-overlay{display:none; position:fixed; inset:0; background:rgba(15,23,42,0.45); z-index:1200; align-items:center; justify-content:center; padding:20px;}
        .modal-panel{background:var(--white); border-radius:24px; padding:28px; width:100%; max-width:520px; box-shadow:var(--shadow-xl); max-height:90vh; overflow:auto;}
        .modal-header{display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;}
        .modal-header h3{margin:0; font-size:22px; color:var(--gray-900);}
        .modal-close{border:none; background:transparent; font-size:26px; line-height:1; color:var(--gray-400); cursor:pointer;}
        .notes-list{display:flex; flex-direction:column; gap:16px; margin-top:10px;}
        .note-card{border:1px solid var(--gray-200); border-radius:16px; padding:16px; background:var(--gray-50);}
        .note-title{font-weight:700; color:var(--gray-900); margin-bottom:6px;}
        .note-meta{font-size:12px; color:var(--gray-500); margin-bottom:10px;}
        .note-actions{display:flex; gap:8px;}
        .btn-small{padding:6px 10px; border-radius:10px; border:1px solid transparent; font-size:12px; font-weight:600; cursor:pointer;}
        .btn-note-edit{background:rgba(14,165,233,0.12); color:#0369a1; border-color:rgba(14,165,233,0.2);} 
        .btn-note-edit:hover{background:rgba(14,165,233,0.18);}
        .btn-note-delete{background:rgba(244,114,182,0.18); color:#be185d; border-color:rgba(244,114,182,0.3);} 
        .btn-note-delete:hover{background:rgba(244,114,182,0.26);}
        .note-empty{padding:18px; border-radius:16px; background:var(--gray-100); color:var(--gray-500); font-size:14px; text-align:center;}
        .note-form{display:flex; flex-direction:column; gap:12px; margin-top:16px;}
        .note-form label{font-weight:600; color:var(--gray-700);}
        .note-form input, .note-form textarea{width:100%; padding:10px 12px; border-radius:12px; border:1px solid var(--gray-200); font-family:inherit; font-size:14px; resize:vertical; min-height:44px;}
        .note-form textarea{min-height:110px;}
        .note-form input:focus, .note-form textarea:focus{outline:none; border-color:var(--primary-blue); box-shadow:0 0 0 2px rgba(37,99,235,0.15);}
        .note-form-actions{display:flex; justify-content:flex-end; gap:10px;}
        .btn-primary{background:var(--primary-blue); color:#fff; border:none; border-radius:12px; padding:10px 18px; font-weight:600; cursor:pointer;}
        .btn-primary:hover{background:var(--primary-blue-dark);} 
        .btn-secondary{background:var(--gray-100); color:var(--gray-600); border:none; border-radius:12px; padding:10px 18px; font-weight:600; cursor:pointer;}
        .btn-secondary:hover{background:var(--gray-200);} 
    </style>
    <link rel="icon" type="image/svg+xml" href="public/assets/doxi-icon.svg?v=1">
</head>
<body>
    <div class="page">
        <div class="header">
            <div>
                <div class="logo"><img src="public/assets/doxi-logo.svg?v=4" alt="DOXI" style="height:52px;width:auto;"></div>
                <div class="tagline">Doctor · Patient Directory</div>
            </div>
            <div style="display:flex; align-items:center; gap: var(--spacing-4);">
                <button class="theme-toggle" id="theme-toggle" onclick="toggleTheme()">
                    <span id="theme-icon">🌙</span>
                    <span id="theme-text">Dark</span>
                </button>
                <a class="back" href="doctor-dashboard.php">← Back to Dashboard</a>
            </div>
        </div>

        <div class="grid">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">My Patients</h2>
                        <p class="card-subtitle">Overview of every patient you've consulted</p>
                    </div>
                    <div class="filters">
                        <input type="text" class="search-input" id="patient-search" placeholder="Search by name or email..." onkeypress="handleSearchKey(event)">
                        <select class="filter-select" id="visit-filter" onchange="applyFilters()">
                            <option value="all">All visits</option>
                            <option value="upcoming">Upcoming appointment</option>
                            <option value="recent">Visited in last 30 days</option>
                            <option value="inactive">No visit in 90 days</option>
                        </select>
                        <button class="search-btn" onclick="applyFilters()">Search</button>
                        <button class="clear-btn" onclick="resetFilters()">Clear</button>
                    </div>
                </div>
                <div id="patients-content">
                    <div class="loading-state">
                        <div class="loading-spinner"></div>
                        <p style="margin-top: var(--spacing-4);">Loading patients...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        if (sessionStorage.getItem('isLoggedIn') !== 'true' || sessionStorage.getItem('userRole') !== 'doctor') {
            window.location.href = 'login.php';
        }

        let doctorId = null;
        let allPatients = [];
        let filteredPatients = [];
        let patientDetailsLoaded = false;

        function toggleTheme(){
            const current = document.documentElement.getAttribute('data-theme') || 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            applyTheme(next);
        }

        function applyTheme(theme){
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            updateThemeToggle(theme);
        }

        function updateThemeToggle(theme){
            const icon = document.getElementById('theme-icon');
            const text = document.getElementById('theme-text');
            if (!icon || !text) return;
            if (theme === 'dark') {
                icon.textContent = '☀️';
                text.textContent = 'Light';
            } else {
                icon.textContent = '🌙';
                text.textContent = 'Dark';
            }
        }

        (function(){
            try{
                const savedTheme = localStorage.getItem('theme') || 'light';
                updateThemeToggle(savedTheme);
            }catch(_e){}
        })();

        function handleSearchKey(event){
            if (event.key === 'Enter') {
                applyFilters();
            }
        }

        function resetFilters(){
            document.getElementById('patient-search').value = '';
            document.getElementById('visit-filter').value = 'all';
            filteredPatients = [...allPatients];
            renderPatients();
        }

        function applyFilters(){
            const term = document.getElementById('patient-search').value.trim().toLowerCase();
            const visitFilter = document.getElementById('visit-filter').value;
            const now = new Date();
            const thirtyDaysAgo = new Date(now.getTime() - 30 * 86400000);
            const ninetyDaysAgo = new Date(now.getTime() - 90 * 86400000);

            filteredPatients = allPatients.filter(patient => {
                const matchesSearch = !term || (patient.name.toLowerCase().includes(term) || patient.email.toLowerCase().includes(term));
                if (!matchesSearch) return false;

                if (visitFilter === 'upcoming') {
                    return patient.nextVisitDate !== null;
                }
                if (visitFilter === 'recent') {
                    return patient.lastVisitDate && patient.lastVisitDate >= thirtyDaysAgo;
                }
                if (visitFilter === 'inactive') {
                    return !patient.lastVisitDate || patient.lastVisitDate < ninetyDaysAgo;
                }
                return true;
            });

            renderPatients();
        }

        async function initializePatients(){
            if (!checkAuthentication()) {
                return;
            }

            const email = sessionStorage.getItem('userEmail');
            if (email && !doctorId) {
                await loadDoctorIdFromEmail(email);
            } else if (doctorId) {
                await loadPatients();
            }
        }

        function checkAuthentication(){
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

        async function loadDoctorIdFromEmail(email){
            try{
                const response = await fetch(`api/users.php?search=${encodeURIComponent(email)}&role=doctor&page=1&limit=1`);
                const result = await response.json();

                if (result.success && result.data && result.data.length > 0) {
                    doctorId = parseInt(result.data[0].id);
                    sessionStorage.setItem('userId', doctorId);
                    await loadPatients();
                } else {
                    sessionStorage.clear();
                    window.location.href = 'login.php';
                }
            }catch(error){
                console.error('Error loading doctor ID:', error);
                sessionStorage.clear();
                window.location.href = 'login.php';
            }
        }

        async function loadPatients(){
            const content = document.getElementById('patients-content');
            if (!doctorId) {
                content.innerHTML = '<div class="empty-state">Doctor ID not available</div>';
                return;
            }

            try {
                const response = await fetch(`api/appointments.php?doctor_id=${doctorId}`);
                const result = await response.json();

                if (!result.success || !Array.isArray(result.data)) {
                    content.innerHTML = '<div class="empty-state">No patient data available.</div>';
                    return;
                }

                const patientMap = new Map();
                result.data.forEach(apt => {
                    if (!apt || !apt.patient_id) return;
                    const id = String(apt.patient_id);
                    if (!patientMap.has(id)) {
                        patientMap.set(id, {
                            id: apt.patient_id,
                            name: (apt.patient_name || `Patient #${apt.patient_id}`).trim(),
                            email: (apt.patient_email || '-').trim(),
                            totalVisits: 0,
                            lastVisitDate: null,
                            lastVisitDisplay: '—',
                            lastVisitStatus: '',
                            nextVisitDate: null,
                            nextVisitDisplay: '—'
                        });
                    }

                    const entry = patientMap.get(id);
                    entry.totalVisits += 1;

                    if (apt.appt_date) {
                        const comparisonDate = makeDateForComparisons(apt.appt_date, apt.appt_time);
                        if (comparisonDate) {
                            if (!entry.lastVisitDate || comparisonDate > entry.lastVisitDate) {
                                entry.lastVisitDate = comparisonDate;
                                entry.lastVisitDisplay = formatDateTimeParts(apt.appt_date, apt.appt_time);
                                entry.lastVisitStatus = (apt.status || '').toLowerCase();
                            }
                            const status = (apt.status || '').toLowerCase();
                            if (status === 'scheduled' || status === 'confirmed') {
                                if (!entry.nextVisitDate || comparisonDate < entry.nextVisitDate) {
                                    entry.nextVisitDate = comparisonDate;
                                    entry.nextVisitDisplay = formatDateTimeParts(apt.appt_date, apt.appt_time);
                                }
                            }
                        }
                    }
                });

                allPatients = Array.from(patientMap.values()).sort((a, b) => a.name.localeCompare(b.name));
                filteredPatients = [...allPatients];

                await enrichPatientDetails();
                renderPatients();
                applyInitialQuery();
            } catch (error) {
                console.error('Error loading patients:', error);
                content.innerHTML = '<div class="empty-state">Error loading patients. Please try again.</div>';
            }
        }

        async function enrichPatientDetails(){
            if (patientDetailsLoaded || !allPatients.length) return;
            try {
                const requests = allPatients.map(async patient => {
                    try{
                        const res = await fetch(`api/users.php?id=${patient.id}`);
                        const json = await res.json();
                        if (json.success && json.data) {
                            const data = json.data;
                            patient.phone = data.phone || '-';
                            patient.gender = data.gender || '-';
                            patient.date_of_birth = data.date_of_birth || null;
                        }
                    }catch(err){
                        console.warn('Failed to fetch patient details', err);
                    }
                });
                await Promise.all(requests);
            } finally {
                patientDetailsLoaded = true;
            }
        }

        function applyInitialQuery(){
            const params = new URLSearchParams(window.location.search);
            const searchParam = params.get('search') || params.get('patientName');
            if (searchParam) {
                document.getElementById('patient-search').value = searchParam;
                applyFilters();
            }
        }

        function renderPatients(){
            const content = document.getElementById('patients-content');
            if (!filteredPatients.length) {
                content.innerHTML = '<div class="empty-state">No patients match your filters.</div>';
                return;
            }

            const rows = filteredPatients.map(patient => {
                const nextLabel = patient.nextVisitDate ? `<span class="pill upcoming">Upcoming</span>` : `<span class="pill none">No upcoming</span>`;
                const lastStatus = patient.lastVisitStatus;
                let lastStatusPill = '';
                if (lastStatus === 'completed') {
                    lastStatusPill = '<span class="pill completed">Completed</span>';
                } else if (lastStatus === 'scheduled' || lastStatus === 'confirmed') {
                    lastStatusPill = '<span class="pill upcoming">Scheduled</span>';
                } else if (lastStatus) {
                    lastStatusPill = `<span class="pill none">${lastStatus}</span>`;
                }

                const phone = patient.phone ? `<div style="color: var(--gray-500); font-size: var(--font-size-xs);">${patient.phone}</div>` : '';

                return `
                    <tr>
                        <td>
                            <div style="font-weight:600; color: var(--gray-900);">${escapeHtml(patient.name)}</div>
                            <div style="color: var(--gray-500); font-size: var(--font-size-xs);">${escapeHtml(patient.email)}</div>
                            ${phone}
                        </td>
                        <td>${patient.totalVisits}</td>
                        <td>
                            <div>${patient.lastVisitDisplay}</div>
                            ${lastStatusPill}
                        </td>
                        <td>
                            <div>${patient.nextVisitDisplay}</div>
                            ${nextLabel}
                        </td>
                        <td>
                            <div class="actions">
                                <button class="btn-link" onclick="openAppointmentsForPatient(${patient.id}, '${encodeURIComponent(patient.name)}')">View Appointments</button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            content.innerHTML = `
                <div style="overflow-x:auto;">
                    <table class="table" aria-label="Patient list">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Total Visits</th>
                                <th>Last Visit</th>
                                <th>Next Visit</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rows}
                        </tbody>
                    </table>
                </div>
            `;
        }

        function openAppointmentsForPatient(patientId, encodedName){
            const name = decodeURIComponent(encodedName);
            const params = new URLSearchParams();
            params.set('patientId', patientId);
            params.set('patientName', name);
            window.location.href = `doctor-appointment.php?${params.toString()}`;
        }

        function formatDateTime(date){
            if (!(date instanceof Date) || Number.isNaN(date.getTime())) return '—';
            const options = { month:'short', day:'numeric', year:'numeric', hour:'2-digit', minute:'2-digit' };
            return date.toLocaleString(undefined, options);
        }

        function formatDateTimeParts(dateStr, timeStr){
            if (!dateStr) return '—';
            const [year, month, day] = dateStr.split('-').map(Number);
            const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            const dateDisplay = `${months[(month-1+12)%12]} ${String(day).padStart(2,'0')}, ${year}`;
            if (!timeStr) return dateDisplay;
            const [hourRaw, minuteRaw] = timeStr.split(':').map(Number);
            if (Number.isNaN(hourRaw) || Number.isNaN(minuteRaw)) return dateDisplay;
            const meridiem = hourRaw >= 12 ? 'PM' : 'AM';
            let hour12 = hourRaw % 12;
            if (hour12 === 0) hour12 = 12;
            const timeDisplay = `${hour12}:${String(minuteRaw).padStart(2,'0')} ${meridiem}`;
            return `${dateDisplay} · ${timeDisplay}`;
        }

        function makeDateForComparisons(dateStr, timeStr){
            if (!dateStr) return null;
            const iso = `${dateStr}T${timeStr || '00:00'}Z`;
            const dt = new Date(iso);
            return Number.isNaN(dt.getTime()) ? null : dt;
        }

        function escapeHtml(text){
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }


        document.addEventListener('DOMContentLoaded', initializePatients);
    </script>
</body>
</html>
