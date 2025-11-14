<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Doctor Reviews & Ratings</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/svg+xml" href="public/assets/doxi-icon.svg?v=1">
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
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 10px 10px -5px rgba(0, 0, 0, 0.3);
        }
        html, body { margin:0; background: var(--gray-50); color: var(--gray-900); transition: background 0.3s, color 0.3s; }
        body { background: var(--gray-50); }
        .page { max-width: 1100px; margin: 40px auto; padding: 0 var(--spacing-6); }
        .header { display:flex; align-items:center; justify-content: space-between; margin-bottom: var(--spacing-6); }
        .logo {
             font-size: var(--font-size-2xl);
             font-weight: 800;
             color: var(--primary-blue);
         }
        .logo img{display:block;height:36px;width:auto;}
        .tagline { color: var(--gray-600); font-size: var(--font-size-sm); font-weight: 500; }
        .theme-toggle { padding: 8px 14px; border-radius: 8px; border: 1px solid var(--gray-200); background: var(--white); color: var(--gray-700); cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .theme-toggle:hover { background: var(--gray-100); }
        .back { text-decoration:none; color: var(--gray-600); transition: color 0.3s; }
        .back:hover { color: var(--gray-900); }
        .card { background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius-xl); box-shadow: var(--shadow-md); padding: var(--spacing-6); margin-bottom: var(--spacing-6); transition: background 0.3s, border-color 0.3s; }

        /* Filter Tabs */
        .filter-tabs-container {
            /* Card styles are inherited from .card class */
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
            padding: var(--spacing-2) var(--spacing-4);
            border-radius: var(--radius-md);
            border: 2px solid var(--gray-200);
            background: var(--white);
            color: var(--gray-700);
            font-weight: 600;
            font-size: var(--font-size-sm);
            cursor: pointer;
            transition: all 0.3s;
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
            border-radius: 9999px;
            background: rgba(0, 0, 0, 0.1);
            font-size: var(--font-size-xs);
            font-weight: 700;
        }

        .filter-tab.active .filter-tab-count {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Search Box */
        .search-container {
            display: flex;
            gap: var(--spacing-2);
            align-items: center;
            min-width: 300px;
        }

        .search-input {
            flex: 1;
            padding: var(--spacing-3) var(--spacing-4);
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-md);
            font-size: var(--font-size-sm);
            font-family: var(--font-family);
            background: var(--white);
            color: var(--gray-900);
            transition: border-color 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-blue);
        }

        .search-btn {
            padding: var(--spacing-3) var(--spacing-4);
            background: var(--primary-blue);
            color: var(--white);
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-size: var(--font-size-sm);
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .search-btn:hover {
            background: var(--primary-blue-dark);
        }

        /* Reviews Table */
        .reviews-container {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-md);
            padding: var(--spacing-6);
        }

        .reviews-table {
            width: 100%;
            border-collapse: collapse;
        }

        .reviews-table thead {
            background: var(--gray-50);
        }

        .reviews-table th {
            padding: 12px 14px;
            text-align: left;
            font-weight: 600;
            font-size: var(--font-size-sm);
            color: var(--gray-600);
            border-bottom: 1px solid var(--gray-200);
        }

        .reviews-table td {
            padding: 12px 14px;
            border-bottom: 1px solid var(--gray-200);
            font-size: var(--font-size-sm);
            color: var(--gray-900);
        }

        .reviews-table tbody tr:hover {
            background: var(--gray-50);
        }

        .reviews-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Rating Display */
        .rating-display {
            display: flex;
            align-items: center;
            gap: var(--spacing-2);
        }

        .rating-stars {
            display: flex;
            gap: var(--spacing-1);
            align-items: center;
        }

        .rating-star {
            font-size: var(--font-size-lg);
            color: var(--gray-300);
            line-height: 1;
            transition: color 0.3s;
        }

        .rating-star.active {
            color: #fbbf24;
        }

        .rating-star.empty {
            color: var(--gray-300);
        }

        .rating-number {
            font-weight: 600;
            font-size: var(--font-size-sm);
            color: var(--gray-700);
            margin-left: var(--spacing-2);
        }

        /* Category Badge */
        .category-badge {
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: var(--font-size-xs);
            font-weight: 700;
            display: inline-block;
            text-transform: lowercase;
        }

        .category-excellent {
            background: #D1FAE5;
            color: #065F46;
        }

        .category-good {
            background: #FEF3C7;
            color: #92400E;
        }

        .category-bad {
            background: #FEE2E2;
            color: #991B1B;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: var(--spacing-2) var(--spacing-3);
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            font-size: var(--font-size-xs);
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-view {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .btn-view:hover {
            background: #BFDBFE;
        }

        .btn-delete {
            background: #FEE2E2;
            color: #991B1B;
        }

        .btn-delete:hover {
            background: #FECACA;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: var(--spacing-6);
            color: var(--gray-500);
        }

        .empty-state-icon {
            font-size: var(--font-size-3xl);
            margin-bottom: var(--spacing-4);
            opacity: 0.5;
        }

        .empty-state-text {
            font-size: var(--font-size-base);
            font-weight: 500;
        }

        /* Loading State */
        .loading-state {
            text-align: center;
            padding: var(--spacing-6);
            color: var(--gray-500);
        }

        .loading-spinner {
            display: inline-block;
            width: 2rem;
            height: 2rem;
            border: 3px solid var(--gray-200);
            border-top-color: var(--primary-blue);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Modal Styles */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: var(--white); margin: 5% auto; padding: var(--spacing-6); border-radius: var(--radius-xl); box-shadow: var(--shadow-xl); width: 90%; max-width: 600px; position: relative; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-4); border-bottom: 1px solid var(--gray-200); padding-bottom: var(--spacing-4); }
        .modal-title { font-size: var(--font-size-xl); font-weight: 700; color: var(--gray-900); }
        .close-modal { color: var(--gray-500); font-size: 28px; font-weight: bold; cursor: pointer; background: none; border: none; padding: 0; line-height: 1; }
        .close-modal:hover { color: var(--gray-900); }
        .modal-body { color: var(--gray-700); }
        .modal-field { margin-bottom: var(--spacing-4); }
        .modal-field-label { font-weight: 600; color: var(--gray-700); margin-bottom: var(--spacing-2); display: block; }
        .modal-field-value { color: var(--gray-900); padding: var(--spacing-3); background: var(--gray-50); border-radius: var(--radius-md); border: 1px solid var(--gray-200); white-space: pre-wrap; word-wrap: break-word; }
        .modal-footer { display: flex; justify-content: flex-end; gap: var(--spacing-3); padding-top: var(--spacing-4); border-top: 1px solid var(--gray-200); }
        .btn-modal { padding: var(--spacing-3) var(--spacing-6); border-radius: var(--radius-md); border: none; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn-modal-secondary { background: var(--gray-100); color: var(--gray-700); }
        .btn-modal-secondary:hover { background: var(--gray-200); }
        .btn-modal-danger { background: #EF4444; color: var(--white); }
        .btn-modal-danger:hover { background: #DC2626; }
        
        /* Responsive Design */
        @media (max-width: 900px) {
            .filter-tabs-header { flex-direction: column; align-items: stretch; }
            .filter-tabs { width: 100%; overflow-x: auto; flex-wrap: nowrap; }
            .search-container { width: 100%; min-width: auto; }
            .reviews-table { font-size: var(--font-size-xs); }
            .reviews-table th, .reviews-table td { padding: var(--spacing-2) var(--spacing-3); }
            .action-buttons { flex-direction: column; gap: 4px; }
        }
        @media (max-width: 600px) {
            .reviews-table { display: block; overflow-x: auto; }
            .filter-tabs { gap: var(--spacing-2); }
            .filter-tab { padding: var(--spacing-2) var(--spacing-3); font-size: var(--font-size-xs); }
        }
    </style>
    <script>
        // Apply theme early before styles load
        (function(){
            try{
                const savedTheme = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-theme', savedTheme);
            }catch(_e){}
        })();
    </script>
</head>
<body>
    <div class="page">
        <div class="header">
            <div>
                <div class="logo"><img src="public/assets/doxi-logo.svg?v=4" alt="DOXI" style="height:52px;width:auto;"></div>
                <div class="tagline">Review & Rating</div>
            </div>
            <div style="display:flex; align-items:center; gap: var(--spacing-4);">
                <button class="theme-toggle" id="theme-toggle" onclick="toggleTheme()">
                    <span id="theme-icon">🌙</span>
                    <span id="theme-text">Dark</span>
                </button>
                <a class="back" href="doctor-dashboard.php">← Back to Dashboard</a>
            </div>
        </div>

        <!-- Filter Tabs and Search -->
        <div class="card filter-tabs-container" id="filter-search-container">
            <div class="filter-tabs-header">
                <div class="filter-tabs" id="filter-tabs-container">
                    <button class="filter-tab active" data-filter="all" onclick="filterReviews('all')">
                        All Ratings
                        <span class="filter-tab-count" id="count-all">0</span>
                    </button>
                    <button class="filter-tab" data-filter="excellent" onclick="filterReviews('excellent')">
                        Excellent Rating
                        <span class="filter-tab-count" id="count-excellent">0</span>
                    </button>
                    <button class="filter-tab" data-filter="good" onclick="filterReviews('good')">
                        Good Rating
                        <span class="filter-tab-count" id="count-good">0</span>
                    </button>
                    <button class="filter-tab" data-filter="bad" onclick="filterReviews('bad')">
                        Bad Rating
                        <span class="filter-tab-count" id="count-bad">0</span>
                    </button>
                </div>
                <div class="search-container" id="search-container">
                    <input 
                        type="text" 
                        class="search-input" 
                        id="search-input" 
                        placeholder="Search by patient name, rating (1-5), category, or date (DD-MM-YYYY)..."
                        onkeypress="handleSearchKeyPress(event)"
                    >
                    <button class="search-btn" onclick="performSearch()">🔍 Search</button>
                </div>
            </div>
        </div>

        <!-- Reviews Table -->
        <div class="card reviews-container">
            <div id="reviews-content">
                <div class="loading-state">
                    <div class="loading-spinner"></div>
                    <p style="margin-top: var(--spacing-4);">Loading reviews...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- View Review Modal -->
    <div id="view-review-modal" class="modal" onclick="if(event.target === this) closeViewModal()">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Review Details</h2>
                <button class="close-modal" onclick="closeViewModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="modal-field">
                    <label class="modal-field-label">Patient Name</label>
                    <div class="modal-field-value" id="view-patient-name">-</div>
                </div>
                <div class="modal-field">
                    <label class="modal-field-label">Rating</label>
                    <div class="modal-field-value">
                        <div class="rating-display" id="view-rating-display">-</div>
                    </div>
                </div>
                <div class="modal-field">
                    <label class="modal-field-label">Category</label>
                    <div class="modal-field-value">
                        <span class="category-badge" id="view-category-badge">-</span>
                    </div>
                </div>
                <div class="modal-field">
                    <label class="modal-field-label">Review Text</label>
                    <div class="modal-field-value" id="view-review-text">-</div>
                </div>
                <div class="modal-field">
                    <label class="modal-field-label">Date</label>
                    <div class="modal-field-value" id="view-review-date">-</div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal btn-modal-secondary" onclick="closeViewModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-confirmation-modal" class="modal" onclick="if(event.target === this) closeDeleteModal()">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Delete Review</h2>
                <button class="close-modal" onclick="closeDeleteModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p id="delete-confirmation-text" style="font-size: var(--font-size-base); color: var(--gray-700); margin: 0;">
                    Are you sure you want to delete this review?
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn-modal btn-modal-secondary" onclick="closeDeleteModal()">No</button>
                <button class="btn-modal btn-modal-danger" onclick="confirmDeleteReview()">Yes</button>
            </div>
        </div>
    </div>

    <script>
        // ============================================
        // DOCTOR REVIEWS & RATINGS MODULE JAVASCRIPT
        // Filtering, searching, viewing, and deleting reviews
        // ============================================

        // Global variables
        let doctorId = null;
        let allReviews = [];
        let currentFilter = 'all';
        let currentSearchTerm = '';

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
                    
                    loadAllReviews();
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
         * Only checks and redirects if NOT on the settings page itself
         * Also checks sessionStorage flag for already completed profiles
         */
        async function checkProfileCompletion() {
            // Skip check if already on settings page (where you complete profile)
            const currentPage = window.location.pathname.split('/').pop();
            if (currentPage === 'doctor-settings.php') {
                return true; // Always allow access to settings page
            }

            // Check both sessionStorage and localStorage for profile completion flag
            const profileCompleteFlag = sessionStorage.getItem('profileComplete') || localStorage.getItem('doctorProfileCompleted');
            if (profileCompleteFlag === 'true') {
                return true; // Profile already verified as complete
            }

            if (!doctorId) return true;
            
            try {
                // Fetch fresh data from API (don't use cached data)
                const response = await fetch(`api/users.php?id=${doctorId}`);
                const result = await response.json();
                
                if (result.success && result.data) {
                    const doctor = result.data;
                    
                    // Required fields - must match dashboard check
                    const requiredFields = {
                        'first_name': doctor.first_name,
                        'last_name': doctor.last_name,
                        'email': doctor.email,
                        'gender': doctor.gender,
                        'specialty': doctor.specialty,
                        'license_number': doctor.license_number,
                        'years_experience': doctor.years_experience
                    };

                    // Check if all required fields are filled
                    const incompleteFields = Object.keys(requiredFields).filter(key => {
                        const value = requiredFields[key];
                        if (!value) return true;
                        if (typeof value === 'string' && value.trim() === '') return true;
                        if (key === 'years_experience' && (value === null || value === '' || value === undefined || value === 0)) return true;
                        return false;
                    });

                    if (incompleteFields.length > 0) {
                        // Profile incomplete - redirect to settings
                        if (currentPage !== 'doctor-settings.php') {
                            alert('Please complete your profile before accessing other modules.');
                            window.location.href = 'doctor-settings.php';
                            return false;
                        }
                    } else {
                        // Profile is complete - set flags to skip future checks
                        sessionStorage.setItem('profileComplete', 'true');
                        localStorage.setItem('doctorProfileCompleted', 'true'); // Persistent flag
                    }
                }
                
                return true;
            } catch (error) {
                console.error('Error checking profile completion:', error);
                return true; // Allow access on error
            }
        }

        /**
         * Logout Function
         * Clears all session data and redirects to login page
         */
        function logout() {
            // Clear session storage
            sessionStorage.clear();
            
            // Clear profile completion flags from localStorage
            localStorage.removeItem('doctorProfileCompleted');
            localStorage.removeItem('doctorProfileData');
            
            // Redirect to login page
            window.location.href = 'login.php';
        }

        /**
         * Load All Reviews
         * Fetches all reviews for the doctor from API
         */
        async function loadAllReviews() {
            if (!doctorId) {
                document.getElementById('reviews-content').innerHTML = 
                    '<div class="empty-state"><div class="empty-state-icon">⭐</div><div class="empty-state-text">Doctor ID not available</div></div>';
                return;
            }

            try {
                const response = await fetch(`api/reviews.php?doctor_id=${doctorId}&limit=100`);
                const result = await response.json();
                
                if (result.success && result.data) {
                    allReviews = result.data;
                    updateFilterCounts();
                    displayReviews();
                } else {
                    allReviews = [];
                    updateFilterCounts();
                    displayReviews();
                }
            } catch (error) {
                console.error('Error loading reviews:', error);
                document.getElementById('reviews-content').innerHTML = 
                    '<div class="empty-state"><div class="empty-state-icon">⚠️</div><div class="empty-state-text">Error loading reviews. Please try again later.</div></div>';
            }
        }

        /**
         * Update Filter Counts
         * Calculates and updates the count badges for each filter tab
         */
        function updateFilterCounts() {
            let excellentCount = 0;
            let goodCount = 0;
            let badCount = 0;

            allReviews.forEach(review => {
                const rating = parseInt(review.rating) || 0;
                
                if (rating === 5) {
                    excellentCount++;
                } else if (rating >= 3 && rating <= 4) {
                    goodCount++;
                } else if (rating >= 1 && rating <= 2) {
                    badCount++;
                }
            });

            document.getElementById('count-all').textContent = allReviews.length;
            document.getElementById('count-excellent').textContent = excellentCount;
            document.getElementById('count-good').textContent = goodCount;
            document.getElementById('count-bad').textContent = badCount;
        }

        /**
         * Filter Reviews
         * Filters reviews based on selected category
         * Note: 
         * - Preserves search term when switching filters for combined filtering
         * - Filter will auto-reset when clicking outside filter/search containers
         * - Results update dynamically when filter is applied
         */
        function filterReviews(filter) {
            currentFilter = filter;
            // Don't clear search when filtering - allow combined filter + search functionality
            // Filter will reset when clicking outside filter/search containers
            
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelector(`.filter-tab[data-filter="${filter}"]`).classList.add('active');
            
            displayReviews();
        }

        /**
         * Perform Search
         * Searches reviews by patient name, rating, category, or date
         * Note: 
         * - Search works with active filters - doesn't reset filter tabs
         * - Search will auto-reset when clicking outside filter/search containers
         * - Results update dynamically when search is performed
         */
        function performSearch() {
            const searchTerm = document.getElementById('search-input').value.trim();
            if (!searchTerm) {
                clearSearch();
                return;
            }
            currentSearchTerm = searchTerm; // Store original term
            // Automatically switch to "All Ratings" tab when searching
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            currentFilter = 'all';
            document.querySelector('.filter-tab[data-filter="all"]').classList.add('active');
            displayReviews();
        }
        
        function clearSearch() {
            currentSearchTerm = '';
            document.getElementById('search-input').value = '';
            displayReviews();
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
         * Reset All Filters and Search
         * Resets the component to its default initial state
         * - Sets filter to "All Ratings"
         * - Clears search term and input field
         * - Refreshes display to show all reviews
         * This function is called when clicking outside filter/search containers
         */
        function resetToDefaultState() {
            // Reset filter to "All Ratings"
            currentFilter = 'all';
            
            // Clear search term
            currentSearchTerm = '';
            
            // Clear search input field
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.value = '';
            }
            
            // Reset filter tabs - activate "All Ratings" tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            const allTab = document.querySelector('.filter-tab[data-filter="all"]');
            if (allTab) {
                allTab.classList.add('active');
            }
            
            // Refresh display to show all reviews (default state)
            displayReviews();
        }

        /**
         * Setup Auto-Reset on Outside Click
         * Detects clicks outside filter/search containers and resets to default state
         * This implements the auto-refresh and reset functionality
         */
        function setupAutoResetOnOutsideClick() {
            const filterSearchContainer = document.getElementById('filter-search-container');
            
            if (!filterSearchContainer) {
                return;
            }

            // Document click listener - reset when clicking outside filter/search containers
            document.addEventListener('click', function(e) {
                // Check if click is outside the filter/search container
                // Also exclude clicks inside modals (view/delete modals)
                const isClickInsideFilterSearch = filterSearchContainer.contains(e.target);
                const isClickInsideModal = e.target.closest('.modal') !== null;
                
                // If click is outside filter/search container and not inside a modal, reset
                if (!isClickInsideFilterSearch && !isClickInsideModal) {
                    // Only reset if there's an active filter or search term
                    if (currentFilter !== 'all' || currentSearchTerm !== '') {
                        resetToDefaultState();
                    }
                }
            });
        }

        /**
         * Get Rating Category
         * Returns category name based on rating value
         * Rating 1-2 = Bad, 3-4 = Good, 5 = Excellent
         */
        function getRatingCategory(rating) {
            const ratingNum = parseInt(rating) || 0;
            
            if (ratingNum === 5) {
                return 'Excellent';
            } else if (ratingNum >= 3 && ratingNum <= 4) {
                return 'Good';
            } else if (ratingNum >= 1 && ratingNum <= 2) {
                return 'Bad';
            }
            
            return 'Unknown';
        }

        /**
         * Get Category Class
         * Returns CSS class name for category badge
         */
        function getCategoryClass(rating) {
            const category = getRatingCategory(rating);
            
            if (category === 'Excellent') {
                return 'category-excellent';
            } else if (category === 'Good') {
                return 'category-good';
            } else if (category === 'Bad') {
                return 'category-bad';
            }
            
            return '';
        }

        /**
         * Render Rating Stars
         * Creates HTML for star rating display with dynamic yellow stars
         * Shows filled yellow stars (⭐) for active ratings and empty stars (☆) for inactive
         * Examples:
         * - Rating 1: ⭐☆☆☆☆ (1 yellow star, 4 empty stars)
         * - Rating 2: ⭐⭐☆☆☆ (2 yellow stars, 3 empty stars)
         * - Rating 3: ⭐⭐⭐☆☆ (3 yellow stars, 2 empty stars)
         * - Rating 4: ⭐⭐⭐⭐☆ (4 yellow stars, 1 empty star)
         * - Rating 5: ⭐⭐⭐⭐⭐ (5 yellow stars, no empty stars)
         */
        function renderRatingStars(rating) {
            const ratingNum = parseInt(rating) || 0;
            let starsHTML = '<div class="rating-stars">';
            
            for (let i = 1; i <= 5; i++) {
                if (i <= ratingNum) {
                    // Active/filled star - yellow color
                    starsHTML += `<span class="rating-star active">⭐</span>`;
                } else {
                    // Empty star - gray color
                    starsHTML += `<span class="rating-star empty">☆</span>`;
                }
            }
            
            starsHTML += `</div><span class="rating-number">${ratingNum}/5</span>`;
            return starsHTML;
        }

        /**
         * Format Date
         * Converts date to DD-MM-YYYY format
         */
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            try {
                const date = new Date(dateString);
                const day = date.getDate().toString().padStart(2, '0');
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const year = date.getFullYear();
                return `${day}-${month}-${year}`;
            } catch (error) {
                return dateString;
            }
        }

        /**
         * Display Reviews
         * Renders filtered/searched reviews in the table
         */
        function displayReviews() {
            let filteredReviews = [...allReviews];

            // Apply search first (when searching, ignore filter and search all reviews)
            if (currentSearchTerm) {
                filteredReviews = filteredReviews.filter(review => {
                    // Get search term in lowercase for text matching
                    const searchTermLower = currentSearchTerm.toLowerCase();
                    
                    // Get review data for matching
                    const patientName = (review.patient_name || '').toLowerCase();
                    const reviewRating = parseInt(review.rating) || 0; // Rating as number
                    const category = getRatingCategory(review.rating).toLowerCase();
                    const reviewDate = formatDate(review.created_at).toLowerCase();
                    const reviewText = (review.comment || '').toLowerCase();
                    
                    // 1. Check if search term matches rating number (1-5)
                    // Parse search term as number and compare with review rating
                    const searchAsNumber = parseInt(currentSearchTerm);
                    if (!isNaN(searchAsNumber) && searchAsNumber >= 1 && searchAsNumber <= 5) {
                        if (reviewRating === searchAsNumber) {
                            return true;
                        }
                    }
                    
                    // 2. Check if search term matches patient name (case-insensitive)
                    if (patientName.includes(searchTermLower)) {
                        return true;
                    }
                    
                    // 3. Check if search term matches category (case-insensitive)
                    // Match "excellent", "good", "bad" in any case
                    if (category.includes(searchTermLower)) {
                        return true;
                    }
                    
                    // 4. Check if search term is a date in DD-MM-YYYY format
                    const dateRegex = /^(\d{2})-(\d{2})-(\d{4})$/;
                    if (dateRegex.test(currentSearchTerm)) {
                        // Check if it matches the formatted date (case-insensitive for partial matches)
                        if (reviewDate.includes(currentSearchTerm.toLowerCase())) {
                            return true;
                        }
                    }
                    
                    // 5. Check if search term matches review text (case-insensitive)
                    if (reviewText.includes(searchTermLower)) {
                        return true;
                    }
                    
                    // 6. Additional: Check if search term matches rating as string (e.g., "5" matches "5/5")
                    if (reviewRating.toString() === currentSearchTerm) {
                        return true;
                    }
                    
                    return false;
                });
            } else {
                // Apply filter only when not searching
                if (currentFilter !== 'all') {
                    filteredReviews = filteredReviews.filter(review => {
                        const rating = parseInt(review.rating) || 0;
                        
                        switch (currentFilter) {
                            case 'excellent':
                                return rating === 5;
                            case 'good':
                                return rating >= 3 && rating <= 4;
                            case 'bad':
                                return rating >= 1 && rating <= 2;
                            default:
                                return true;
                        }
                    });
                }
            }

            // Sort by date (most recent first)
            filteredReviews.sort((a, b) => {
                const dateA = new Date(a.created_at || 0);
                const dateB = new Date(b.created_at || 0);
                return dateB - dateA;
            });

            // Render reviews
            const contentDiv = document.getElementById('reviews-content');
            
            if (filteredReviews.length === 0) {
                const emptyMessage = currentSearchTerm 
                    ? `No reviews found matching "${currentSearchTerm}"`
                    : 'No reviews found';
                contentDiv.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">⭐</div>
                        <div class="empty-state-text">${emptyMessage}</div>
                    </div>
                `;
                return;
            }

            // Build table HTML
            let tableHTML = `
                <table class="reviews-table">
                    <thead>
                        <tr>
                            <th>Review ID</th>
                            <th>Patient Name</th>
                            <th>Rating</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            filteredReviews.forEach(review => {
                const reviewId = review.id || 'N/A';
                const patientName = review.patient_name || 'Anonymous Patient';
                const rating = review.rating || 0;
                const category = getRatingCategory(rating);
                const categoryClass = getCategoryClass(rating);
                const reviewDate = formatDate(review.created_at);
                
                tableHTML += `
                    <tr>
                        <td>#${reviewId}</td>
                        <td>${patientName}</td>
                        <td>
                            <div class="rating-display">
                                ${renderRatingStars(rating)}
                            </div>
                        </td>
                        <td><span class="category-badge ${categoryClass}">${category}</span></td>
                        <td>${reviewDate}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-action btn-view" onclick="viewReview(${reviewId})">View</button>
                                <button class="btn-action btn-delete" onclick="showDeleteConfirmation(${reviewId})">Delete</button>
                            </div>
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
         * View Review
         * Opens modal showing full review details (read-only)
         */
        function viewReview(reviewId) {
            // Find review in the current list
            const review = allReviews.find(r => r.id == reviewId);
            
            if (!review) {
                alert('Review not found');
                return;
            }

            // Populate modal with review details
            document.getElementById('view-patient-name').textContent = review.patient_name || 'Anonymous Patient';
            
            // Render rating stars in modal
            const ratingDisplay = document.getElementById('view-rating-display');
            ratingDisplay.innerHTML = renderRatingStars(review.rating);
            
            // Set category badge
            const categoryBadge = document.getElementById('view-category-badge');
            categoryBadge.textContent = getRatingCategory(review.rating);
            categoryBadge.className = `category-badge ${getCategoryClass(review.rating)}`;
            
            // Set review text
            document.getElementById('view-review-text').textContent = review.comment || 'No review text available';
            
            // Set date
            document.getElementById('view-review-date').textContent = formatDate(review.created_at);
            
            // Show the modal
            document.getElementById('view-review-modal').style.display = 'block';
        }

        /**
         * Close View Modal
         * Hides the review details modal
         */
        function closeViewModal() {
            document.getElementById('view-review-modal').style.display = 'none';
        }

        /**
         * Show Delete Confirmation Modal
         * Displays a confirmation popup before deleting review
         */
        function showDeleteConfirmation(reviewId) {
            // Find review to get patient name for confirmation message
            const review = allReviews.find(r => r.id == reviewId);
            const patientName = review ? review.patient_name : 'this review';
            
            // Store review ID in the modal (using data attribute)
            const modal = document.getElementById('delete-confirmation-modal');
            modal.dataset.reviewId = reviewId;
            
            // Update confirmation text
            document.getElementById('delete-confirmation-text').textContent = `Are you sure you want to delete this review from ${patientName}?`;
            
            // Show the confirmation modal
            modal.style.display = 'block';
        }

        /**
         * Close Delete Confirmation Modal
         * Hides the deletion confirmation modal
         */
        function closeDeleteModal() {
            document.getElementById('delete-confirmation-modal').style.display = 'none';
        }

        /**
         * Confirm Delete Review
         * Called when user clicks Yes in the confirmation modal
         */
        function confirmDeleteReview() {
            const modal = document.getElementById('delete-confirmation-modal');
            const reviewId = modal.dataset.reviewId;
            
            if (reviewId) {
                deleteReview(parseInt(reviewId));
            }
        }

        /**
         * Delete Review
         * Deletes the selected review after confirmation
         */
        async function deleteReview(reviewId) {
            // Hide confirmation modal
            closeDeleteModal();

            try {
                const response = await fetch('api/reviews.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: reviewId
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    alert('Review deleted successfully!');
                    // Reload reviews to update the table
                    await loadAllReviews();
                } else {
                    alert('Failed to delete review: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error deleting review:', error);
                alert('Error deleting review. Please try again.');
            }
        }

        /**
         * Initialize Reviews Module
         * Sets up authentication and loads reviews
         * Also sets up auto-reset functionality for outside clicks
         */
        async function initializeReviews() {
            if (!checkAuthentication()) {
                return;
            }

            const email = sessionStorage.getItem('userEmail');
            if (email && !doctorId) {
                await loadDoctorIdFromEmail(email);
            } else if (doctorId) {
                await loadAllReviews();
            }

            // Setup auto-reset on outside click
            // This will reset filters and search when clicking outside filter/search containers
            setupAutoResetOnOutsideClick();
        }

        /**
         * Theme Toggle Functionality
         * Handles dark/light mode switching
         */
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

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize theme
            try{
                const savedTheme = localStorage.getItem('theme') || 'light';
                updateThemeToggle(savedTheme);
            }catch(_e){}
            
            initializeReviews();
        });
    </script>
</body>
</html>


