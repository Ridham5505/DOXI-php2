<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Doctor Calendar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📆</text></svg>">
    <script>
        (function(){
            try{
                const savedTheme = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-theme', savedTheme);
            }catch(_e){}
        })();
    </script>
    <style>
        body{margin:0;background:var(--gray-50);color:var(--gray-900);font-family:var(--font-family,'Inter',sans-serif);} 
        .layout{max-width:1100px;margin:40px auto;padding:0 var(--spacing-6);display:grid;gap:var(--spacing-6);} 
        .header{display:flex;align-items:center;justify-content:space-between;} 
        .header-left{display:flex;align-items:center;gap:var(--spacing-4);} 
        .logo{font-size:var(--font-size-2xl);font-weight:800;color:var(--primary-blue);} 
        .logo img{display:block;height:36px;width:auto;} 
        .tagline{font-size:var(--font-size-sm);color:var(--gray-500);} 
        .theme-toggle{padding:8px 14px;border-radius:8px;border:1px solid var(--gray-200);background:var(--white);color:var(--gray-700);cursor:pointer;font-weight:600;display:flex;align-items:center;gap:8px;transition:all .3s;} 
        .theme-toggle:hover{background:var(--gray-100);} 
        .calendar-card{background:var(--white);border:1px solid var(--gray-200);border-radius:24px;padding:var(--spacing-6);box-shadow:var(--shadow-md);display:grid;gap:var(--spacing-4);} 
        [data-theme="dark"] body{background:#0b1120;color:#e2e8f0;} 
        [data-theme="dark"] .calendar-card{background:#131c2c;border-color:#1f2937;box-shadow:0 12px 32px rgba(15,23,42,0.45);} 
        [data-theme="dark"] .detail-panel{background:#131c2c;border-color:#1f2937;box-shadow:0 12px 32px rgba(15,23,42,0.4);} 
        [data-theme="dark"] .day-card{background:#10192b;border-color:#1f2937;color:#f8fafc;} 
        [data-theme="dark"] .day-card.disabled{background:#0d1626;color:#475569;} 
        [data-theme="dark"] .weekday-row{color:#cbd5f5;} 
        [data-theme="dark"] .chip-none{background:#1f2937;color:#cbd5f5;} 
        [data-theme="dark"] .chip-available{background:#064e3b;color:#d1fae5;} 
        [data-theme="dark"] .chip-booked{background:#7f1d1d;color:#fee2e2;} 
        [data-theme="dark"] .chip-unavailable{background:#312e81;color:#ede9fe;} 
        [data-theme="dark"] .nav-btn{background:#152136;border-color:#1f2937;color:#cbd5f5;} 
        [data-theme="dark"] .nav-btn:hover{background:#1e293b;} 
        [data-theme="dark"] .theme-toggle{background:#152136;border-color:#1f2937;color:#cbd5f5;} 
        [data-theme="dark"] .theme-toggle:hover{background:#1e293b;} 
        [data-theme="dark"] .list-item{border-color:#1f2937;color:#e2e8f0;} 
        [data-theme="dark"] .mini-btn{background:#152136;border-color:#1f2937;color:#cbd5f5;} 
        [data-theme="dark"] .mini-btn.danger{border-color:#ef4444;color:#fca5a5;} 
        [data-theme="dark"] .modal{background:#10192b;border:1px solid #1f2937;color:#e2e8f0;} 
        [data-theme="dark"] .modal-form input,[data-theme="dark"] .modal-form select,[data-theme="dark"] .modal-form textarea{background:#0f172a;border-color:#1f2937;color:#cbd5f5;} 
        [data-theme="dark"] .modal-form input::placeholder,[data-theme="dark"] .modal-form textarea::placeholder{color:#64748b;} 
        [data-theme="dark"] .calendar-title,[data-theme="dark"] .calendar-sub,[data-theme="dark"] .detail-date,[data-theme="dark"] .detail-summary{color:#f8fafc;} 
        .header{display:flex;align-items:center;justify-content:space-between;} 
        .header-left{display:flex;align-items:center;gap:var(--spacing-4);} 
        .logo{font-size:var(--font-size-2xl);font-weight:800;color:var(--primary-blue);} 
        .logo img{display:block;height:36px;width:auto;} 
        .tagline{font-size:var(--font-size-sm);color:var(--gray-500);} 
        .theme-toggle{padding:8px 14px;border-radius:8px;border:1px solid var(--gray-200);background:var(--white);color:var(--gray-700);cursor:pointer;font-weight:600;display:flex;align-items:center;gap:8px;transition:all .3s;} 
        .theme-toggle:hover{background:var(--gray-100);} 
        .calendar-card{background:var(--white);border:1px solid var(--gray-200);border-radius:24px;padding:var(--spacing-6);box-shadow:var(--shadow-md);display:grid;gap:var(--spacing-4);} 
        .calendar-top{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:var(--spacing-4);} 
        .calendar-title{font-size:var(--font-size-2xl);font-weight:700;margin:0;} 
        .calendar-sub{color:var(--gray-500);margin-top:4px;} 
        .calendar-controls{display:flex;align-items:center;gap:var(--spacing-6);flex-wrap:wrap;} 
        .month-display{font-weight:700;font-size:var(--font-size-lg);} 
        .nav-buttons{display:flex;gap:10px;} 
        .nav-btn{width:34px;height:34px;border:1px solid var(--gray-300);border-radius:12px;background:var(--white);cursor:pointer;display:grid;place-items:center;font-size:18px;font-weight:700;color:var(--gray-600);} 
        .nav-btn:hover{background:var(--gray-100);} 
        .full-day-btn{padding:10px 16px;border-radius:999px;border:none;background:#2563eb;color:#fff;font-weight:600;cursor:pointer;transition:background .2s,opacity .2s;box-shadow:0 6px 18px rgba(37,99,235,0.25);}
        .full-day-btn:hover{background:#1d4ed8;}
        .full-day-btn:disabled{opacity:.4;cursor:not-allowed;box-shadow:none;}
        .weekday-row{display:grid;grid-template-columns:repeat(7,1fr);gap:8px;text-align:center;font-size:var(--font-size-sm);color:var(--gray-500);font-weight:600;} 
        .calendar-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:8px;} 
        .day-card{border:1px solid var(--gray-200);border-radius:18px;padding:12px 14px;background:var(--white);min-height:110px;display:flex;flex-direction:column;gap:10px;transition:box-shadow .2s;cursor:pointer;} 
        .day-card.sun-highlight{background:#fee2e2;border-color:#fca5a5;}
        .day-card:hover{box-shadow:var(--shadow-sm);} 
        .day-card.disabled{background:var(--gray-200);color:var(--gray-500);cursor:not-allowed;box-shadow:none;} 
        .day-card.today{border:2px solid var(--primary-blue);} 
        .day-number{font-weight:700;font-size:var(--font-size-lg);} 
        .chip-row{display:flex;flex-wrap:wrap;gap:6px;} 
        .chip{padding:4px 10px;border-radius:999px;font-size:var(--font-size-xs);font-weight:600;} 
        .chip-sunday{background:#fee2e2;color:#b91c1c;}
        .chip-available{background:#dcfce7;color:#166534;} 
        .chip-booked{background:#fee2e2;color:#991b1b;} 
        .chip-unavailable{background:#ede9fe;color:#5b21b6;} 
        .chip-none{background:var(--gray-200);color:var(--gray-600);} 
        .legend{display:flex;gap:12px;flex-wrap:wrap;} 
        .legend .chip{padding:6px 12px;} 
        .detail-panel{display:none !important;}
        .detail-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:var(--spacing-4);} 
        .detail-date{font-size:var(--font-size-xl);font-weight:700;margin:0;} 
        .detail-summary{color:var(--gray-500);} 
        .detail-actions{display:flex;gap:var(--spacing-3);} 
        .btn{padding:10px 18px;border-radius:14px;border:1px solid var(--gray-300);background:var(--white);color:var(--gray-700);font-weight:600;cursor:pointer;transition:all .2s;} 
        .btn-primary{background:var(--primary-blue);border-color:var(--primary-blue);color:var(--white);} 
        .btn:hover{background:var(--gray-100);} 
        .btn-primary:hover{background:var(--primary-blue-dark);} 
        .detail-columns{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:var(--spacing-4);} 
        .detail-card{border:1px solid var(--gray-200);border-radius:18px;padding:var(--spacing-4);background:var(--gray-50);display:grid;gap:var(--spacing-3);} 
        .detail-card h4{margin:0;font-size:var(--font-size-base);font-weight:700;color:var(--gray-700);} 
        .list-item{display:flex;justify-content:space-between;align-items:center;font-size:var(--font-size-sm);padding:6px 0;border-bottom:1px solid var(--gray-200);} 
        .list-item:last-child{border-bottom:none;} 
        .list-actions{display:flex;gap:8px;} 
        .mini-btn{padding:4px 10px;border-radius:999px;border:1px solid var(--gray-300);background:var(--white);font-size:var(--font-size-xs);font-weight:600;cursor:pointer;} 
        .mini-btn.danger{border-color:#ef4444;color:#ef4444;} 
        .mini-btn:hover{background:var(--gray-100);} 
        .empty-state{padding:var(--spacing-5);text-align:center;color:var(--gray-500);} 
        .modal-backdrop{position:fixed;inset:0;background:rgba(15,23,42,0.35);display:none;align-items:center;justify-content:center;z-index:2000;} 
        .modal{width:100%;max-width:460px;background:var(--white);border-radius:20px;padding:var(--spacing-5);box-shadow:var(--shadow-lg);display:grid;gap:var(--spacing-4);} 
        .modal h3{margin:0;} 
        .modal-form{display:grid;gap:var(--spacing-3);} 
        .modal-form label{font-weight:600;color:var(--gray-700);} 
        .modal-form input,.modal-form select,.modal-form textarea{width:100%;padding:10px 12px;border:1px solid var(--gray-300);border-radius:12px;font-size:var(--font-size-sm);} 
        .modal-actions{display:flex;justify-content:flex-end;gap:var(--spacing-3);} 
        @media(max-width:720px){
            .calendar-grid{grid-template-columns:repeat(1,1fr);}
            .weekday-row{grid-template-columns:repeat(1,1fr);}
            .calendar-controls{flex-direction:column;align-items:flex-start;gap:10px;}
        }
    </style>
</head>
<body>
    <div class="layout">
        <div class="header">
            <div class="header-left">
                <div>
                    <div class="logo"><img src="public/assets/doxi-logo.svg?v=2" alt="DOXI" width="120" height="36"></div>
                    <div class="tagline">Doctor Calendar</div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:var(--spacing-3);">
                <button class="theme-toggle" id="theme-toggle" onclick="toggleTheme()">
                    <span id="theme-icon">🌙</span>
                    <span id="theme-text">Dark</span>
                </button>
                <a href="doctor-dashboard.php" style="text-decoration:none;color:var(--gray-600);font-weight:600;">← Back to Dashboard</a>
            </div>
        </div>

        <div class="calendar-card" aria-labelledby="calendar-title">
            <div class="calendar-top">
                <div>
                    <h2 class="calendar-title" id="calendar-title">Schedule Overview</h2>
                    <p class="calendar-sub">Review your appointments and availability by day.</p>
                </div>
                <div class="calendar-controls">
                    <div class="month-display" id="current-month"></div>
                    <div class="nav-buttons">
                        <button class="nav-btn" id="prev-month" aria-label="Previous month">←</button>
                        <button class="nav-btn" id="next-month" aria-label="Next month">→</button>
                    </div>
                    <button class="full-day-btn" id="mark-unavailable-day" disabled>Mark Day Unavailable</button>
                </div>
            </div>

            <div class="legend">
                <div class="chip chip-available">Available</div>
                <div class="chip chip-booked">Booked</div>
                <div class="chip chip-unavailable">Unavailable</div>
                <div class="chip chip-none">No schedule</div>
            </div>

            <div class="weekday-row" id="weekday-row"></div>
            <div class="calendar-grid" id="calendar-grid"></div>
        </div>

        <div class="detail-panel" aria-live="polite">
            <div class="detail-header">
                <h3 class="detail-date" id="detail-date">Select a date</h3>
                <div class="detail-summary" id="detail-summary"></div>
                <div class="detail-actions">
                    <button class="btn btn-primary" id="btn-add-availability" disabled>Add Availability</button>
                </div>
            </div>
            <div class="detail-columns" id="detail-content">
                <div class="empty-state">Choose a date on the calendar to see details.</div>
            </div>
        </div>
    </div>

    <div class="modal-backdrop" id="availability-modal">
        <div class="modal">
            <h3 id="availability-modal-title">Add Availability</h3>
            <form class="modal-form" id="availability-form">
                <input type="hidden" id="availability-id-input">
                <input type="hidden" id="availability-date-input">
                <label for="availability-start">Start Time</label>
                <input type="time" id="availability-start" required>
                <label for="availability-end">End Time</label>
                <input type="time" id="availability-end" required>
                <label for="availability-status">Status</label>
                <select id="availability-status">
                    <option value="available">Available</option>
                    <option value="unavailable">Unavailable</option>
                </select>
                <label for="availability-notes">Notes</label>
                <textarea id="availability-notes" placeholder="Optional notes"></textarea>
                <div class="modal-actions">
                    <button type="button" class="btn" id="availability-cancel">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="availability-save">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        if (sessionStorage.getItem('isLoggedIn') !== 'true' || sessionStorage.getItem('userRole') !== 'doctor') {
            window.location.href = 'login.php';
        }

        const doctorId = parseInt(sessionStorage.getItem('userId') || '0', 10);
        if (!doctorId) {
            alert('Doctor information missing. Please sign in again.');
            window.location.href = 'login.php';
        }

        const weekdayRow = document.getElementById('weekday-row');
        const calendarGrid = document.getElementById('calendar-grid');
        const currentMonthLabel = document.getElementById('current-month');
        const detailDate = document.getElementById('detail-date');
        const detailSummary = document.getElementById('detail-summary');
        const detailContent = document.getElementById('detail-content');
        const markUnavailableBtn = document.getElementById('mark-unavailable-day');

        const weekdayNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        weekdayRow.innerHTML = weekdayNames.map((name,index)=> {
            const sundayStyle = index === 0 ? 'style="color:#dc2626;font-weight:700;"' : '';
            return `<div ${sundayStyle}>${name}</div>`;
        }).join('');

        let monthCursor = new Date();
        monthCursor.setDate(1);
        let appointments = [];
        let availability = [];
        let selectedDateKey = null;
        updateFullDayButton(null);

        async function loadData(){
            try{
                const [apptRes, availRes] = await Promise.all([
                    fetch(`api/appointments.php?doctor_id=${doctorId}`),
                    fetch(`api/availability.php?doctor_id=${doctorId}`)
                ]);
                const apptJson = await apptRes.json();
                const availJson = await availRes.json();
                appointments = apptJson.success ? (apptJson.data||[]) : [];
                availability = availJson.success ? (availJson.data||[]) : [];
            }catch(e){
                console.error('Load data error', e);
                appointments = [];
                availability = [];
            }
            renderCalendar();
            if (selectedDateKey){
                const data = getDayData(selectedDateKey);
                showDayDetail(parseDateKey(selectedDateKey), data.appts, data.availableSlots, data.unavailableSlots);
            }
        }

        function formatMonthLabel(date){
            const options = { month:'long', year:'numeric' };
            return date.toLocaleDateString(undefined, options);
        }

        function getDateKey(date){
            return `${date.getFullYear()}-${String(date.getMonth()+1).padStart(2,'0')}-${String(date.getDate()).padStart(2,'0')}`;
        }

        function parseDateKey(key){
            const [y,m,d] = key.split('-').map(Number);
            return new Date(y, m-1, d);
        }

        function getDayData(dateKey){
            return {
                appts: appointments.filter(a => a.appt_date === dateKey),
                availableSlots: availability.filter(a => a.availability_date === dateKey && a.status === 'available'),
                unavailableSlots: availability.filter(a => a.availability_date === dateKey && a.status !== 'available')
            };
        }

        function renderCalendar(){
            currentMonthLabel.textContent = formatMonthLabel(monthCursor);
            calendarGrid.innerHTML = '';

            const startDay = new Date(monthCursor.getFullYear(), monthCursor.getMonth(), 1);
            const endDay = new Date(monthCursor.getFullYear(), monthCursor.getMonth()+1, 0);
            const today = new Date(); today.setHours(0,0,0,0);

            const leadingBlanks = startDay.getDay();
            for (let i=0;i<leadingBlanks;i++){
                const cell = document.createElement('div');
                cell.className = 'day-card disabled';
                calendarGrid.appendChild(cell);
            }

            for (let day=1; day<=endDay.getDate(); day++){
                const dateObj = new Date(monthCursor.getFullYear(), monthCursor.getMonth(), day);
                const dateKey = getDateKey(dateObj);
                const data = getDayData(dateKey);

                const cell = document.createElement('div');
                cell.className = 'day-card';
                if (dateObj.getDay() === 0) cell.classList.add('sun-highlight');
                if (dateObj.getTime() === today.getTime()) cell.classList.add('today');
                const isPast = dateObj < today;
                if (isPast) cell.classList.add('disabled');

                const dayNum = document.createElement('div');
                dayNum.className = 'day-number';
                dayNum.textContent = day;
                cell.appendChild(dayNum);

                if (isPast){
                    calendarGrid.appendChild(cell);
                    continue;
                }

                const chips = document.createElement('div');
                chips.className = 'chip-row';
                if (data.availableSlots.length) chips.appendChild(makeChip(`${data.availableSlots.length} available`, 'chip-available'));
                if (data.appts.length) chips.appendChild(makeChip(`${data.appts.length} booked`, 'chip-booked'));
                if (data.unavailableSlots.length) chips.appendChild(makeChip(`${data.unavailableSlots.length} unavailable`, 'chip-unavailable'));
                if (chips.children.length) cell.appendChild(chips);

                cell.addEventListener('click', ()=>showDayDetail(dateObj, data.appts, data.availableSlots, data.unavailableSlots));

                calendarGrid.appendChild(cell);
            }
        }

        function makeChip(text, className){
            const chip = document.createElement('div');
            chip.className = `chip ${className}`;
            chip.textContent = text;
            return chip;
        }

        function showDayDetail(dateObj, appts, availableSlots, unavailableSlots){
            selectedDateKey = getDateKey(dateObj);
            detailDate.textContent = dateObj.toLocaleDateString(undefined,{weekday:'long',month:'long',day:'numeric',year:'numeric'});
            const summary = [];
            if (availableSlots.length) summary.push(`${availableSlots.length} available block${availableSlots.length===1?'':'s'}`);
            if (unavailableSlots.length) summary.push(`${unavailableSlots.length} unavailable block${unavailableSlots.length===1?'':'s'}`);
            if (appts.length) summary.push(`${appts.length} appointment${appts.length===1?'':'s'}`);
            detailSummary.textContent = summary.length ? summary.join(' • ') : 'No schedule for this day.';
            document.getElementById('btn-add-availability').disabled = false;
            updateFullDayButton(dateObj);

            const columns = [];
            if (availableSlots.length){
                const col = ['<div class="detail-card"><h4>Available</h4>'];
                availableSlots.forEach(slot => {
                    col.push(renderAvailabilityRow(slot));
                });
                col.push('</div>');
                columns.push(col.join(''));
            }
            if (unavailableSlots.length){
                const col = ['<div class="detail-card"><h4>Unavailable</h4>'];
                unavailableSlots.forEach(slot => {
                    col.push(renderAvailabilityRow(slot, true));
                });
                col.push('</div>');
                columns.push(col.join(''));
            }
            if (appts.length){
                const col = ['<div class="detail-card"><h4>Appointments</h4>'];
                appts.forEach(appt => {
                    const patient = appt.patient_name || `Patient #${appt.patient_id}`;
                    col.push(`<div class="list-item"><span>${appt.appt_time.substring(0,5)} • ${patient}</span><span>${(appt.status||'').toUpperCase()}</span></div>`);
                });
                col.push('</div>');
                columns.push(col.join(''));
            }
            detailContent.innerHTML = columns.length ? columns.join('') : '<div class="empty-state">No availability or appointments recorded for this day.</div>';
        }

        function updateFullDayButton(dateObj){
            if (!markUnavailableBtn) return;
            if (!dateObj){
                markUnavailableBtn.disabled = false;
                markUnavailableBtn.title = 'Select a date and click to mark it unavailable';
                return;
            }
            const today = new Date(); today.setHours(0,0,0,0);
            const isPast = dateObj < today;
            markUnavailableBtn.disabled = isPast;
            markUnavailableBtn.title = isPast ? 'Cannot mark past dates' : '';
        }

        function renderAvailabilityRow(slot, isUnavailable=false){
            const label = `${slot.start_time.substring(0,5)} – ${slot.end_time.substring(0,5)}${slot.notes ? ` • ${slot.notes}` : ''}`;
            return `<div class="list-item"><span>${label}</span><span class="list-actions"><button class="mini-btn" data-action="edit-availability" data-id="${slot.id}">Edit</button><button class="mini-btn danger" data-action="delete-availability" data-id="${slot.id}">Delete</button></span></div>`;
        }

        calendarGrid.addEventListener('click', e=>{
            const actionBtn = e.target.closest('button[data-action]');
            if (!actionBtn) return;
        });

        detailContent.addEventListener('click', e=>{
            const button = e.target.closest('button[data-action]');
            if (!button) return;
            const id = parseInt(button.dataset.id,10);
            if (button.dataset.action === 'edit-availability'){
                const slot = availability.find(a=>a.id===id);
                if (slot) openAvailabilityModal('edit', slot);
            } else if (button.dataset.action === 'delete-availability'){
                deleteAvailability(id);
            }
        });

        document.getElementById('btn-add-availability').addEventListener('click', ()=>{
            if (!selectedDateKey) return;
            openAvailabilityModal('add', { availability_date: selectedDateKey, start_time:'10:00', end_time:'11:00', status:'available', notes:'' });
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        function openAvailabilityModal(mode, slot){
            const modal = document.getElementById('availability-modal');
            modal.style.display = 'flex';
            document.getElementById('availability-modal-title').textContent = mode === 'edit' ? 'Edit Availability' : 'Add Availability';
            document.getElementById('availability-id-input').value = slot.id || '';
            document.getElementById('availability-date-input').value = slot.availability_date || selectedDateKey;
            document.getElementById('availability-start').value = slot.start_time ? slot.start_time.substring(0,5) : '10:00';
            document.getElementById('availability-end').value = slot.end_time ? slot.end_time.substring(0,5) : '11:00';
            document.getElementById('availability-status').value = slot.status || 'available';
            document.getElementById('availability-notes').value = slot.notes || '';
        }

        function closeAvailabilityModal(){
            document.getElementById('availability-modal').style.display = 'none';
        }

        document.getElementById('availability-cancel').addEventListener('click', closeAvailabilityModal);
        document.getElementById('availability-modal').addEventListener('click', e=>{ if (e.target.id === 'availability-modal') closeAvailabilityModal(); });

        document.getElementById('availability-form').addEventListener('submit', async (e)=>{
            e.preventDefault();
            const id = document.getElementById('availability-id-input').value;
            const payload = {
                doctor_id: doctorId,
                availability_date: document.getElementById('availability-date-input').value,
                start_time: document.getElementById('availability-start').value,
                end_time: document.getElementById('availability-end').value,
                status: document.getElementById('availability-status').value,
                notes: document.getElementById('availability-notes').value.trim()
            };
            if (!payload.availability_date) {
                alert('Please pick a date from the calendar first.');
                return;
            }
            if (payload.start_time >= payload.end_time){
                alert('End time must be after start time.');
                return;
            }
            const method = id ? 'PUT' : 'POST';
            if (id) payload.id = parseInt(id,10);
            try{
                const res = await fetch('api/availability.php', {
                    method,
                    headers:{'Content-Type':'application/json'},
                    body: JSON.stringify(payload)
                });
                const json = await res.json();
                if (!json.success) throw new Error(json.message||'Save failed');
                closeAvailabilityModal();
                await loadData();
            }catch(err){
                alert(err.message || 'Unable to save availability.');
            }
        });

        async function deleteAvailability(id){
            if (!confirm('Delete this availability block?')) return;
            try{
                const res = await fetch('api/availability.php', {
                    method:'DELETE',
                    headers:{'Content-Type':'application/json'},
                    body: JSON.stringify({ id })
                });
                const json = await res.json();
                if (!json.success) throw new Error(json.message||'Delete failed');
                await loadData();
            }catch(err){
                alert(err.message || 'Unable to delete availability.');
            }
        }

        document.getElementById('prev-month').addEventListener('click', ()=>{
            monthCursor = new Date(monthCursor.getFullYear(), monthCursor.getMonth()-1, 1);
            renderCalendar();
        });
        document.getElementById('next-month').addEventListener('click', ()=>{
            monthCursor = new Date(monthCursor.getFullYear(), monthCursor.getMonth()+1, 1);
            renderCalendar();
        });
        if (markUnavailableBtn){
            markUnavailableBtn.addEventListener('click', async ()=>{
                if (!selectedDateKey){
                    alert('Please select a date on the calendar first.');
                    return;
                }
                const dateObj = parseDateKey(selectedDateKey);
                const today = new Date(); today.setHours(0,0,0,0);
                if (dateObj < today){
                    alert('Past dates cannot be modified.');
                    return;
                }
                const niceDate = dateObj.toLocaleDateString(undefined,{weekday:'long',month:'long',day:'numeric',year:'numeric'});
                if (!confirm(`Mark ${niceDate} as unavailable for the entire day? This will cancel all appointments booked on this date.`)) return;
                const payload = {
                    doctor_id: doctorId,
                    availability_date: selectedDateKey,
                    start_time: '00:00',
                    end_time: '23:59',
                    status: 'unavailable',
                    notes: 'Full day unavailable'
                };
                try{
                    markUnavailableBtn.disabled = true;
                    markUnavailableBtn.textContent = 'Updating...';
                    const res = await fetch('api/availability.php', {
                        method:'POST',
                        headers:{'Content-Type':'application/json'},
                        body: JSON.stringify(payload)
                    });
                    const json = await res.json();
                    if (!json.success) throw new Error(json.message||'Unable to update availability.');
                    await loadData();
                    alert('Marked as unavailable for the entire day.');
                }catch(err){
                    console.error(err);
                    alert(err.message || 'Failed to mark the day unavailable.');
                }finally{
                    markUnavailableBtn.textContent = 'Mark Day Unavailable';
                    const selectedObj = selectedDateKey ? parseDateKey(selectedDateKey) : null;
                    updateFullDayButton(selectedObj);
                }
            });
        }

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
            applyTheme(current === 'dark' ? 'light' : 'dark');
        }

        (function init(){
            try{
                const savedTheme = localStorage.getItem('theme') || 'light';
                applyTheme(savedTheme);
            }catch(_e){}
            renderCalendar();
            loadData();
        })();
    </script>
</body>
</html>
