<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Manage Availability</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🗓️</text></svg>">
    <script>
        (function(){
            try{
                const savedTheme = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-theme', savedTheme);
            }catch(_e){}
        })();
    </script>
    <style>
        body {
            margin: 0;
            font-family: var(--font-family, 'Inter', sans-serif);
            background: var(--gray-50);
            color: var(--gray-900);
        }
        .page {
            max-width: 1080px;
            margin: 40px auto;
            padding: 0 var(--spacing-6);
            display: grid;
            gap: var(--spacing-6);
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: var(--spacing-4);
        }
        .logo {
            display:flex;
            align-items:center;
        }
        .logo img{display:block;height:36px;width:auto;}
        .tagline {
            font-size: var(--font-size-sm);
            color: var(--gray-500);
        }
        .theme-toggle {
            padding: 8px 14px;
            border-radius: 8px;
            border: 1px solid var(--gray-200);
            background: var(--white);
            color: var(--gray-700);
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        .theme-toggle:hover { background: var(--gray-100); }
        .card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            padding: var(--spacing-6);
        }
        [data-theme="dark"] body { color: #e2e8f0; background: #0b1120; }
        [data-theme="dark"] .card { background: #131c2c; border-color: #1f2937; box-shadow: 0 12px 32px rgba(0,0,0,0.45); }
        [data-theme="dark"] .card h2, [data-theme="dark"] .card h3, [data-theme="dark"] label { color: #f8fafc; }
        [data-theme="dark"] .card p, [data-theme="dark"] .card span, [data-theme="dark"] .card div { color: #cbd5f5; }
        [data-theme="dark"] input, [data-theme="dark"] select, [data-theme="dark"] textarea {
            background: #0f172a;
            border-color: #1f2937;
            color: #e2e8f0;
        }
        [data-theme="dark"] input::placeholder, [data-theme="dark"] textarea::placeholder { color: #64748b; }
        [data-theme="dark"] .availability-table th { background:#152136; color:#cbd5f5; }
        [data-theme="dark"] .availability-table tr:nth-child(even) { background:#10192b; }
        [data-theme="dark"] .availability-table td { border-color:#1f2a3b; color:#d1d5db; }
        [data-theme="dark"] .btn-outline { border-color:#1f2937; color:#cbd5f5; background:#152136; }
        [data-theme="dark"] .btn-outline:hover { background:#1e293b; }
        [data-theme="dark"] .theme-toggle { background:#152136; border-color:#1f2937; color:#cbd5f5; }
        [data-theme="dark"] .theme-toggle:hover { background:#1e293b; }
        [data-theme="dark"] .logo { color:#60a5fa; }
        [data-theme="dark"] .tagline { color:#94a3b8; }
        [data-theme="dark"] .summary { color:#94a3b8; }
        [data-theme="dark"] .badge-available { background:#064e3b; color:#d1fae5; }
        [data-theme="dark"] .badge-unavailable { background:#4338ca; color:#ede9fe; }
        [data-theme="dark"] .empty-state { background:#0f172a; border-color:#1f2937; color:#cbd5f5; }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: var(--spacing-4);
        }
        label {
            font-weight: 600;
            color: var(--gray-700);
            display: block;
            margin-bottom: var(--spacing-2);
        }
        input, select, textarea {
            width: 100%;
            padding: var(--spacing-3) var(--spacing-4);
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-md);
            font-size: var(--font-size-sm);
            font-family: inherit;
            transition: border-color 0.2s ease;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary-blue);
        }
        textarea {
            min-height: 84px;
            resize: vertical;
        }
        .form-actions {
            display: flex;
            gap: var(--spacing-3);
            justify-content: flex-end;
            margin-top: var(--spacing-4);
        }
        .btn {
            padding: 10px 16px;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.2s ease, transform 0.1s ease;
        }
        .btn-primary {
            background: var(--primary-blue);
            color: var(--white);
        }
        .btn-primary:hover { background: var(--primary-blue-dark); }
        .btn-outline {
            background: var(--white);
            border: 1px solid var(--gray-300);
            color: var(--gray-700);
        }
        .btn-outline:hover { background: var(--gray-100); }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--gray-200);
            text-align: left;
            font-size: var(--font-size-sm);
        }
        th {
            color: var(--gray-600);
            font-weight: 600;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-weight: 600;
            font-size: var(--font-size-xs);
        }
        .status-available {
            background: #dcfce7;
            color: #166534;
        }
        .status-unavailable {
            background: #fee2e2;
            color: #991b1b;
        }
        .table-actions {
            display: flex;
            gap: var(--spacing-2);
        }
        .table-btn {
            padding: 6px 12px;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: var(--font-size-xs);
        }
        .table-btn.edit {
            background: var(--primary-blue);
            color: var(--white);
        }
        .table-btn.delete {
            background: #ef4444;
            color: var(--white);
        }
        .empty-state {
            text-align: center;
            padding: var(--spacing-6);
            color: var(--gray-500);
        }
        .back-link {
            text-decoration: none;
            color: var(--gray-600);
            font-weight: 500;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="header">
            <div class="header-left">
                <div>
                    <div class="logo"><img src="public/assets/doxi-logo.svg?v=2" alt="DOXI" width="120" height="36"></div>
                    <div class="tagline">Manage Availability</div>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap: var(--spacing-3);">
                <button class="theme-toggle" id="theme-toggle" onclick="toggleTheme()">
                    <span id="theme-icon">🌙</span>
                    <span id="theme-text">Dark</span>
                </button>
                <a href="doctor-dashboard.php" class="back-link">← Back to Dashboard</a>
            </div>
        </div>

        <div class="card" aria-labelledby="availability-form-title">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: var(--spacing-4);">
                <div>
                    <h2 id="availability-form-title" style="margin:0;">Availability Editor</h2>
                    <p style="color:var(--gray-500); margin-top: var(--spacing-1);">Create or update your availability slots. Patients will only see the times marked as available.</p>
                </div>
                <div style="font-weight:600; color: var(--gray-600);" id="form-mode-label">Adding new slot</div>
            </div>
            <form id="availability-form">
                <input type="hidden" id="availability-id">
                <div class="form-grid">
                    <div>
                        <label for="availability-date">Date</label>
                        <input type="date" id="availability-date" required>
                    </div>
                    <div>
                        <label for="start-time">Start Time</label>
                        <input type="time" id="start-time" required>
                    </div>
                    <div>
                        <label for="end-time">End Time</label>
                        <input type="time" id="end-time" required>
                    </div>
                    <div>
                        <label for="status">Status</label>
                        <select id="status">
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                        </select>
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label for="notes">Notes (optional)</label>
                        <textarea id="notes" placeholder="Add context for this slot (e.g., Telehealth only)"></textarea>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" id="cancel-edit">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="save-slot">Save Slot</button>
                </div>
            </form>
        </div>

        <div class="card" aria-labelledby="availability-list-title">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: var(--spacing-4);">
                <h2 id="availability-list-title" style="margin:0;">Scheduled Availability</h2>
                <div style="color:var(--gray-500);" id="availability-count">Loading...</div>
            </div>
            <div class="table-wrapper">
                <table role="grid">
                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Time Window</th>
                            <th scope="col">Status</th>
                            <th scope="col">Notes</th>
                            <th scope="col">Updated</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="availability-table"></tbody>
                </table>
            </div>
            <div class="empty-state" id="availability-empty" hidden>No availability slots yet. Add your first slot above.</div>
        </div>
    </div>

    <script>
        if (sessionStorage.getItem('isLoggedIn') !== 'true' || sessionStorage.getItem('userRole') !== 'doctor') {
            window.location.href = 'login.php';
        }

        const doctorId = parseInt(sessionStorage.getItem('userId') || '0', 10);
        if (!doctorId) {
            alert('Doctor information not found. Please sign in again.');
            window.location.href = 'login.php';
        }

        function formatLocalDate(date){
            const year = date.getFullYear();
            const month = String(date.getMonth()+1).padStart(2,'0');
            const day = String(date.getDate()).padStart(2,'0');
            return `${year}-${month}-${day}`;
        }

        const todayIso = (() => {
            const today = new Date();
            today.setHours(0,0,0,0);
            return formatLocalDate(today);
        })();

        const availabilityIdInput = document.getElementById('availability-id');
        const dateInput = document.getElementById('availability-date');
        const startInput = document.getElementById('start-time');
        const endInput = document.getElementById('end-time');
        const statusInput = document.getElementById('status');
        const notesInput = document.getElementById('notes');
        const formModeLabel = document.getElementById('form-mode-label');
        const availabilityTable = document.getElementById('availability-table');
        const emptyState = document.getElementById('availability-empty');
        const countLabel = document.getElementById('availability-count');
        const saveButton = document.getElementById('save-slot');
        const cancelButton = document.getElementById('cancel-edit');

        let availabilityCache = [];
        let isEditing = false;

        function setDateBounds() {
            const today = new Date();
            today.setHours(0,0,0,0);
            const max = new Date(today.getTime() + 180*24*60*60*1000);
            dateInput.min = formatLocalDate(today);
            dateInput.max = formatLocalDate(max);
            dateInput.value = formatLocalDate(today);
            startInput.value = '10:00';
            endInput.value = '11:00';
        }

        function resetForm() {
            availabilityIdInput.value = '';
            isEditing = false;
            formModeLabel.textContent = 'Adding new slot';
            saveButton.textContent = 'Save Slot';
            notesInput.value = '';
            statusInput.value = 'available';
            setDateBounds();
        }

        function formatTime(timeString) {
            if (!timeString) return '';
            const [hour, minute] = timeString.split(':');
            const date = new Date();
            date.setHours(parseInt(hour, 10), parseInt(minute, 10));
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }

        function renderTable() {
            if (!availabilityCache.length) {
                availabilityTable.innerHTML = '';
                emptyState.hidden = false;
                countLabel.textContent = '0 slots scheduled';
                return;
            }
            emptyState.hidden = true;
            countLabel.textContent = `${availabilityCache.length} slot${availabilityCache.length === 1 ? '' : 's'} scheduled`;
            availabilityTable.innerHTML = availabilityCache.map(entry => {
                const statusClass = entry.status === 'available' ? 'status-available' : 'status-unavailable';
                const statusLabel = entry.status === 'available' ? 'Available' : 'Unavailable';
                const timeWindow = `${formatTime(entry.start_time)} – ${formatTime(entry.end_time)}`;
                const updated = entry.updated_at ? new Date(entry.updated_at).toLocaleString() : '—';
                const notes = entry.notes ? entry.notes : '—';
                return `
                    <tr data-id="${entry.id}">
                        <td>${entry.availability_date}</td>
                        <td>${timeWindow}</td>
                        <td><span class="status-badge ${statusClass}">${statusLabel}</span></td>
                        <td>${notes}</td>
                        <td>${updated}</td>
                        <td>
                            <div class="table-actions">
                                <button type="button" class="table-btn edit" data-action="edit" data-id="${entry.id}">Edit</button>
                                <button type="button" class="table-btn delete" data-action="delete" data-id="${entry.id}">Delete</button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function loadAvailability() {
            countLabel.textContent = 'Loading...';
            availabilityTable.innerHTML = '<tr><td colspan="6">Loading availability...</td></tr>';
            try {
                const res = await fetch(`api/availability.php?doctor_id=${doctorId}`);
                const data = await res.json();
                if (data.success) {
                    availabilityCache = data.data || [];
                    renderTable();
                } else {
                    availabilityTable.innerHTML = '<tr><td colspan="6">Failed to load availability.</td></tr>';
                    countLabel.textContent = '—';
                }
            } catch (error) {
                availabilityTable.innerHTML = '<tr><td colspan="6">Error loading availability.</td></tr>';
                countLabel.textContent = '—';
            }
        }

        function validateTimes(startTime, endTime, date) {
            if (!startTime || !endTime) return false;
            const start = Date.parse(`1970-01-01T${startTime}:00`);
            const end = Date.parse(`1970-01-01T${endTime}:00`);
            if (start >= end) return false;
            if (date && date < todayIso) return false;
            return true;
        }

        async function saveAvailability(event) {
            event.preventDefault();
            const payload = {
                doctor_id: doctorId,
                availability_date: dateInput.value,
                start_time: startInput.value,
                end_time: endInput.value,
                status: statusInput.value,
                notes: notesInput.value.trim()
            };

            if (!validateTimes(payload.start_time, payload.end_time, payload.availability_date)) {
                alert('Please choose a future date with an end time after the start time.');
                return;
            }

            saveButton.disabled = true;
            saveButton.textContent = isEditing ? 'Updating...' : 'Saving...';

            try {
                let response;
                if (isEditing && availabilityIdInput.value) {
                    payload.id = parseInt(availabilityIdInput.value, 10);
                    response = await fetch('api/availability.php', {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                } else {
                    response = await fetch('api/availability.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                }
                const result = await response.json();
                if (!result.success) {
                    throw new Error(result.message || 'Save failed');
                }
                resetForm();
                await loadAvailability();
            } catch (error) {
                alert(error.message || 'Failed to save availability');
            } finally {
                saveButton.disabled = false;
                saveButton.textContent = isEditing ? 'Update Slot' : 'Save Slot';
            }
        }

        async function deleteAvailability(id) {
            if (!confirm('Delete this availability slot?')) return;
            try {
                const res = await fetch('api/availability.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                const result = await res.json();
                if (!result.success) {
                    throw new Error(result.message || 'Delete failed');
                }
                await loadAvailability();
            } catch (error) {
                alert(error.message || 'Failed to delete availability');
            }
        }

        availabilityTable.addEventListener('click', (event) => {
            const target = event.target;
            const action = target.dataset.action;
            const id = parseInt(target.dataset.id || '0', 10);
            if (!action || !id) return;
            const entry = availabilityCache.find(item => item.id === id);
            if (!entry) return;

            if (action === 'edit') {
                isEditing = true;
                availabilityIdInput.value = entry.id;
                dateInput.value = entry.availability_date;
                startInput.value = entry.start_time.substring(0,5);
                endInput.value = entry.end_time.substring(0,5);
                statusInput.value = entry.status;
                notesInput.value = entry.notes || '';
                formModeLabel.textContent = 'Editing existing slot';
                saveButton.textContent = 'Update Slot';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else if (action === 'delete') {
                deleteAvailability(id);
            }
        });

        cancelButton.addEventListener('click', resetForm);
        document.getElementById('availability-form').addEventListener('submit', saveAvailability);

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

        (function init(){
            resetForm();
            loadAvailability();
            try{
                const savedTheme = localStorage.getItem('theme') || 'light';
                applyTheme(savedTheme);
            }catch(_e){}
        })();
    </script>
</body>
</html>


