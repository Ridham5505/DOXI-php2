<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Medical Reports</title>
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
        .page { max-width: 1100px; margin: 40px auto; padding: 0 var(--spacing-6); }
        .header { display:flex; align-items:center; justify-content: space-between; margin-bottom: var(--spacing-6); }
        .card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius-xl); box-shadow: var(--shadow-md); padding: var(--spacing-6); }
        .report-card { padding: var(--spacing-5); margin-bottom: var(--spacing-4); border-left: 4px solid var(--primary-blue); }
        .report-title { font-weight: 700; color: var(--gray-900); font-size: var(--font-size-lg); margin-bottom: var(--spacing-2); }
        .report-meta { color: var(--gray-600); font-size: var(--font-size-sm); margin-bottom: var(--spacing-3); }
        .report-desc { color: var(--gray-700); line-height: 1.6; margin-bottom: var(--spacing-3); }
        .report-type { display: inline-block; padding: 4px 10px; border-radius: 9999px; font-size: var(--font-size-xs); font-weight: 700; background: var(--light-blue); color: var(--primary-blue); }
        .empty { padding: var(--spacing-6); text-align:center; color: var(--gray-500); }
        .back { text-decoration:none; color: var(--gray-600); }
    </style>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📄</text></svg>">
</head>
<body>
    <div class="page">
        <div class="header">
            <div>
                <div class="logo">DOXI</div>
                <div class="tagline">Medical Reports</div>
            </div>
            <div style="display:flex; align-items:center; gap: var(--spacing-4);">
                <button class="theme-toggle" id="theme-toggle" onclick="toggleTheme()" style="padding: 8px 14px; border-radius: 8px; border: 1px solid var(--gray-200); background: var(--white); color: var(--gray-700); cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s;">
                    <span id="theme-icon">🌙</span>
                    <span id="theme-text">Dark</span>
                </button>
                <a class="back" href="patient-dashboard.php">← Back to Dashboard</a>
            </div>
        </div>

        <div class="card">
            <h3 style="margin-bottom: var(--spacing-4); color: var(--gray-900);">My Medical Reports</h3>
            <div id="reports-list"></div>
        </div>
    </div>

    <script>
        if (sessionStorage.getItem('isLoggedIn') !== 'true' || sessionStorage.getItem('userRole') !== 'patient') {
            window.location.href = 'login.php';
        }

        let currentPatientId = null;
        function getJSON(url){ return fetch(url).then(r=>r.json()); }

        async function loadCurrentPatient(){
            const email = sessionStorage.getItem('userEmail');
            const res = await getJSON(`api/users.php?search=${encodeURIComponent(email)}&page=1&limit=1`);
            if (res.success && res.data && res.data.length){ currentPatientId = res.data[0].id; }
        }

        async function loadReports(){
            if (!currentPatientId) return;
            const res = await getJSON(`api/patients.php?id=${currentPatientId}`);
            const container = document.getElementById('reports-list');
            container.innerHTML = '';
            
            if (!res.success){
                container.innerHTML = `<div class="empty">Failed to load reports.</div>`;
                return;
            }

            const reports = res.data?.medical_records || [];
            if (!reports.length){
                container.innerHTML = `<div class="empty">No medical reports available.</div>`;
                return;
            }

            reports.forEach(r=>{
                const el = document.createElement('div');
                el.className = 'card report-card';
                const date = r.created_at ? new Date(r.created_at).toLocaleDateString() : 'N/A';
                const type = (r.record_type || 'report').toLowerCase();
                el.innerHTML = `
                    <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom: var(--spacing-2);">
                        <div class="report-title">${r.title || 'Untitled Report'}</div>
                        <span class="report-type">${type}</span>
                    </div>
                    <div class="report-meta">Date: ${date} ${r.doctor_id ? '· Doctor ID: ' + r.doctor_id : ''}</div>
                    ${r.description ? `<div class="report-desc">${r.description}</div>` : ''}
                    ${r.file_path ? `<a href="${r.file_path}" class="btn btn-outline" target="_blank">View File</a>` : ''}
                `;
                container.appendChild(el);
            });
        }

        (async function init(){
            await loadCurrentPatient();
            await loadReports();
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
