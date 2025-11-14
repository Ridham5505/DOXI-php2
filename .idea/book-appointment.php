<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Book Appointment</title>
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
        .grid { display:grid; gap: var(--spacing-6); }
        .cols-2 { grid-template-columns: 1fr 1fr; }
        @media (max-width: 900px){ .cols-2{ grid-template-columns: 1fr; } }
        .form-row { display:grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-4); }
        .form-row-3 { display:grid; grid-template-columns: 1.2fr .8fr .8fr; gap: var(--spacing-4); }
        @media (max-width: 700px){ .form-row, .form-row-3 { grid-template-columns: 1fr; } }
        label { font-weight:600; color: var(--gray-700); margin-bottom: var(--spacing-2); display:block; }
        select, input, textarea { width:100%; padding: var(--spacing-3) var(--spacing-4); border: 2px solid var(--gray-200); border-radius: var(--radius-md); font-family: var(--font-family); }
        textarea { min-height: 90px; resize: vertical; }
        .help { font-size: var(--font-size-sm); color: var(--gray-500); margin: -6px 0 var(--spacing-2); }
        .label-row { display:flex; align-items:baseline; justify-content: space-between; gap: var(--spacing-3); }
        .help-inline { font-size: var(--font-size-sm); color: var(--gray-500); font-weight: 500; }
        .error { color: #dc2626; font-size: var(--font-size-sm); margin-top: 4px; }
        .invalid { border-color: #dc2626 !important; }
        .btn-row { display:flex; gap: var(--spacing-3); justify-content: flex-end; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 12px 14px; border-bottom: 1px solid var(--gray-200); text-align: left; }
        .table th { color: var(--gray-600); font-weight: 600; }
        .pill { padding: 4px 10px; border-radius: 9999px; font-size: var(--font-size-xs); font-weight: 700; }
        .pill.scheduled { background:#e0e7ff; color:#3730a3; }
        .pill.completed { background:#dcfce7; color:#166534; }
        .pill.cancelled { background:#fee2e2; color:#991b1b; }
        .actions { display:flex; gap:8px; }
        .link { color: var(--primary-blue); font-weight:600; text-decoration:none; }
        .link:hover { text-decoration: underline; }
        .back { text-decoration:none; color: var(--gray-600); }
        /* Modal styles */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: var(--white); margin: 5% auto; padding: var(--spacing-6); border-radius: var(--radius-xl); box-shadow: var(--shadow-xl); width: 90%; max-width: 600px; position: relative; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4); border-bottom: 1px solid var(--gray-200); padding-bottom: var(--spacing-4); }
        .modal-title { font-size: var(--font-size-xl); font-weight: 700; color: var(--gray-900); }
        .close-modal { color: var(--gray-500); font-size: 28px; font-weight: bold; cursor: pointer; background: none; border: none; padding: 0; line-height: 1; }
        .close-modal:hover { color: var(--gray-900); }
        .modal-body { color: var(--gray-700); }
        .detail-row { display: flex; padding: var(--spacing-3) 0; border-bottom: 1px solid var(--gray-100); }
        .detail-label { font-weight: 600; color: var(--gray-700); min-width: 120px; }
        .detail-value { color: var(--gray-900); flex: 1; }
    </style>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📅</text></svg>">
</head>
<body>
    <div class="page">
        <div class="header">
            <div>
                <div class="logo">DOXI</div>
                <div class="tagline">Book Appointment</div>
            </div>
            <div style="display:flex; align-items:center; gap: var(--spacing-4);">
                <button class="theme-toggle" id="theme-toggle" onclick="toggleTheme()" style="padding: 8px 14px; border-radius: 8px; border: 1px solid var(--gray-200); background: var(--white); color: var(--gray-700); cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s;">
                    <span id="theme-icon">🌙</span>
                    <span id="theme-text">Dark</span>
                </button>
                <a class="back" href="patient-dashboard.php">← Back to Dashboard</a>
            </div>
        </div>

        <div class="grid">
            <div class="card">
                <h3 style="margin-bottom: var(--spacing-4); color: var(--gray-900);">Create / Update Appointment</h3>
                <form id="appt-form">
                    <input type="hidden" id="appt-id">
                    <div class="form-row-3">
                        <div>
                            <label for="doctor">Doctor</label>
                            <select id="doctor" required></select>
                            <div id="err-doctor" class="error"></div>
                        </div>
                        <div>
                            <div class="label-row">
                                <label for="date">Date</label>
                                <span class="help-inline">Format: YYYY‑MM‑DD</span>
                            </div>
                            <input id="date" type="date" required />
                            <div id="err-date" class="error"></div>
                        </div>
                        <div>
                            <div class="label-row">
                                <label for="time">Time</label>
                                <span class="help-inline">Available: 10:00 AM – 7:00 PM</span>
                            </div>
                            <input id="time" type="time" required min="10:00" max="19:00" step="900" list="time-slots" />
                            <datalist id="time-slots"></datalist>
                            <p id="availability-hint" style="margin-top:4px; font-size: var(--font-size-xs); color: var(--gray-500);">Select a doctor and date to view available times.</p>
                            <div id="err-time" class="error"></div>
                        </div>
                    </div>
                    <div style="margin-top: var(--spacing-4);">
                        <label for="notes">Problem Description <span style="font-weight:400; color: var(--gray-500);">(Required - No numbers allowed)</span></label>
                        <textarea id="notes" placeholder="Describe your medical problem or reason for appointment in words only..." maxlength="500" required></textarea>
                        <div id="err-notes" class="error"></div>
                    </div>
                    <div class="form-row" style="margin-top: var(--spacing-4); align-items:center;">
                        <div>
                            <label for="status">Status</label>
                            <select id="status">
                                <option value="scheduled">scheduled</option>
                                <option value="cancelled">cancelled</option>
                            </select>
                        </div>
                        <div class="btn-row">
                            <button type="button" class="btn btn-outline" onclick="resetForm()">Clear</button>
                            <button type="submit" class="btn btn-primary" id="submit-btn">Save Appointment</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card">
                <h3 id="appointments-title" style="margin-bottom: var(--spacing-4); color: var(--gray-900);">My Appointments</h3>
                <table class="table" id="appt-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Doctor</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for viewing appointment details -->
    <div id="view-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Appointment Details</h3>
                <button class="close-modal" onclick="closeViewModal()">&times;</button>
            </div>
            <div class="modal-body" id="modal-body">
                <!-- Appointment details will be populated here -->
            </div>
        </div>
    </div>

    <script>
        if (sessionStorage.getItem('isLoggedIn') !== 'true' || sessionStorage.getItem('userRole') !== 'patient') {
            window.location.href = 'login.php';
        }

let currentPatientId = null;
let currentAvailableSlots = [];
let currentAvailabilityDoctor = null;
let currentAvailabilityDate = null;
let unreadNotificationsFetched = false;
        function getJSON(url){ return fetch(url).then(r=>r.json()); }
        function api(path, method='GET', body){
            return fetch(path, { method, headers:{ 'Content-Type':'application/json' }, body: body ? JSON.stringify(body) : undefined }).then(r=>r.json());
        }

async function fetchUnreadNotificationsOnce(){
    if (unreadNotificationsFetched || !currentPatientId) return;
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
        unreadNotificationsFetched = true;
    }catch(e){
        console.error('Notification fetch error', e);
    }
}

        function toMinutes(timeString){
            if (!timeString) return null;
            const [hh, mm] = timeString.split(':');
            const hours = parseInt(hh, 10);
            const minutes = parseInt(mm, 10);
            if (Number.isNaN(hours) || Number.isNaN(minutes)) return null;
            return hours * 60 + minutes;
        }

        function minutesToTime(totalMinutes){
            const hours = Math.floor(totalMinutes / 60);
            const minutes = totalMinutes % 60;
            return `${String(hours).padStart(2,'0')}:${String(minutes).padStart(2,'0')}`;
        }

        function formatDisplayTime(timeString){
            if (!timeString) return '';
            const [hourStr, minuteStr] = timeString.split(':');
            const date = new Date();
            date.setHours(parseInt(hourStr, 10), parseInt(minuteStr, 10));
            return date.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        }

        function minutesInRanges(totalMinutes, ranges){
            return ranges.some(range => {
                const start = toMinutes((range.start_time || '').substring(0,5));
                const end = toMinutes((range.end_time || '').substring(0,5));
                return start !== null && end !== null && totalMinutes >= start && totalMinutes < end;
            });
        }

        async function refreshTimeSlots(includeTime){
            const doctorSelect = document.getElementById('doctor');
            const dateInputEl = document.getElementById('date');
            const list = document.getElementById('time-slots');
            const hint = document.getElementById('availability-hint');
            currentAvailableSlots = [];
            list.innerHTML = '';
            const doctorId = parseInt(doctorSelect.value || '0', 10);
            const date = dateInputEl.value;
            currentAvailabilityDoctor = doctorId || null;
            currentAvailabilityDate = date || null;

            if (!doctorId || !date){
                if (hint) hint.textContent = 'Select a doctor and date to view available times.';
                return;
            }

            if (hint) hint.textContent = 'Loading availability...';

            try{
                const [availabilityRes, appointmentsRes] = await Promise.all([
                    getJSON(`api/availability.php?doctor_id=${doctorId}&date=${date}`),
                    getJSON(`api/appointments.php?doctor_id=${doctorId}&date=${date}`)
                ]);

                const availability = availabilityRes.success ? (availabilityRes.data || []) : [];
                const appointments = appointmentsRes.success ? (appointmentsRes.data || []) : [];

                const availableRanges = availability.filter(entry => entry.status === 'available');
                const unavailableRanges = availability.filter(entry => entry.status !== 'available');
                const bookedTimes = new Set(
                    appointments
                        .filter(a => (a.status || '').toLowerCase() !== 'cancelled')
                        .map(a => (a.appt_time || '').substring(0,5))
                );

                const clinicStartMinutes = 10 * 60;
                const clinicEndMinutes = 19 * 60;
                const now = new Date();
                const todayStr = now.toISOString().slice(0,10);
                const isToday = date === todayStr;
                const minTodayMinutes = Math.max(clinicStartMinutes, Math.ceil((now.getHours() * 60 + now.getMinutes()) / 15) * 15);
                const slotSet = new Set();
                availableRanges.forEach(range => {
                    let start = toMinutes((range.start_time || '').substring(0,5));
                    let end = toMinutes((range.end_time || '').substring(0,5));
                    if (start === null || end === null || start >= end) return;
                    for (let minutes = start; minutes < end; minutes += 15){
                        const timeValue = minutesToTime(minutes);
                        if (minutesInRanges(minutes, unavailableRanges)) continue;
                        if (bookedTimes.has(timeValue)) continue;
                        slotSet.add(timeValue);
                    }
                });

                let finalSlots = Array.from(slotSet).sort();

                // Remove past times when booking for today
                if (isToday){
                    finalSlots = finalSlots.filter(timeValue => {
                        const minutes = toMinutes(timeValue);
                        return minutes !== null && minutes >= minTodayMinutes && minutes <= clinicEndMinutes;
                    });
                }

                const isFuture = date > todayStr;
                let usedFallback = false;

                if (!finalSlots.length){
                    usedFallback = true;
                    for (let minutes = clinicStartMinutes; minutes <= clinicEndMinutes; minutes += 15){
                        if (isToday && minutes < minTodayMinutes) continue;
                        const timeValue = minutesToTime(minutes);
                        if (bookedTimes.has(timeValue)) continue;
                        if (minutesInRanges(minutes, unavailableRanges)) continue;
                        slotSet.add(timeValue);
                    }
                    finalSlots = Array.from(slotSet).sort();
                    if (isToday){
                        finalSlots = finalSlots.filter(timeValue => {
                            const minutes = toMinutes(timeValue);
                            return minutes !== null && minutes >= minTodayMinutes && minutes <= clinicEndMinutes;
                        });
                    }
                }

                if (includeTime && includeTime.match(/^\d{2}:\d{2}$/)){
                    if (!finalSlots.includes(includeTime)){
                        finalSlots.push(includeTime);
                    }
                    finalSlots.sort();
                }

                finalSlots.forEach(timeValue => {
                    const opt = document.createElement('option');
                    opt.value = timeValue;
                    opt.label = formatDisplayTime(timeValue);
                    list.appendChild(opt);
                });
                currentAvailableSlots = finalSlots;
                const timeInput = document.getElementById('time');
                if (timeInput){
                    if (includeTime && finalSlots.includes(includeTime)){
                        timeInput.value = includeTime;
                    } else if (finalSlots.length){
                        timeInput.value = finalSlots[0];
                    }
                }
                if (hint){
                    if (finalSlots.length){
                        const previews = finalSlots.slice(0, 6).map(formatDisplayTime).join(', ');
                        const more = finalSlots.length > 6 ? '…' : '';
                        const baseMessage = `Select one of ${finalSlots.length} available time slot${finalSlots.length === 1 ? '' : 's'}: ${previews}${more}`;
                        hint.textContent = usedFallback ? `${baseMessage} (default office hours)` : baseMessage;
                    }else{
                        hint.textContent = 'No open time slots remain for this date.';
                    }
                }
            }catch(e){
                if (hint) hint.textContent = 'Unable to load availability. Please try again.';
                currentAvailableSlots = [];
            }
        }

        function setDefaultTime(){
            const input = document.getElementById('time');
            if (!input) return;
            if (currentAvailableSlots.length){
                input.value = currentAvailableSlots[0];
                return;
            }
            const now = new Date();
            const minutesFromMidnight = now.getHours()*60 + now.getMinutes();
            const start = 10*60, end = 19*60; // inclusive end allows 19:00
            let slotMinutes = Math.ceil(minutesFromMidnight/15)*15; // round up to next 15 min
            if (slotMinutes < start) slotMinutes = start;
            if (slotMinutes > end) slotMinutes = end; // clamp to 19:00 if late
            input.value = minutesToTime(slotMinutes);
        }

        function ymd(d){ const yy=d.getFullYear(); const mm=String(d.getMonth()+1).padStart(2,'0'); const dd=String(d.getDate()).padStart(2,'0'); return `${yy}-${mm}-${dd}`; }

        function setDateConstraints(){
            const input = document.getElementById('date');
            if (!input) return;
            const today = new Date(); today.setHours(0,0,0,0);
            const max = new Date(today.getTime() + 180*24*60*60*1000); // next 6 months
            input.min = ymd(today);
            input.max = ymd(max);
            if (!input.value) input.value = ymd(today);
            validateDateField();
        }

        function validateDateField(){
            const input = document.getElementById('date');
            if (!input) return true;
            const errId = 'err-date';
            const val = input.value;
            const min = input.min; const max = input.max;
            let msg = '';
            if (!val){ msg = 'Please choose a date.'; }
            else if (min && val < min){ msg = 'Please select a valid date (today or later).'; }
            else if (max && val > max){ msg = 'Please select a date within the next 6 months.'; }
            document.getElementById(errId).textContent = msg;
            input.classList.toggle('invalid', !!msg);
            input.setCustomValidity(msg);
            return !msg;
        }

        function validateNotesField(){
            const input = document.getElementById('notes');
            if (!input) return true;
            const errId = 'err-notes';
            const val = input.value.trim();
            let msg = '';
            if (!val || val.length === 0){ 
                msg = 'Please describe your medical problem.'; 
            }
            else if (/\d/.test(val)){ 
                msg = 'Numbers are not allowed. Please describe your problem in words only.'; 
            }
            else if (val.length < 10){ 
                msg = 'Please provide a detailed description (at least 10 characters).'; 
            }
            else if (val.length > 500){ 
                msg = 'Notes must be 500 characters or fewer.'; 
            }
            setError(errId, msg, 'notes');
            return !msg;
        }

        async function loadCurrentPatient(){
            const email = sessionStorage.getItem('userEmail');
            const res = await getJSON(`api/users.php?search=${encodeURIComponent(email)}&page=1&limit=1`);
            if (res.success && res.data && res.data.length){ currentPatientId = res.data[0].id; }
        }

        async function loadDoctors(){
            const sel = document.getElementById('doctor');
            sel.innerHTML = '<option value="">Loading doctors...</option>';
            const res = await getJSON('api/users.php?role=doctor&page=1&limit=100');
            sel.innerHTML='<option value="" selected disabled>Select Doctor...</option>';
            if (res.success){
                res.data.forEach(d=>{
                    const o = document.createElement('option');
                    o.value = d.id; o.textContent = `${d.first_name||''} ${d.last_name||''}`.trim() || d.email; sel.appendChild(o);
                });
            }
        }

        async function loadAppointments(){
            if (!currentPatientId) return;
            const res = await getJSON(`api/appointments.php?patient_id=${currentPatientId}`);
            const tbody = document.querySelector('#appt-table tbody');
            tbody.innerHTML='';
            if (res.success){
                const now = new Date();
                const params = new URLSearchParams(window.location.search);
                const filter = params.get('filter') || 'all';
                
                // Update title based on filter
                const titleEl = document.getElementById('appointments-title');
                if (titleEl){
                    if (filter === 'upcoming') titleEl.textContent = 'Upcoming Appointments';
                    else titleEl.textContent = 'My Appointments';
                }
                
                let filteredData = res.data || [];
                if (filter === 'upcoming'){
                    filteredData = filteredData.filter(a=>{
                        const apptDt = new Date(`${a.appt_date}T${(a.appt_time||'00:00').substring(0,5)}:00`);
                        return !isNaN(apptDt.getTime()) && apptDt >= now && (a.status !== 'cancelled' && a.status !== 'completed');
                    });
                }
                
                filteredData.forEach(async a=>{
                    const apptDt = new Date(`${a.appt_date}T${(a.appt_time||'00:00').substring(0,5)}:00`);
                    const isPast = !isNaN(apptDt.getTime()) && apptDt < now;
                    const computedStatus = (isPast && a.status !== 'cancelled') ? 'completed' : a.status;
                    if (computedStatus === 'completed' && a.status !== 'completed'){
                        try { await api('api/appointments.php','PUT',{ id:a.id, status:'completed' }); a.status='completed'; } catch(e){}
                    }
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${a.appt_date}</td>
                        <td>${a.appt_time.substring(0,5)}</td>
                        <td>${a.doctor_name || ('#'+a.doctor_id)}</td>
                        <td><span class="pill ${computedStatus}">${computedStatus}</span></td>
                        <td class="actions">
                            <a href="#" class="link" onclick="viewAppt(${a.id});return false;">View</a>
                            <a href="#" class="link" onclick="editAppt(${a.id});return false;">Edit</a>
                            <a href="#" class="link" onclick="delAppt(${a.id});return false;">Delete</a>
                        </td>`;
                    tbody.appendChild(tr);
                });
            }
        }

        function resetForm(){
            document.getElementById('appt-id').value = '';
            const doctorSel = document.getElementById('doctor');
            doctorSel.value = '';
            doctorSel.selectedIndex = 0;
            document.getElementById('date').value = '';
            document.getElementById('time').value = '';
            document.getElementById('notes').value = '';
            document.getElementById('status').value = 'scheduled';
            document.getElementById('submit-btn').textContent = 'Save Appointment';
            currentAvailableSlots = [];
            const list = document.getElementById('time-slots');
            if (list) list.innerHTML = '';
            const hint = document.getElementById('availability-hint');
            if (hint) hint.textContent = 'Select a doctor and date to view available times.';
        }

        async function editAppt(id){
            const res = await getJSON(`api/appointments.php?id=${id}`);
            if (res.success){
                const a = res.data;
                document.getElementById('appt-id').value = a.id;
                document.getElementById('doctor').value = a.doctor_id;
                document.getElementById('date').value = a.appt_date;
                await refreshTimeSlots((a.appt_time || '').substring(0,5));
                document.getElementById('notes').value = a.notes || '';
                document.getElementById('status').value = a.status || 'scheduled';
                document.getElementById('submit-btn').textContent = 'Update Appointment';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        async function delAppt(id){
            if (!confirm('Delete this appointment?')) return;
            const res = await api('api/appointments.php','DELETE',{ id });
            if (res.success){
                await loadAppointments();
                await refreshTimeSlots(document.getElementById('time').value);
            }
            else { alert(res.message || 'Delete failed'); }
        }

        async function viewAppt(id){
            const res = await getJSON(`api/appointments.php?id=${id}`);
            if (!res.success || !res.data){
                alert('Failed to load appointment details.');
                return;
            }
            const a = res.data;
            const modalBody = document.getElementById('modal-body');
            const apptDt = new Date(`${a.appt_date}T${(a.appt_time||'00:00').substring(0,5)}:00`);
            const isPast = !isNaN(apptDt.getTime()) && apptDt < new Date();
            const computedStatus = (isPast && a.status !== 'cancelled') ? 'completed' : a.status;
            
            modalBody.innerHTML = `
                <div class="detail-row">
                    <div class="detail-label">Patient Name:</div>
                    <div class="detail-value">${a.patient_name || 'N/A'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Doctor Name:</div>
                    <div class="detail-value">${a.doctor_name || 'N/A'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Date:</div>
                    <div class="detail-value">${a.appt_date || 'N/A'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Time:</div>
                    <div class="detail-value">${a.appt_time ? a.appt_time.substring(0,5) : 'N/A'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status:</div>
                    <div class="detail-value"><span class="pill ${computedStatus}">${computedStatus}</span></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Notes:</div>
                    <div class="detail-value">${a.notes || 'No notes provided'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Created:</div>
                    <div class="detail-value">${a.created_at ? new Date(a.created_at).toLocaleString() : 'N/A'}</div>
                </div>
            `;
            document.getElementById('view-modal').style.display = 'block';
        }

        function closeViewModal(){
            document.getElementById('view-modal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            const modal = document.getElementById('view-modal');
            if (e.target === modal){
                closeViewModal();
            }
        });

        function isTimeInWindow(t){
            // t format HH:MM
            if (!t || t.length < 5) return false;
            const [h,m] = t.split(':').map(Number);
            const minutes = h*60 + m;
            const start = 10*60; // 10:00
            const end = 19*60;   // 19:00
            return minutes >= start && minutes <= end;
        }

        function isQuarterHour(t){ if (!t) return false; const m = parseInt(t.split(':')[1]||'0',10); return m % 15 === 0; }

        function setError(id, message, inputId){
            const el = document.getElementById(id);
            if (el) el.textContent = message || '';
            if (inputId){ document.getElementById(inputId).classList.toggle('invalid', !!message); }
        }

        function clearErrors(){
            setError('err-doctor','', 'doctor');
            setError('err-date','', 'date');
            setError('err-time','', 'time');
            setError('err-notes','', 'notes');
        }

        function validateForm(){
            clearErrors();
            let valid = true;
            const doctor = document.getElementById('doctor').value;
            const dateStr = document.getElementById('date').value;
            const timeStr = document.getElementById('time').value;
            const notes = document.getElementById('notes').value.trim();

            if (!doctor){ valid = false; setError('err-doctor','Please select a doctor.','doctor'); }

            if (!dateStr){ valid = false; setError('err-date','Please choose a date.','date'); }
            else {
                const today = new Date(); today.setHours(0,0,0,0);
                const d = new Date(dateStr + 'T00:00:00');
                if (isNaN(d.getTime())){ valid = false; setError('err-date','Invalid date.','date'); }
                else if (d < today){ valid = false; setError('err-date','Date cannot be in the past.','date'); }
            }

            if (!timeStr){ valid = false; setError('err-time','Please select a time.','time'); }
            else if (!isTimeInWindow(timeStr)){ valid = false; setError('err-time','Time must be between 10:00 and 19:00.','time'); }
            else if (!isQuarterHour(timeStr)){ valid = false; setError('err-time','Time must be in 15-minute steps (00, 15, 30, 45).','time'); }

            // Validate notes: required, no numbers, proper description
            if (!notes || notes.trim().length === 0){ 
                valid = false; 
                setError('err-notes','Please describe your medical problem.','notes'); 
            }
            else if (/\d/.test(notes)){ 
                valid = false; 
                setError('err-notes','Numbers are not allowed. Please describe your problem in words only.','notes'); 
            }
            else if (notes.trim().length < 10){ 
                valid = false; 
                setError('err-notes','Please provide a detailed description (at least 10 characters).','notes'); 
            }
            else if (notes.length > 500){ 
                valid = false; 
                setError('err-notes','Notes must be 500 characters or fewer.','notes'); 
            }

            // If date is today, ensure time is not in the past (allow next slot)
            if (valid && dateStr){
                const now = new Date();
                const todayStr = now.toISOString().slice(0,10);
                if (dateStr === todayStr){
                    const nowMinutes = now.getHours()*60 + now.getMinutes();
                    const [hh,mm] = timeStr.split(':').map(Number);
                    const tMinutes = hh*60 + mm;
                    const minAllowed = Math.ceil(nowMinutes/15)*15; // next quarter hour
                    if (tMinutes < minAllowed){
                        valid = false;
                        setError('err-time', 'Time must be later than the current time.', 'time');
                    }
                }
            }
            return valid;
        }

        async function checkDoubleBooking(doctorId, date, time, excludeApptId){
            if (!doctorId || !date || !time) return false;
            try{
                const res = await getJSON(`api/appointments.php?doctor_id=${doctorId}`);
                if (res.success && res.data){
                    const conflict = res.data.find(a=>{
                        if (excludeApptId && a.id === excludeApptId) return false; // Exclude current appointment if editing
                        return a.appt_date === date && a.appt_time.substring(0,5) === time.substring(0,5) && 
                               (a.status !== 'cancelled' && a.status !== 'completed');
                    });
                    return !!conflict;
                }
            }catch(e){ console.error('Check double booking error:', e); }
            return false;
        }

        document.getElementById('appt-form').addEventListener('submit', async (e)=>{
            e.preventDefault();
            if (!validateForm()) return;
            if (!currentPatientId){ alert('User not loaded yet'); return; }
            const payload = {
                patient_id: currentPatientId,
                doctor_id: parseInt(document.getElementById('doctor').value || '0',10),
                appt_date: document.getElementById('date').value,
                appt_time: document.getElementById('time').value,
                status: document.getElementById('status').value,
                notes: document.getElementById('notes').value.trim()
            };
            if (!isTimeInWindow(payload.appt_time)){
                alert('Please select a time between 10:00 AM and 7:00 PM.');
                return;
            }
            
            // Check for double booking
            const apptId = document.getElementById('appt-id').value;
            const isDoubleBooked = await checkDoubleBooking(payload.doctor_id, payload.appt_date, payload.appt_time, apptId ? parseInt(apptId,10) : null);
            if (isDoubleBooked){
                setError('err-time', 'This doctor is already booked at this time. Please select another time.', 'time');
                return;
            }

            if (!apptId && currentAvailableSlots.length && !currentAvailableSlots.includes(payload.appt_time)){
                setError('err-time', 'Please choose a time from the available slots.', 'time');
                return;
            }
            
            const submitBtn = document.getElementById('submit-btn');
            const prev = submitBtn.textContent; submitBtn.disabled = true; submitBtn.textContent = 'Saving...';
            let res;
            if (apptId){ payload.id = parseInt(apptId,10); res = await api('api/appointments.php','PUT', payload); }
            else { res = await api('api/appointments.php','POST', payload); }
            if (res.success){
                resetForm();
                await loadAppointments();
                await refreshTimeSlots();
            }
            else { alert(res.message || 'Save failed'); }
            submitBtn.disabled = false; submitBtn.textContent = prev;
        });

        (async function init(){
            await loadCurrentPatient();
            await Promise.all([ loadDoctors() ]);
            // Preselect doctor if passed via hash (#doctor=ID) or query (?doctor=ID)
            try{
                const hash = window.location.hash || '';
                const hashMatch = hash.match(/doctor=(\d+)/);
                const urlParams = new URLSearchParams(window.location.search);
                const queryDoctor = urlParams.get('doctor');
                const doctorId = hashMatch ? parseInt(hashMatch[1],10) : (queryDoctor ? parseInt(queryDoctor,10) : null);
                if (doctorId){
                    const sel = document.getElementById('doctor');
                    sel.value = doctorId;
                    // If value not found in dropdown (still loading), wait a bit
                    if (!sel.value && sel.options.length <= 1){
                        setTimeout(()=>{ sel.value = doctorId; }, 100);
                    }
                }
            }catch(_e){}
            await loadAppointments();
            await refreshTimeSlots();
            setDefaultTime();
            setDateConstraints();
            await fetchUnreadNotificationsOnce();
            document.getElementById('date').addEventListener('input', validateDateField);
            document.getElementById('date').addEventListener('change', validateDateField);
            document.getElementById('notes').addEventListener('input', validateNotesField);
            document.getElementById('notes').addEventListener('change', validateNotesField);
            const doctorSelect = document.getElementById('doctor');
            doctorSelect.addEventListener('change', ()=> refreshTimeSlots(document.getElementById('time').value));
            document.getElementById('date').addEventListener('change', ()=> refreshTimeSlots(document.getElementById('time').value));
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



