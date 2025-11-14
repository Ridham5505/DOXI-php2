<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Medical History</title>
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
        body { background: var(--gray-50); }
        .page { max-width: 1200px; margin: 40px auto; padding: 0 var(--spacing-6); }
        .header { display:flex; align-items:center; justify-content: space-between; margin-bottom: var(--spacing-6); }
        .card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius-xl); box-shadow: var(--shadow-md); padding: var(--spacing-6); margin-bottom: var(--spacing-6); }
        .section-title { font-size: var(--font-size-xl); font-weight: 700; color: var(--gray-900); margin-bottom: var(--spacing-4); padding-bottom: var(--spacing-3); border-bottom: 2px solid var(--primary-blue); }
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--spacing-4); }
        .info-item { display: flex; flex-direction: column; gap: var(--spacing-1); }
        .info-label { font-size: var(--font-size-sm); color: var(--gray-600); font-weight: 600; }
        .info-value { font-size: var(--font-size-base); color: var(--gray-900); font-weight: 500; }
        .table { width: 100%; border-collapse: collapse; margin-top: var(--spacing-4); }
        .table th, .table td { padding: 12px 14px; border-bottom: 1px solid var(--gray-200); text-align: left; }
        .table th { color: var(--gray-600); font-weight: 600; background: var(--gray-50); }
        .pill { padding: 4px 10px; border-radius: 9999px; font-size: var(--font-size-xs); font-weight: 700; }
        .pill.scheduled { background:#e0e7ff; color:#3730a3; }
        .pill.completed { background:#dcfce7; color:#166534; }
        .pill.cancelled { background:#fee2e2; color:#991b1b; }
        .empty { padding: var(--spacing-6); text-align:center; color: var(--gray-500); }
        .back { text-decoration:none; color: var(--gray-600); }
        .loading { text-align: center; padding: var(--spacing-6); color: var(--gray-500); }
        .logo { font-size: var(--font-size-2xl); font-weight: 800; color: var(--primary-blue); }
        .logo img{display:block;height:36px;width:auto;}
    </style>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📋</text></svg>">
</head>
<body>
    <div class="page">
        <div class="header">
            <div>
                <div class="logo"><img src="public/assets/doxi-logo.svg?v=2" alt="DOXI" width="120" height="36"></div>
                <div class="tagline">Medical History</div>
            </div>
            <div style="display:flex; align-items:center; gap: var(--spacing-4);">
                <button class="theme-toggle" id="theme-toggle" onclick="toggleTheme()" style="padding: 8px 14px; border-radius: 8px; border: 1px solid var(--gray-200); background: var(--white); color: var(--gray-700); cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s;">
                    <span id="theme-icon">🌙</span>
                    <span id="theme-text">Dark</span>
                </button>
                <a class="back" href="patient-dashboard.php">← Back to Dashboard</a>
            </div>
        </div>

        <!-- Patient Information -->
        <div class="card">
            <h2 class="section-title">Personal Information</h2>
            <div id="patient-info" class="info-grid">
                <div class="loading">Loading patient information...</div>
            </div>
        </div>


        <!-- Appointment History -->
        <div class="card">
            <h2 class="section-title">Appointment History</h2>
            <table class="table" id="appointments-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Doctor</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td colspan="4" class="loading">Loading appointments...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        if (sessionStorage.getItem('isLoggedIn') !== 'true' || sessionStorage.getItem('userRole') !== 'patient') {
            window.location.href = 'login.php';
        }

        let currentPatientId = null;
        function getJSON(url){ return fetch(url).then(r=>r.json()); }

        function esc(s){ return (s||'').toString().replace(/[&<>'"]/g, c=>({"&":"&amp;","<":"&lt;",">":"&gt;","'":"&#39;",'"':"&quot;"}[c])); }

        async function loadCurrentPatient(){
            const email = sessionStorage.getItem('userEmail');
            // Try patients.php first (more complete data), fallback to users.php
            let res = await getJSON(`api/patients.php?email=${encodeURIComponent(email)}`).catch(()=>null);
            if (res && res.success && res.data){
                currentPatientId = res.data.id;
                const patient = {...res.data};
                return patient;
            }
            // Fallback to users.php
            res = await getJSON(`api/users.php?search=${encodeURIComponent(email)}&page=1&limit=1`);
            if (res.success && res.data && res.data.length){ 
                currentPatientId = res.data[0].id;
                return res.data[0];
            }
            return null;
        }

        async function loadPatientInfo(patient){
            if (!patient) return;
            const container = document.getElementById('patient-info');
            const gender = (patient.gender && patient.gender.trim() !== '') ? patient.gender : 'Not specified';
            const dob = patient.date_of_birth ? new Date(patient.date_of_birth).toLocaleDateString() : 'Not specified';
            const phone = patient.phone || 'Not specified';
            const address = patient.address || 'Not specified';
            const email = patient.email || 'Not specified';
            const joined = patient.created_at ? new Date(patient.created_at).toLocaleDateString() : 'N/A';
            
            container.innerHTML = `
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value">${esc(patient.first_name || '')} ${esc(patient.last_name || '')}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">${esc(email)}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phone</div>
                    <div class="info-value">${esc(phone)}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Date of Birth</div>
                    <div class="info-value">${esc(dob)}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Gender</div>
                    <div class="info-value">${esc(gender)}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Address</div>
                    <div class="info-value">${esc(address)}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Member Since</div>
                    <div class="info-value">${esc(joined)}</div>
                </div>
            `;
        }

        async function loadAppointmentHistory(){
            if (!currentPatientId) return;
            const res = await getJSON(`api/appointments.php?patient_id=${currentPatientId}`);
            const tbody = document.querySelector('#appointments-table tbody');
            
            if (!res.success || !res.data || res.data.length === 0){
                tbody.innerHTML = `<tr><td colspan="4" class="empty">No appointment history available.</td></tr>`;
                return;
            }

            tbody.innerHTML = '';
            const now = new Date();
            res.data.sort((a,b)=>{
                const da = new Date(`${a.appt_date}T${(a.appt_time||'00:00').substring(0,5)}:00`);
                const db = new Date(`${b.appt_date}T${(b.appt_time||'00:00').substring(0,5)}:00`);
                return db - da; // newest first
            }).forEach(a=>{
                const tr = document.createElement('tr');
                const apptDt = new Date(`${a.appt_date}T${(a.appt_time||'00:00').substring(0,5)}:00`);
                const isPast = !isNaN(apptDt.getTime()) && apptDt < now;
                const computedStatus = (isPast && a.status !== 'cancelled') ? 'completed' : a.status;
                tr.innerHTML = `
                    <td>${esc(a.appt_date || 'N/A')}</td>
                    <td>${a.appt_time ? esc(a.appt_time.substring(0,5)) : 'N/A'}</td>
                    <td>${esc(a.doctor_name || ('Doctor #' + a.doctor_id))}</td>
                    <td><span class="pill ${computedStatus}">${esc(computedStatus)}</span></td>
                `;
                tbody.appendChild(tr);
            });
        }

        (async function init(){
            const patient = await loadCurrentPatient();
            if (patient){
                await Promise.all([
                    loadPatientInfo(patient),
                    loadAppointmentHistory()
                ]);
            } else {
                document.getElementById('patient-info').innerHTML = '<div class="empty">Failed to load patient information.</div>';
            }
        })();

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

        (function(){
            try{
                const savedTheme = localStorage.getItem('theme') || 'light';
                updateThemeToggle(savedTheme);
            }catch(_e){}
        })();
    </script>
</body>
</html>
