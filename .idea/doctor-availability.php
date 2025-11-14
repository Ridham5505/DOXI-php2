<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Doctor Availability</title>
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
        .filters { display:grid; grid-template-columns: 1.5fr 1fr 1fr; gap: var(--spacing-4); margin-bottom: var(--spacing-4); }
        @media(max-width: 900px){ .filters{ grid-template-columns: 1fr; }}
        label { font-weight:600; color: var(--gray-700); margin-bottom: var(--spacing-2); display:block; }
        select, input { width:100%; padding: var(--spacing-3) var(--spacing-4); border: 2px solid var(--gray-200); border-radius: var(--radius-md); font-family: var(--font-family); }
        .back { text-decoration:none; color: var(--gray-600); }
        .availability-grid { display:grid; gap: var(--spacing-4); }
        .doctor-availability { border: 1px solid var(--gray-200); border-radius: var(--radius-lg); padding: var(--spacing-4); background: var(--gray-50); }
        .doctor-header { display:flex; justify-content:space-between; align-items:center; margin-bottom: var(--spacing-3); }
        .doctor-name { font-weight:700; color: var(--gray-900); font-size: var(--font-size-lg); }
        .doctor-specialty { color: var(--gray-600); font-size: var(--font-size-sm); }
        .time-slots { display:grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: var(--spacing-2); }
        .time-slot { padding: var(--spacing-2) var(--spacing-3); border-radius: var(--radius-md); text-align:center; font-size: var(--font-size-sm); font-weight:600; }
        .time-slot.available { background: #dcfce7; color: #166534; border: 2px solid #86efac; }
        .time-slot.booked { background: #fee2e2; color: #991b1b; border: 2px solid #fca5a5; cursor: not-allowed; opacity: 0.6; }
        .time-slot.past { background: var(--gray-200); color: var(--gray-500); border: 2px solid var(--gray-300); }
        .time-slot.unavailable { background: #ede9fe; color: #5b21b6; border: 2px solid #c4b5fd; opacity: 0.85; }
        .empty { padding: var(--spacing-6); text-align:center; color: var(--gray-500); }
        .legend { display:flex; gap: var(--spacing-4); margin-top: var(--spacing-4); padding-top: var(--spacing-4); border-top: 1px solid var(--gray-200); }
        .legend-item { display:flex; align-items:center; gap: var(--spacing-2); font-size: var(--font-size-sm); }
        .legend-color { width: 20px; height: 20px; border-radius: var(--radius-md); border: 2px solid; }
    </style>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🗓️</text></svg>">
</head>
<body>
    <div class="page">
        <div class="header">
            <div>
                <div class="logo">DOXI</div>
                <div class="tagline">Doctor Availability</div>
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
            <div class="filters">
                <div>
                    <label for="doctor-filter">Doctor <span style="font-weight:400; color: var(--gray-500);">(Required)</span></label>
                    <select id="doctor-filter" required>
                        <option value="" selected disabled>Select Doctor...</option>
                    </select>
                </div>
                <div>
                    <label for="date-filter">Date</label>
                    <input id="date-filter" type="date" />
                </div>
                <div style="display:flex; align-items:flex-end; gap: var(--spacing-3);">
                    <button id="btn-search" class="btn btn-primary">Search</button>
                    <button id="btn-reset" class="btn btn-outline">Reset</button>
                </div>
            </div>
            
            <div class="legend">
                <div class="legend-item">
                    <div class="legend-color" style="background:#dcfce7; border-color:#86efac;"></div>
                    <span>Available</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background:#fee2e2; border-color:#fca5a5;"></div>
                    <span>Booked</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background:var(--gray-200); border-color:var(--gray-300);"></div>
                    <span>Past Time</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color" style="background:#ede9fe; border-color:#c4b5fd;"></div>
                    <span>Unavailable</span>
                </div>
            </div>
        </div>

        <div id="availability-results" class="availability-grid"></div>
    </div>

    <script>
        if (sessionStorage.getItem('isLoggedIn') !== 'true' || sessionStorage.getItem('userRole') !== 'patient') {
            window.location.href = 'login.php';
        }

        function getJSON(url){ return fetch(url).then(r=>r.json()); }
        function esc(s){ return (s||'').toString().replace(/[&<>'"]/g, c=>({"&":"&amp;","<":"&lt;",">":"&gt;","'":"&#39;",'"':"&quot;"}[c])); }

        let allDoctors = [];
        let allAppointments = [];
        let availabilityEntries = [];

        async function loadDoctors(){
            const res = await getJSON('api/users.php?role=doctor&page=1&limit=100');
            if (res.success){
                allDoctors = res.data || [];
                const sel = document.getElementById('doctor-filter');
                allDoctors.forEach(d=>{
                    const o = document.createElement('option');
                    o.value = d.id;
                    o.textContent = `Dr. ${(d.first_name||'')} ${(d.last_name||'')}`.trim() || d.email;
                    sel.appendChild(o);
                });
            }
        }

        async function loadAppointments(doctorId, date){
            let url = 'api/appointments.php?limit=500';
            if (doctorId) url += `&doctor_id=${doctorId}`;
            if (date) url += `&date=${date}`;
            
            const res = await getJSON(url);
            if (res.success){
                allAppointments = res.data || [];
            } else {
                allAppointments = [];
            }
        }

        async function loadAvailabilityEntries(doctorId, date){
            let url = `api/availability.php?doctor_id=${doctorId}`;
            if (date) url += `&date=${date}`;
            try{
                const res = await getJSON(url);
                if (res.success){
                    availabilityEntries = res.data || [];
                } else {
                    availabilityEntries = [];
                }
            }catch(e){
                availabilityEntries = [];
            }
        }

        function generateTimeSlots(){
            const slots = [];
            for (let h = 10; h <= 19; h++){
                for (let m = 0; m < 60; m += 15){
                    const hh = String(h).padStart(2,'0');
                    const mm = String(m).padStart(2,'0');
                    slots.push(`${hh}:${mm}`);
                }
            }
            return slots;
        }

        function toMinutes(timeString){
            if (!timeString) return null;
            const [hh, mm] = timeString.split(':');
            return parseInt(hh, 10) * 60 + parseInt(mm, 10);
        }

        function isWithinRanges(timeString, ranges){
            const minutes = toMinutes(timeString);
            if (minutes === null) return false;
            return ranges.some(range => {
                const start = toMinutes(range.start_time.substring(0,5));
                const end = toMinutes(range.end_time.substring(0,5));
                return start !== null && end !== null && minutes >= start && minutes < end;
            });
        }

        function isSlotBooked(doctorId, date, time){
            if (!date || !time) return false;
            return allAppointments.some(a=>{
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

        function minutesToString(totalMinutes){
            const hours = Math.floor(totalMinutes / 60);
            const minutes = totalMinutes % 60;
            return `${String(hours).padStart(2,'0')}:${String(minutes).padStart(2,'0')}`;
        }

        function displayAvailability(doctorId, date){
            const container = document.getElementById('availability-results');
            container.innerHTML = '';
            
            if (!doctorId){
                container.innerHTML = '<div class="card empty">Please select a doctor to view availability.</div>';
                return;
            }

            if (!date){
                container.innerHTML = '<div class="card empty">Please select a date to view availability.</div>';
                return;
            }

            const doctor = allDoctors.find(d=> d.id == doctorId);
            if (!doctor){
                container.innerHTML = '<div class="card empty">Doctor not found.</div>';
                return;
            }

            const entries = availabilityEntries.filter(entry => entry.availability_date === date);
            const slottedTimes = new Map();

            const availableRanges = entries.filter(entry => entry.status === 'available');
            const unavailableRanges = entries.filter(entry => entry.status !== 'available');

            if (!availableRanges.length){
                // Fall back to default clinic hours for display
                for (let h = 10; h <= 19; h++){
                    for (let m = 0; m < 60; m += 15){
                        const time = minutesToString(h*60 + m);
                        if (isSlotPast(date, time)){
                            slottedTimes.set(time, 'past');
                        } else if (isSlotBooked(doctor.id, date, time)){
                            slottedTimes.set(time, 'booked');
                        } else {
                            slottedTimes.set(time, 'available');
                        }
                    }
                }
            }

            const slotStatus = slottedTimes;
            availableRanges.forEach(range => {
                let start = toMinutes(range.start_time.substring(0,5));
                let end = toMinutes(range.end_time.substring(0,5));
                if (start === null || end === null || start >= end) return;
                for (let minutes = start; minutes < end; minutes += 15){
                    const time = minutesToString(minutes);
                    if (isSlotPast(date, time)){
                        slotStatus.set(time, 'past');
                        continue;
                    }
                    if (isWithinRanges(time, unavailableRanges)){
                        slotStatus.set(time, 'unavailable');
                        continue;
                    }
                    if (isSlotBooked(doctor.id, date, time)){
                        slotStatus.set(time, 'booked');
                    } else if (!slotStatus.has(time)){
                        slotStatus.set(time, 'available');
                    }
                }
            });

            unavailableRanges.forEach(range => {
                let start = toMinutes(range.start_time.substring(0,5));
                let end = toMinutes(range.end_time.substring(0,5));
                if (start === null || end === null || start >= end) return;
                for (let minutes = start; minutes < end; minutes += 15){
                    const time = minutesToString(minutes);
                    if (isSlotPast(date, time)){
                        slotStatus.set(time, 'past');
                        continue;
                    }
                    slotStatus.set(time, 'unavailable');
                }
            });

            const sortedTimes = Array.from(slotStatus.keys()).sort();
            if (!sortedTimes.length){
                container.innerHTML = '<div class="card empty">No open slots remain for the selected date.</div>';
                return;
            }

            let slotsHTML = '';
            sortedTimes.forEach(time => {
                const status = slotStatus.get(time);
                const label = status === 'available' ? 'Available' :
                              status === 'booked' ? 'Booked' :
                              status === 'unavailable' ? 'Unavailable' : 'Past time';
                slotsHTML += `<div class="time-slot ${status}" title="${label}">${time}</div>`;
            });

            const specialty = doctor.specialty || 'General Medicine';
            const doctorName = `Dr. ${(doctor.first_name||'')} ${(doctor.last_name||'')}`.trim() || doctor.email;
            const availableCount = sortedTimes.filter(time => slotStatus.get(time) === 'available').length;

            const el = document.createElement('div');
            el.className = 'card doctor-availability';
            el.innerHTML = `
                <div class="doctor-header">
                    <div>
                        <div class="doctor-name">${esc(doctorName)}</div>
                        <div class="doctor-specialty">${esc(specialty)}</div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:var(--font-size-sm); color:var(--gray-600);">Open slots</div>
                        <div style="font-weight:700; color:#166534;">${availableCount}</div>
                    </div>
                </div>
                <div class="time-slots">${slotsHTML}</div>
            `;
            container.appendChild(el);
        }

        async function searchAvailability(){
            const doctorId = document.getElementById('doctor-filter').value;
            const date = document.getElementById('date-filter').value;
            
            if (!doctorId || doctorId === ''){
                alert('Please select a doctor first.');
                return;
            }
            
            if (!date || date === ''){
                alert('Please select a date first.');
                return;
            }
            
            await Promise.all([
                loadAvailabilityEntries(doctorId, date),
                loadAppointments(doctorId, date)
            ]);
            displayAvailability(doctorId, date);
        }

        function resetFilters(){
            document.getElementById('doctor-filter').value = '';
            document.getElementById('doctor-filter').selectedIndex = 0;
            document.getElementById('date-filter').value = '';
            availabilityEntries = [];
            document.getElementById('availability-results').innerHTML = '<div class="card empty">Please select a doctor and date to view availability.</div>';
        }

        document.getElementById('btn-search').addEventListener('click', searchAvailability);
        document.getElementById('btn-reset').addEventListener('click', resetFilters);

        (async function init(){
            // Set date constraints
            const dateInput = document.getElementById('date-filter');
            const today = new Date();
            const maxDate = new Date(today.getTime() + 180*24*60*60*1000);
            dateInput.min = today.toISOString().slice(0,10);
            dateInput.max = maxDate.toISOString().slice(0,10);
            
            // Set initial message
            document.getElementById('availability-results').innerHTML = '<div class="card empty">Please select a doctor and date, then click Search to view availability.</div>';
            
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
