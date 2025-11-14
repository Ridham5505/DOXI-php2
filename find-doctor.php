<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Find Doctor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="public/assets/doxi-icon.svg?v=1">
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
            --gray-400:#6b7280;
            --gray-500:#9ca3af;
            --gray-600:#d1d5db;
            --gray-700:#e5e7eb;
            --gray-800:#f3f4f6;
            --gray-900:#ffffff;
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -1px rgba(0, 0, 0, 0.3);
        }
        html, body { margin:0; background: var(--gray-50); color: var(--gray-900); transition: background 0.3s, color 0.3s; }
        body { background: var(--gray-50); }
        .page { max-width: 1100px; margin: 40px auto; padding: 0 var(--spacing-6); }
        .header { display:flex; align-items:center; justify-content: space-between; margin-bottom: var(--spacing-6); }
        .filters { display:grid; grid-template-columns: 1.2fr .8fr .8fr; gap: var(--spacing-4); }
        @media(max-width: 900px){ .filters{ grid-template-columns: 1fr; }}
        .card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius-xl); box-shadow: var(--shadow-md); }
        .doctor-card { display:grid; grid-template-columns: 1fr auto; gap: var(--spacing-3); padding: var(--spacing-5); align-items:center; }
        .doc-name { font-weight:700; color: var(--gray-900); font-size: var(--font-size-lg); }
        .doc-meta { color: var(--gray-600); font-size: var(--font-size-sm); }
        .actions { display:flex; gap: var(--spacing-3); }
        .grid { display:grid; gap: var(--spacing-4); }
        .empty { padding: var(--spacing-6); text-align:center; color: var(--gray-500); }
        .btn { padding: var(--spacing-3) var(--spacing-6); border-radius: var(--radius-md); border: none; font-weight: 600; cursor: pointer; transition: all 0.3s; text-decoration: none; display: inline-block; }
        .btn-primary { background: var(--primary-blue); color: var(--white); }
        .btn-primary:hover { background: var(--primary-blue-dark); }
        .btn-outline { background: var(--white); color: var(--primary-blue); border: 2px solid var(--primary-blue); }
        .btn-outline:hover { background: var(--primary-blue); color: var(--white); }
        input, select { background: var(--white); color: var(--gray-900); transition: background 0.3s, border-color 0.3s; }
        input:focus, select:focus { outline: none; border-color: var(--primary-blue); }
        .logo { font-size: var(--font-size-2xl); font-weight: 800; color: var(--primary-blue); transition: color 0.3s; }
        .logo img{display:block;height:36px;width:auto;}
        .tagline { color: var(--gray-600); font-size: var(--font-size-sm); font-weight: 500; transition: color 0.3s; }
        .back { text-decoration: none; color: var(--gray-600); transition: color 0.3s; }
        .back:hover { color: var(--gray-900); }
        .card { transition: background 0.3s, border-color 0.3s, box-shadow 0.3s; }
        .doctor-card { border-bottom: 1px solid var(--gray-200); transition: border-color 0.3s, background 0.3s; }
        .doctor-card:last-child { border-bottom: none; }
        .doctor-card:hover { background: var(--gray-50); }
        .theme-toggle { padding: 8px 14px; border-radius: 8px; border: 1px solid var(--gray-200); background: var(--white); color: var(--gray-700); cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .theme-toggle:hover { background: var(--gray-100); border-color: var(--gray-300); }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div>
                <div class="logo"><img src="public/assets/doxi-logo.svg?v=4" alt="DOXI" style="height:52px;width:auto;"></div>
                <div class="tagline">Find Doctor</div>
            </div>
            <div style="display:flex; align-items:center; gap: var(--spacing-4);">
                <button class="theme-toggle" id="theme-toggle" onclick="toggleTheme()" style="padding: 8px 14px; border-radius: 8px; border: 1px solid var(--gray-200); background: var(--white); color: var(--gray-700); cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s;">
                    <span id="theme-icon">🌙</span>
                    <span id="theme-text">Dark</span>
                </button>
                <a class="back" href="patient-dashboard.php">← Back to Dashboard</a>
            </div>
        </div>

        <div class="card" style="padding: var(--spacing-5); margin-bottom: var(--spacing-4);">
            <div class="filters">
                <div>
                    <label for="q" style="display:block; font-weight:600; color:var(--gray-700); margin-bottom:6px;">Search</label>
                    <input id="q" type="text" placeholder="Name, email or phone" style="width:100%; padding:10px 12px; border:2px solid var(--gray-200); border-radius:10px; background: var(--white); color: var(--gray-900);">
                </div>
                <div>
                    <label for="specialty" style="display:block; font-weight:600; color:var(--gray-700); margin-bottom:6px;">Specialty</label>
                    <select id="specialty" style="width:100%; padding:10px 12px; border:2px solid var(--gray-200); border-radius:10px; background: var(--white); color: var(--gray-900);">
                        <option value="">All</option>
                    </select>
                </div>
                <div style="display:flex; align-items:flex-end; gap: var(--spacing-3);">
                    <button id="btn-search" class="btn btn-primary">Search</button>
                    <button id="btn-reset" class="btn btn-outline">Reset</button>
                </div>
            </div>
        </div>

        <div id="results" class="grid"></div>
    </div>

    <script>
        if (sessionStorage.getItem('isLoggedIn') !== 'true' || sessionStorage.getItem('userRole') !== 'patient') {
            window.location.href = 'login.php';
        }

        function getJSON(url){ return fetch(url).then(r=>r.json()); }
        function esc(s){ return (s||'').replace(/[&<>]/g,c=>({"&":"&amp;","<":"&lt;",">":"&gt;"}[c])); }

        let state = { q: '', specialty: '' };

        async function loadDoctors(){
            const params = new URLSearchParams();
            params.set('role','doctor');
            if (state.q) params.set('search', state.q);
            params.set('page', 1);
            params.set('limit', 1000); // Fetch all doctors
            const res = await getJSON('api/users.php?' + params.toString());
            const list = document.getElementById('results');
            list.innerHTML = '';
            if (!res.success){ list.innerHTML = `<div class="card empty">Failed to load doctors.</div>`; return; }

            // Build specialty options from results if available
            const specSel = document.getElementById('specialty');
            const specs = new Set();
            (res.data||[]).forEach(u=>{ if (u.specialty) specs.add(u.specialty); });
            const currentSpecs = Array.from(specSel.options).map(o=>o.value);
            if (Array.from(specs).some(s=>!currentSpecs.includes(s))){
                specSel.innerHTML = '<option value="">All</option>' + Array.from(specs).map(s=>`<option value="${esc(s)}">${esc(s)}</option>`).join('');
            }

            // Filter client-side by specialty if chosen
            let doctors = res.data || [];
            if (state.specialty) doctors = doctors.filter(d=> (d.specialty||'') === state.specialty);

            if (!doctors.length){ list.innerHTML = `<div class="card empty">No doctors found.</div>`; }
            doctors.forEach(d=>{
                const name = `${d.first_name||''} ${d.last_name||''}`.trim() || d.email;
                const specialty = d.specialty || 'General';
                const phone = d.phone || 'N/A';
                const el = document.createElement('div');
                el.className = 'card doctor-card';
                el.innerHTML = `
                    <div>
                        <div class="doc-name">Dr. ${esc(name)}</div>
                        <div class="doc-meta">Specialty: ${esc(specialty)}</div>
                        <div class="doc-meta">Phone: ${esc(phone)}</div>
                    </div>
                    <div class="actions">
                        <a class="btn btn-primary" href="book-appointment.php#doctor=${d.id}">Book</a>
                    </div>`;
                list.appendChild(el);
            });
        }

        document.getElementById('btn-search').addEventListener('click', ()=>{ state.q = document.getElementById('q').value.trim(); state.specialty = document.getElementById('specialty').value; loadDoctors(); });
        document.getElementById('btn-reset').addEventListener('click', ()=>{ state = { q:'', specialty:'' }; document.getElementById('q').value=''; document.getElementById('specialty').value=''; loadDoctors(); });

        (async function init(){
            await loadDoctors();
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


