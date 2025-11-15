<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Doctor Appointments</title>
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
        .logo { font-size: var(--font-size-2xl); font-weight: 800; color: var(--primary-blue); }
        .logo img{display:block;height:36px;width:auto;}
        .tagline { font-size: var(--font-size-sm); color: var(--gray-500); margin-top: 2px; }
        .theme-toggle { padding: 8px 14px; border-radius: 8px; border: 1px solid var(--gray-200); background: var(--white); color: var(--gray-700); cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .theme-toggle:hover { background: var(--gray-100); }
        .back { text-decoration:none; color: var(--gray-600); }
        .back:hover { text-decoration: underline; }
        
        /* Filter Tabs Container */
        .filter-tabs-container {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            padding: var(--spacing-6);
            margin-bottom: var(--spacing-6);
        }
        .filter-tabs-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--spacing-4);
            flex-wrap: wrap;
            gap: var(--spacing-4);
        }
        .filter-tabs {
            display: flex;
            gap: var(--spacing-3);
            flex-wrap: wrap;
            flex: 1;
        }
        .filter-tab {
            padding: 10px 16px;
            border-radius: var(--radius-md);
            border: 1px solid var(--gray-200);
            background: var(--white);
            color: var(--gray-700);
            font-weight: 500;
            font-size: var(--font-size-sm);
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .filter-tab:hover {
            background: var(--gray-100);
            border-color: var(--gray-300);
        }
        .filter-tab.active {
            background: var(--primary-blue);
            color: var(--white);
            border-color: var(--primary-blue);
        }
        .filter-tab-count {
            margin-left: var(--spacing-2);
            padding: 2px 6px;
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.1);
            font-size: var(--font-size-xs);
            font-weight: 600;
        }
        .filter-tab.active .filter-tab-count {
            background: rgba(255, 255, 255, 0.3);
        }
        
        /* Search Container */
        .search-container {
            display: flex;
            gap: var(--spacing-2);
            align-items: center;
            min-width: 300px;
        }
        .search-input {
            flex: 1;
            padding: 10px 16px;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-md);
            font-family: var(--font-family);
            font-size: var(--font-size-sm);
            transition: border-color 0.2s ease;
        }
        .search-input:focus {
            outline: none;
            border-color: var(--primary-blue);
        }
        .search-btn {
            padding: 10px 16px;
            background: var(--primary-blue);
            color: var(--white);
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-size: var(--font-size-sm);
            font-weight: 600;
            transition: background-color 0.2s ease;
        }
        .search-btn:hover {
            background: var(--primary-blue-dark);
        }
        
        /* Table Styles - Matching Patient Panel */
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 12px 14px; border-bottom: 1px solid var(--gray-200); text-align: left; }
        .table th { color: var(--gray-600); font-weight: 600; }
        
        /* Status Pills - Matching Patient Panel */
        .pill { padding: 4px 10px; border-radius: 9999px; font-size: var(--font-size-xs); font-weight: 700; }
        .pill.scheduled { background:#e0e7ff; color:#3730a3; }
        .pill.completed { background:#D1FADF; color:#0F5132; }
        .pill.cancelled { background:#fee2e2; color:#991b1b; }
        .pill.confirmed { background:#dcfce7; color:#166534; }
        .pill.pending { background:#fef3c7; color:#92400e; }
        
        /* Actions */
        .actions { display:flex; gap:8px; flex-wrap: wrap; }
        
        /* Action Buttons */
        .btn-action {
            padding: 6px 14px;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            font-size: var(--font-size-sm);
            font-weight: 600;
            transition: all 0.2s ease;
            white-space: nowrap;
            min-width: 70px;
            text-align: center;
        }
        
        .btn-action-view {
            background: #007BFF;
            color: #ffffff;
        }
        
        .btn-action-view:hover {
            background: #0056b3;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
        }
        
        .btn-action-complete {
            background: #28A745;
            color: #ffffff;
        }
        
        .btn-action-complete:hover {
            background: #218838;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
        }
        
        .btn-action-cancel {
            background: #DC3545;
            color: #ffffff;
        }
        
        .btn-action-cancel:hover {
            background: #c82333;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
        }
        
        /* Legacy link styles for other uses */
        .link { color: var(--primary-blue); font-weight:600; text-decoration:none; cursor: pointer; }
        .link:hover { text-decoration: underline; }
        
        /* Modal styles - Matching Patient Panel */
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
        .modal-footer { display: flex; justify-content: flex-end; gap: var(--spacing-3); padding-top: var(--spacing-4); border-top: 1px solid var(--gray-200); margin-top: var(--spacing-4); }
        .btn { padding: var(--spacing-3) var(--spacing-4); border-radius: var(--radius-md); border: none; cursor: pointer; font-weight: 600; font-size: var(--font-size-sm); transition: all 0.2s ease; }
        .btn-primary { background: var(--primary-blue); color: var(--white); }
        .btn-primary:hover { background: var(--primary-blue-dark); }
        .btn-secondary { background: var(--gray-100); color: var(--gray-700); }
        .btn-secondary:hover { background: var(--gray-200); }
        .btn-danger { background: #ef4444; color: var(--white); }
        .btn-danger:hover { background: #dc2626; }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 3rem 1rem; color: var(--gray-500); }
        .empty-state-icon { font-size: 3rem; margin-bottom: var(--spacing-4); opacity: 0.5; }
        .empty-state-text { font-size: var(--font-size-base); font-weight: 500; }
        
        /* Loading State */
        .loading-state { text-align: center; padding: 3rem 1rem; color: var(--gray-500); }
        .loading-spinner { display: inline-block; width: 2rem; height: 2rem; border: 3px solid var(--gray-200); border-top-color: var(--primary-blue); border-radius: 50%; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        
        /* Responsive Design */
        @media (max-width: 900px) {
            .filter-tabs-header { flex-direction: column; align-items: stretch; }
            .filter-tabs { width: 100%; overflow-x: auto; flex-wrap: nowrap; }
            .search-container { width: 100%; min-width: auto; }
            .table { font-size: var(--font-size-xs); }
            .table th, .table td { padding: 8px 10px; }
            .actions { flex-direction: column; gap: 6px; }
            .btn-action { 
                width: 100%;
                min-width: auto;
                padding: 8px 12px;
            }
        }
        @media (max-width: 600px) {
            .table { display: block; overflow-x: auto; }
            .filter-tabs { gap: var(--spacing-2); }
            .filter-tab { padding: 8px 12px; font-size: var(--font-size-xs); }
            .actions { gap: 4px; }
            .btn-action { 
                font-size: var(--font-size-xs);
                padding: 6px 10px;
            }
        }
    </style>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📅</text></svg>">
</head>
<body>
    <div class="page">
        <div class="header">
            <div>
                <div class="logo"><img src="public/assets/doxi-logo.svg?v=2" alt="DOXI" width="120" height="36"></div>
                <div class="tagline">Doctor Appointments</div>
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
            <!-- Filter Tabs and Search -->
            <div class="card">
                <h3 style="margin-bottom: var(--spacing-4); color: var(--gray-900);">Filter & Search Appointments</h3>
                <div class="filter-tabs-header">
                    <div class="filter-tabs">
                        <button class="filter-tab" data-filter="today" onclick="filterAppointments('today')">
                            Today
                            <span class="filter-tab-count" id="count-today">0</span>
                        </button>
                        <button class="filter-tab" data-filter="tomorrow" onclick="filterAppointments('tomorrow')">
                            Tomorrow
                            <span class="filter-tab-count" id="count-tomorrow">0</span>
                        </button>
                        <button class="filter-tab" data-filter="upcoming" onclick="filterAppointments('upcoming')">
                            Upcoming
                            <span class="filter-tab-count" id="count-upcoming">0</span>
                        </button>
                        <button class="filter-tab" data-filter="pending" onclick="filterAppointments('pending')">
                            Pending
                            <span class="filter-tab-count" id="count-pending">0</span>
                        </button>
                        <button class="filter-tab" data-filter="completed" onclick="filterAppointments('completed')">
                            Completed
                            <span class="filter-tab-count" id="count-completed">0</span>
                        </button>
                        <button class="filter-tab" data-filter="cancelled" onclick="filterAppointments('cancelled')">
                            Cancelled
                            <span class="filter-tab-count" id="count-cancelled">0</span>
                        </button>
                        <button class="filter-tab active" data-filter="all" onclick="filterAppointments('all')">
                            All
                            <span class="filter-tab-count" id="count-all">0</span>
                        </button>
                    </div>
                    <div class="search-container">
                        <input 
                            type="text" 
                            class="search-input" 
                            id="search-input" 
                            placeholder="Search by date (DD-MM-YYYY) or patient name..."
                            onkeypress="handleSearchKeyPress(event)"
                        >
                        <button class="search-btn" onclick="performSearch()">🔍 Search</button>
                    </div>
                </div>
            </div>

            <!-- Appointments Table -->
            <div class="card">
                <h3 id="appointments-title" style="margin-bottom: var(--spacing-4); color: var(--gray-900);">My Appointments</h3>
                <div id="appointments-content">
                    <div class="loading-state">
                        <div class="loading-spinner"></div>
                        <p style="margin-top: var(--spacing-4);">Loading appointments...</p>
                    </div>
                </div>
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
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeViewModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Reject Confirmation Modal -->
    <div id="cancel-confirmation-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Reject Appointment</h3>
                <button class="close-modal" onclick="closeCancelModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p id="cancel-confirmation-text" style="font-size: var(--font-size-base); color: var(--gray-700); margin: 0;">
                    Are you sure you want to reject this appointment?
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeCancelModal()">No</button>
                <button class="btn btn-danger" onclick="confirmCancelAppointment()">Yes</button>
            </div>
        </div>
    </div>

    <!-- Approve Confirmation Modal -->
    <div id="complete-confirmation-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Approve Appointment</h3>
                <button class="close-modal" onclick="closeCompleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p id="complete-confirmation-text" style="font-size: var(--font-size-base); color: var(--gray-700); margin: 0;">
                    Are you sure you want to mark this appointment as approved?
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeCompleteModal()">No</button>
                <button class="btn btn-primary" onclick="confirmCompleteAppointment()">Yes</button>
            </div>
        </div>
    </div>

    <script>
        if (sessionStorage.getItem('isLoggedIn') !== 'true' || sessionStorage.getItem('userRole') !== 'doctor') {
            window.location.href = 'login.php';
        }

        // Global variables
        let doctorId = null;
        let allAppointments = [];
        let currentFilter = 'all';
        let currentSearchTerm = '';
        let initialParamsApplied = false;
        let initialPatientName = '';
        let initialPatientId = '';

        /**
         * Authentication Check
         * Verifies if doctor is logged in, redirects to login if not
         */
        function checkAuthentication() {
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
                    
                    // Check profile completion
                    await checkProfileCompletion();
                    
                    loadAllAppointments();
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
         * Check Profile Completion
         * Checks if doctor profile is complete and redirects if not
         */
        async function checkProfileCompletion() {
            const currentPage = window.location.pathname.split('/').pop();
            if (currentPage === 'doctor-settings.php') {
                return true;
            }

            const profileCompleteFlag = sessionStorage.getItem('profileComplete') || localStorage.getItem('doctorProfileCompleted');
            if (profileCompleteFlag === 'true') {
                return true;
            }

            if (!doctorId) return true;
            
            try {
                const response = await fetch(`api/users.php?id=${doctorId}`);
                const result = await response.json();
                
                if (result.success && result.data) {
                    const doctor = result.data;
                    
                    const requiredFields = {
                        'first_name': doctor.first_name,
                        'last_name': doctor.last_name,
                        'email': doctor.email,
                        'gender': doctor.gender,
                        'specialty': doctor.specialty,
                        'license_number': doctor.license_number,
                        'years_experience': doctor.years_experience
                    };

                    const incompleteFields = Object.keys(requiredFields).filter(key => {
                        const value = requiredFields[key];
                        if (!value) return true;
                        if (typeof value === 'string' && value.trim() === '') return true;
                        if (key === 'years_experience' && (value === null || value === '' || value === undefined || value === 0)) return true;
                        return false;
                    });

                    if (incompleteFields.length > 0) {
                        if (currentPage !== 'doctor-settings.php') {
                            alert('Please complete your profile before accessing other modules.');
                            window.location.href = 'doctor-settings.php';
                            return false;
                        }
                    } else {
                        sessionStorage.setItem('profileComplete', 'true');
                        localStorage.setItem('doctorProfileCompleted', 'true');
                    }
                }
                
                return true;
            } catch (error) {
                console.error('Error checking profile completion:', error);
                return true;
            }
        }

        /**
         * Load All Appointments
         * Fetches all appointments for the doctor from API
         */
        async function loadAllAppointments() {
            if (!doctorId) {
                document.getElementById('appointments-content').innerHTML = 
                    '<div class="empty-state"><div class="empty-state-icon">📅</div><div class="empty-state-text">Doctor ID not available</div></div>';
                return;
            }

            try {
                const response = await fetch(`api/appointments.php?doctor_id=${doctorId}`);
                const result = await response.json();
                
                if (result.success && result.data) {
                    allAppointments = result.data;
                    updateFilterCounts();
                    applyInitialQueryParams();
                    displayAppointments();
                } else {
                    allAppointments = [];
                    updateFilterCounts();
                    applyInitialQueryParams();
                    displayAppointments();
                }
            } catch (error) {
                console.error('Error loading appointments:', error);
                document.getElementById('appointments-content').innerHTML = 
                    '<div class="empty-state"><div class="empty-state-icon">⚠️</div><div class="empty-state-text">Error loading appointments. Please try again later.</div></div>';
            }
        }

        function applyInitialQueryParams(){
            if (initialParamsApplied) return;
            initialParamsApplied = true;

            const params = new URLSearchParams(window.location.search);
            const searchInput = document.getElementById('search-input');
            if (!searchInput) return;

            initialPatientName = params.get('patientName') || '';
            initialPatientId = params.get('patientId') || '';

            let resolvedSearch = initialPatientName;

            if (initialPatientId) {
                const match = allAppointments.find(apt => String(apt.patient_id) === String(initialPatientId) && apt.patient_name);
                if (match && match.patient_name) {
                    resolvedSearch = match.patient_name;
                }
            }

            if (resolvedSearch) {
                searchInput.value = resolvedSearch;
                currentSearchTerm = resolvedSearch;
                currentFilter = 'all';
                document.querySelectorAll('.filter-tab').forEach(tab => tab.classList.remove('active'));
                const allTab = document.querySelector('.filter-tab[data-filter="all"]');
                if (allTab) allTab.classList.add('active');
            }
        }

        /**
         * Update Filter Counts
         * Calculates and updates the count badges for each filter tab
         */
        function updateFilterCounts() {
            const today = new Date().toISOString().split('T')[0];
            const tomorrow = new Date(Date.now() + 86400000).toISOString().split('T')[0];
            const todayDate = new Date(today);
            
            let todayCount = 0;
            let tomorrowCount = 0;
            let upcomingCount = 0;
            let pendingCount = 0;
            let completedCount = 0;
            let cancelledCount = 0;

            allAppointments.forEach(apt => {
                const aptDate = apt.appt_date;
                const status = (apt.status || '').toLowerCase();
                
                if (aptDate === today) {
                    todayCount++;
                } else if (aptDate === tomorrow) {
                    tomorrowCount++;
                } else if (aptDate && new Date(aptDate) > todayDate) {
                    upcomingCount++;
                }
                
                if (status === 'pending' || status === 'scheduled') {
                    pendingCount++;
                } else if (status === 'completed' || status === 'finished') {
                    completedCount++;
                } else if (status === 'cancelled' || status === 'canceled') {
                    cancelledCount++;
                }
            });

            document.getElementById('count-today').textContent = todayCount;
            document.getElementById('count-tomorrow').textContent = tomorrowCount;
            document.getElementById('count-upcoming').textContent = upcomingCount;
            document.getElementById('count-pending').textContent = pendingCount;
            document.getElementById('count-completed').textContent = completedCount;
            document.getElementById('count-cancelled').textContent = cancelledCount;
            document.getElementById('count-all').textContent = allAppointments.length;
        }

        /**
         * Filter Appointments
         * Filters appointments based on selected category
         */
        function filterAppointments(filter) {
            currentFilter = filter;
            currentSearchTerm = '';
            document.getElementById('search-input').value = '';
            
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelector(`.filter-tab[data-filter="${filter}"]`).classList.add('active');
            
            displayAppointments();
        }

        /**
         * Perform Search
         * Searches appointments by date or patient name
         * Automatically switches to "All Appointments" filter when searching
         */
        function performSearch() {
            const searchTerm = document.getElementById('search-input').value.trim();
            
            // Clear search if empty
            if (!searchTerm) {
                clearSearch();
                return;
            }
            
            // Store original search term (for date matching) and lowercase version (for name matching)
            currentSearchTerm = searchTerm;
            
            // Automatically switch to "All Appointments" filter when searching
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            currentFilter = 'all';
            document.querySelector('.filter-tab[data-filter="all"]').classList.add('active');
            
            displayAppointments();
        }

        /**
         * Clear Search
         * Clears search and resets filter to "All Appointments"
         */
        function clearSearch() {
            currentSearchTerm = '';
            document.getElementById('search-input').value = '';
            
            // Reset to "All Appointments" filter
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            currentFilter = 'all';
            document.querySelector('.filter-tab[data-filter="all"]').classList.add('active');
            
            displayAppointments();
        }

        /**
         * Handle Search Key Press
         * Allows Enter key to trigger search
         */
        function handleSearchKeyPress(event) {
            if (event.key === 'Enter') {
                performSearch();
            }
        }

        /**
         * Display Appointments
         * Renders filtered/searched appointments in the table
         */
        function displayAppointments() {
            let filteredAppointments = [...allAppointments];

            // If searching, apply search first and ignore filter
            // When searching, show results from ALL categories
            if (currentSearchTerm) {
                const searchLower = currentSearchTerm.toLowerCase();
                filteredAppointments = filteredAppointments.filter(apt => {
                    const patientName = (apt.patient_name || '').toLowerCase();
                    const aptDate = apt.appt_date || '';
                    
                    // Search by patient name (partial or full match)
                    if (patientName.includes(searchLower)) {
                        return true;
                    }
                    
                    // Search by date in DD-MM-YYYY format (with "-" separator)
                    const dateRegexDash = /^(\d{2})-(\d{2})-(\d{4})$/;
                    if (dateRegexDash.test(currentSearchTerm)) {
                        try {
                            const [day, month, year] = currentSearchTerm.split('-');
                            // Validate date values
                            const dayNum = parseInt(day, 10);
                            const monthNum = parseInt(month, 10);
                            const yearNum = parseInt(year, 10);
                            
                            // Basic validation: day 1-31, month 1-12, year 1900-2100
                            if (dayNum >= 1 && dayNum <= 31 && monthNum >= 1 && monthNum <= 12 && yearNum >= 1900 && yearNum <= 2100) {
                                const formattedDate = `${year}-${month}-${day}`;
                                return aptDate === formattedDate;
                            }
                        } catch (e) {
                            // Invalid date format, continue to other checks
                        }
                    }
                    
                    // Search by date in DD/MM/YYYY format (with "/" separator)
                    const dateRegexSlash = /^(\d{2})\/(\d{2})\/(\d{4})$/;
                    if (dateRegexSlash.test(currentSearchTerm)) {
                        try {
                            const [day, month, year] = currentSearchTerm.split('/');
                            // Validate date values
                            const dayNum = parseInt(day, 10);
                            const monthNum = parseInt(month, 10);
                            const yearNum = parseInt(year, 10);
                            
                            // Basic validation: day 1-31, month 1-12, year 1900-2100
                            if (dayNum >= 1 && dayNum <= 31 && monthNum >= 1 && monthNum <= 12 && yearNum >= 1900 && yearNum <= 2100) {
                                const formattedDate = `${year}-${month}-${day}`;
                                return aptDate === formattedDate;
                            }
                        } catch (e) {
                            // Invalid date format, continue to other checks
                        }
                    }
                    
                    // Also handle YYYY-MM-DD format for backward compatibility
                    if (aptDate.includes(currentSearchTerm)) {
                        return true;
                    }
                    
                    return false;
                });
            } else {
                // Apply filter only when not searching
                if (currentFilter !== 'all') {
                    const today = new Date().toISOString().split('T')[0];
                    const tomorrow = new Date(Date.now() + 86400000).toISOString().split('T')[0];
                    const todayDate = new Date(today);

                    filteredAppointments = filteredAppointments.filter(apt => {
                        const aptDate = apt.appt_date;
                        const status = (apt.status || '').toLowerCase();
                        
                        switch (currentFilter) {
                            case 'today':
                                return aptDate === today;
                            case 'tomorrow':
                                return aptDate === tomorrow;
                            case 'upcoming':
                                return aptDate && new Date(aptDate) > todayDate;
                            case 'pending':
                                return status === 'pending' || status === 'scheduled';
                            case 'completed':
                                return status === 'completed' || status === 'finished';
                            case 'cancelled':
                                return status === 'cancelled' || status === 'canceled';
                            default:
                                return true;
                        }
                    });
                }
            }

            // Sort by date (most recent first)
            filteredAppointments.sort((a, b) => {
                const dateA = new Date(`${a.appt_date} ${a.appt_time || '00:00:00'}`);
                const dateB = new Date(`${b.appt_date} ${b.appt_time || '00:00:00'}`);
                return dateB - dateA;
            });

            // Render appointments
            const contentDiv = document.getElementById('appointments-content');
            
            if (filteredAppointments.length === 0) {
                // Show appropriate message based on whether we're searching or filtering
                let emptyMessage = 'No appointments found';
                if (currentSearchTerm) {
                    emptyMessage = `No appointments found for the given date or name: "${currentSearchTerm}"`;
                } else if (currentFilter !== 'all') {
                    emptyMessage = `No appointments found for ${currentFilter} appointments`;
                }
                
                contentDiv.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">📅</div>
                        <div class="empty-state-text">${emptyMessage}</div>
                    </div>
                `;
                return;
            }

            // Build table HTML - Matching Patient Panel Style
            let tableHTML = `
                <table class="table" id="appt-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Patient</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            filteredAppointments.forEach(apt => {
                const appointmentDate = formatDate(apt.appt_date);
                const appointmentTime = apt.appt_time ? formatTime(apt.appt_time) : 'N/A';
                const patientName = apt.patient_name || 'Unknown Patient';
                const status = apt.status || 'scheduled';
                const statusLower = (status || '').toLowerCase();
                const statusClass = getStatusClass(status);
                const statusText = status.charAt(0).toUpperCase() + status.slice(1);
                
                // Show Complete button only for Scheduled or Pending appointments
                const showCompleteButton = (statusLower === 'scheduled' || statusLower === 'pending' || statusLower === 'confirmed');
                
                tableHTML += `
                    <tr>
                        <td>${appointmentDate}</td>
                        <td>${appointmentTime}</td>
                        <td>${patientName}</td>
                        <td><span class="pill ${statusClass}">${statusText}</span></td>
                        <td class="actions">
                            <button class="btn-action btn-action-view" onclick="viewAppt(${apt.id})">View</button>
                            ${showCompleteButton ? `<button class="btn-action btn-action-complete" onclick="showCompleteConfirmation(${apt.id})">Approve</button>` : ''}
                            <button class="btn-action btn-action-cancel" onclick="showCancelConfirmation(${apt.id})">Reject</button>
                        </td>
                    </tr>
                `;
            });

            tableHTML += `
                    </tbody>
                </table>
            `;

            contentDiv.innerHTML = tableHTML;
        }

        /**
         * Format Date
         * Converts YYYY-MM-DD to DD/MM/YYYY format
         */
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            try {
                const date = new Date(dateString);
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const day = date.getDate().toString().padStart(2, '0');
                const year = date.getFullYear();
                return `${day}/${month}/${year}`;
            } catch (error) {
                return dateString;
            }
        }

        /**
         * Format Time
         * Converts HH:MM:SS to HH:MM AM/PM format
         */
        function formatTime(timeString) {
            if (!timeString) return 'N/A';
            try {
                const [hours, minutes] = timeString.split(':');
                const hour = parseInt(hours);
                const ampm = hour >= 12 ? 'PM' : 'AM';
                const hour12 = hour % 12 || 12;
                return `${hour12}:${minutes} ${ampm}`;
            } catch (error) {
                return timeString;
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

        function formatDateTimeParts(dateStr, timeStr){
            if (!dateStr) return dateStr || '—';
            const [year, month, day] = dateStr.split('-').map(Number);
            if (!year || !month || !day) return dateStr;
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

        /**
         * View Appointment
         * Opens modal showing appointment details
         */
        async function viewAppt(id) {
            const appointment = allAppointments.find(apt => apt.id == id);
            
            if (!appointment) {
                alert('Appointment not found');
                return;
            }

            const modalBody = document.getElementById('modal-body');
            const appointmentDate = formatDate(appointment.appt_date);
            const appointmentTime = formatTime(appointment.appt_time);
            const status = appointment.status || 'scheduled';
            const statusClass = getStatusClass(status);
            const statusText = status.charAt(0).toUpperCase() + status.slice(1);
            
            modalBody.innerHTML = `
                <div class="detail-row">
                    <div class="detail-label">Patient Name:</div>
                    <div class="detail-value">${appointment.patient_name || 'N/A'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Appointment ID:</div>
                    <div class="detail-value">#${appointment.id || 'N/A'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Date:</div>
                    <div class="detail-value">${appointmentDate || 'N/A'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Time:</div>
                    <div class="detail-value">${appointmentTime || 'N/A'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Status:</div>
                    <div class="detail-value"><span class="pill ${statusClass}">${statusText}</span></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Patient Email:</div>
                    <div class="detail-value">${appointment.patient_email || 'N/A'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Patient Phone:</div>
                    <div class="detail-value">${appointment.patient_phone || 'N/A'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Notes:</div>
                    <div class="detail-value">${appointment.notes || 'No notes provided'}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Created:</div>
                    <div class="detail-value">${appointment.created_at ? new Date(appointment.created_at).toLocaleString() : 'N/A'}</div>
                </div>
            `;
            document.getElementById('view-modal').style.display = 'block';
        }

        /**
         * Close View Modal
         * Hides the appointment details modal
         */
        function closeViewModal() {
            document.getElementById('view-modal').style.display = 'none';
        }

        /**
         * Show Cancel Confirmation Modal
         * Displays a confirmation popup before canceling appointment
         */
        function showCancelConfirmation(appointmentId) {
            const appointment = allAppointments.find(apt => apt.id == appointmentId);
            const patientName = appointment ? appointment.patient_name : 'this appointment';
            
            const modal = document.getElementById('cancel-confirmation-modal');
            modal.dataset.appointmentId = appointmentId;
            
            document.getElementById('cancel-confirmation-text').textContent = `Are you sure you want to reject appointment #${appointmentId} for ${patientName}?`;
            
            modal.style.display = 'block';
        }

        /**
         * Close Cancel Confirmation Modal
         * Hides the cancellation confirmation modal
         */
        function closeCancelModal() {
            document.getElementById('cancel-confirmation-modal').style.display = 'none';
        }

        /**
         * Confirm Cancel Appointment
         * Called when user clicks Yes in the confirmation modal
         */
        function confirmCancelAppointment() {
            const modal = document.getElementById('cancel-confirmation-modal');
            const appointmentId = modal.dataset.appointmentId;
            
            if (appointmentId) {
                cancelAppointment(parseInt(appointmentId));
            }
        }

        /**
         * Cancel Appointment
         * Cancels the selected appointment after confirmation
         */
        async function cancelAppointment(appointmentId) {
            closeCancelModal();

            try {
                const response = await fetch('api/appointments.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: appointmentId,
                        status: 'cancelled'
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    alert('Appointment rejected successfully!');
                    await loadAllAppointments();
                } else {
                    alert('Failed to reject appointment: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error rejecting appointment:', error);
                alert('Error rejecting appointment. Please try again.');
            }
        }

        /**
         * Show Complete Confirmation Modal
         * Displays a confirmation popup before marking appointment as approved
         */
        function showCompleteConfirmation(appointmentId) {
            const appointment = allAppointments.find(apt => apt.id == appointmentId);
            const patientName = appointment ? appointment.patient_name : 'this appointment';
            
            const modal = document.getElementById('complete-confirmation-modal');
            modal.dataset.appointmentId = appointmentId;
            
            document.getElementById('complete-confirmation-text').textContent = `Are you sure you want to mark appointment #${appointmentId} for ${patientName} as approved?`;
            
            modal.style.display = 'block';
        }

        /**
         * Close Complete Confirmation Modal
         * Hides the completion confirmation modal
         */
        function closeCompleteModal() {
            document.getElementById('complete-confirmation-modal').style.display = 'none';
        }

        /**
         * Confirm Complete Appointment
         * Called when user clicks Yes in the confirmation modal
         */
        function confirmCompleteAppointment() {
            const modal = document.getElementById('complete-confirmation-modal');
            const appointmentId = modal.dataset.appointmentId;
            
            if (appointmentId) {
                completeAppointment(parseInt(appointmentId));
            }
        }

        /**
         * Complete Appointment
         * Marks the selected appointment as approved after confirmation
         */
        async function completeAppointment(appointmentId) {
            closeCompleteModal();

            try {
                const response = await fetch('api/appointments.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: appointmentId,
                        status: 'completed'
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    alert('Appointment approved successfully!');
                    await loadAllAppointments();
                } else {
                    alert('Failed to approve appointment: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error approving appointment:', error);
                alert('Error approving appointment. Please try again.');
            }
        }

        // Close modal when clicking outside
        window.addEventListener('click', (e) => {
            const viewModal = document.getElementById('view-modal');
            const cancelModal = document.getElementById('cancel-confirmation-modal');
            const completeModal = document.getElementById('complete-confirmation-modal');
            if (e.target === viewModal) {
                closeViewModal();
            }
            if (e.target === cancelModal) {
                closeCancelModal();
            }
            if (e.target === completeModal) {
                closeCompleteModal();
            }
        });

        // Auto-clear search when clicking outside search box or action buttons
        document.addEventListener('click', (e) => {
            // Only clear if there's an active search
            if (!currentSearchTerm) return;
            
            // Check if click is on search input or search button
            const searchInput = document.getElementById('search-input');
            const searchBtn = document.querySelector('.search-btn');
            const searchContainer = document.querySelector('.search-container');
            const isSearchElement = (searchInput && (searchInput === e.target || searchInput.contains(e.target))) ||
                                   (searchBtn && (searchBtn === e.target || searchBtn.contains(e.target))) ||
                                   (searchContainer && searchContainer.contains(e.target));
            
            // Check if click is on action buttons (View, Complete, Cancel)
            const isActionButton = e.target.classList.contains('btn-action') || 
                                   e.target.closest('.btn-action') !== null;
            
            // Check if click is on filter tabs (filter tabs already clear search in filterAppointments)
            const isFilterTab = e.target.classList.contains('filter-tab') || 
                               e.target.closest('.filter-tab') !== null;
            
            // Check if click is on a modal
            const isModal = e.target.closest('.modal') !== null;
            
            // Clear search if clicking outside search box, search button, action buttons, filter tabs, and modals
            // Filter tabs already handle clearing search, so we don't need to do it here
            if (!isSearchElement && !isActionButton && !isFilterTab && !isModal) {
                clearSearch();
            }
        });

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

        /**
         * Initialize Appointment Module
         * Sets up authentication and loads appointments
         */
        async function initializeAppointments() {
            if (!checkAuthentication()) {
                return;
            }

            const email = sessionStorage.getItem('userEmail');
            if (email && !doctorId) {
                await loadDoctorIdFromEmail(email);
            } else if (doctorId) {
                await loadAllAppointments();
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', initializeAppointments);
    </script>
</body>
</html>

