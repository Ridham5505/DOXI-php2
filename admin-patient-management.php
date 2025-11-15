<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOXI - Patient Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/svg+xml" href="public/assets/doxi-icon.svg?v=1">
    <style>
        .dashboard-container {
            min-height: 100vh;
            background: var(--gray-50);
        }
        
        .dashboard-header {
            background: var(--white);
            border-bottom: 1px solid var(--gray-200);
            padding: var(--spacing-4) 0;
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--spacing-6);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: var(--spacing-4);
        }
        
        .header-left .logo {
            font-size: var(--font-size-2xl);
            font-weight: 800;
            color: var(--primary-blue);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: var(--spacing-3);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-blue-light);
            color: var(--primary-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .user-name {
            font-weight: 600;
        }
        
        .dashboard-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: var(--spacing-6);
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 5fr;
            gap: var(--spacing-6);
        }
        
        .sidebar {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: var(--spacing-6);
            box-shadow: var(--shadow-sm);
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: var(--spacing-2);
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: var(--spacing-3);
            padding: var(--spacing-3) var(--spacing-4);
            border-radius: var(--radius-md);
            color: var(--gray-700);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: var(--primary-blue-light);
            color: var(--primary-blue);
        }
        
        .content-area {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: var(--spacing-6);
            box-shadow: var(--shadow-sm);
        }
        
        .page-header {
            margin-bottom: var(--spacing-6);
        }
        
        .page-title {
            font-size: var(--font-size-2xl);
            font-weight: 700;
            color: var(--gray-900);
            margin: 0 0 var(--spacing-2) 0;
        }
        
        .page-description {
            color: var(--gray-600);
            margin: 0;
        }
        
        .search-sort-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--spacing-6);
        }
        
        .search-input {
            padding: var(--spacing-2) var(--spacing-4);
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-md);
            width: 250px;
        }
        
        .sort-select {
            padding: var(--spacing-2) var(--spacing-4);
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-md);
        }
        
        .btn {
            padding: var(--spacing-2) var(--spacing-4);
            border-radius: var(--radius-md);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-primary {
            background: var(--primary-blue);
            color: var(--white);
            border: none;
        }
        
        .btn-primary:hover {
            background: var(--primary-blue-dark);
        }
        
        .btn-secondary {
            background: var(--gray-200);
            color: var(--gray-700);
            border: none;
        }
        
        .btn-secondary:hover {
            background: var(--gray-300);
        }
        
        .btn-danger {
            background: var(--red-500);
            color: var(--white);
            border: none;
        }
        
        .btn-danger:hover {
            background: var(--red-600);
        }
        
        .patient-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .patient-table th {
            text-align: left;
            padding: var(--spacing-3) var(--spacing-4);
            border-bottom: 2px solid var(--gray-200);
            font-weight: 600;
            color: var(--gray-700);
        }
        
        .patient-table td {
            padding: var(--spacing-3) var(--spacing-4);
            border-bottom: 1px solid var(--gray-200);
        }
        
        .patient-table tr:hover {
            background: var(--gray-50);
        }
        
        .action-buttons {
            display: flex;
            gap: var(--spacing-2);
        }
        
        .action-btn {
            padding: var(--spacing-1) var(--spacing-2);
            border-radius: var(--radius-sm);
            font-size: var(--font-size-sm);
            cursor: pointer;
            border: none;
        }
        
        .view-btn {
            background: var(--blue-50);
            color: var(--blue-600);
        }
        
        .view-btn:hover {
            background: var(--blue-100);
        }
        
        .edit-btn {
            background: var(--green-50);
            color: var(--green-600);
        }
        
        .edit-btn:hover {
            background: var(--green-100);
        }
        
        .delete-btn {
            background: var(--red-50);
            color: var(--red-600);
        }
        
        .delete-btn:hover {
            background: var(--red-100);
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-content {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: var(--spacing-6);
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--spacing-6);
        }
        
        .modal-title {
            font-size: var(--font-size-xl);
            font-weight: 700;
            margin: 0;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: var(--font-size-xl);
            cursor: pointer;
        }
        
        .form-group {
            margin-bottom: var(--spacing-4);
        }
        
        .form-group label {
            display: block;
            margin-bottom: var(--spacing-2);
            font-weight: 500;
        }
        
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: var(--spacing-3);
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-md);
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: var(--spacing-4);
            margin-top: var(--spacing-6);
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: var(--spacing-6);
            gap: var(--spacing-2);
        }
        
        .pagination-btn {
            padding: var(--spacing-2) var(--spacing-4);
            border: 1px solid var(--gray-300);
            border-radius: var(--radius-md);
            background: var(--white);
            cursor: pointer;
        }
        
        .pagination-btn.active {
            background: var(--primary-blue);
            color: var(--white);
            border-color: var(--primary-blue);
        }
        
        .pagination-btn:hover:not(.active) {
            background: var(--gray-100);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="header-content">
                <div class="header-left">
                    <div class="logo" style="display:flex;align-items:center;gap:12px;">
                        <div style="width:40px;height:40px;border-radius:14px;background:linear-gradient(135deg,#3b82f6 0%,#1d4ed8 100%);box-shadow:0 6px 15px rgba(37,99,235,.25);display:flex;align-items:center;justify-content:center;">
                            <div style="width:24px;height:24px;border-radius:9px;border:2px solid #fff;display:flex;align-items:center;justify-content:center;">
                                <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 6.5L5.2 8.7L10 3.7" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        <span style="font-size:26px;font-weight:800;color:#2563eb;letter-spacing:0.6px;">DOXI</span>
                    </div>
                    <div class="user-info">
                        <div class="user-avatar">A</div>
                        <div class="user-name">Admin</div>
                    </div>
                </div>
                <button class="btn btn-secondary" onclick="logout()">Logout</button>
            </div>
        </header>
        
        <main class="dashboard-main">
            <div class="dashboard-grid">
                <aside class="sidebar">
                    <ul class="sidebar-menu">
                        <li><a href="admin-dashboard.php">Dashboard</a></li>
                        <li><a href="admin-patient-management.php" class="active">Patient Management</a></li>
                        <li><a href="#">Doctor Management</a></li>
                        <li><a href="#">Appointment Management</a></li>
                        <li><a href="#">System Settings</a></li>
                    </ul>
                </aside>
                
                <div class="content-area">
                    <div class="page-header">
                        <h1 class="page-title">Patient Management</h1>
                        <p class="page-description">Manage patient records</p>
                    </div>
                    
                    <div class="search-sort-container">
                        <input type="text" id="searchPatients" class="search-input" placeholder="Search patients" onkeyup="searchPatients()">
                        <div style="display: flex; gap: var(--spacing-4);">
                            <select id="sortPatients" class="sort-select" onchange="sortPatients()">
                                <option value="name">Sort by Name</option>
                                <option value="email">Sort by Email</option>
                                <option value="phone">Sort by Phone</option>
                                <option value="last_login">Sort by Last Login</option>
                            </select>
                            <button class="btn btn-primary" onclick="openAddPatientModal()">+ Add Patient</button>
                        </div>
                    </div>
                    
                    <table class="patient-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Last Login</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="patientTableBody">
                            <!-- Patient data will be loaded here -->
                        </tbody>
                    </table>
                    
                    <div class="pagination" id="pagination">
                        <!-- Pagination will be generated here -->
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Add/Edit Patient Modal -->
    <div id="patientModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modalTitle">Add New Patient</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <form id="patientForm">
                <input type="hidden" id="patientId" name="id">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="last_name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone">
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" name="date_of_birth">
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender">
                        <option value="">Select gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address"></textarea>
                </div>
                <div class="form-group">
                    <label for="medicalHistory">Medical History</label>
                    <textarea id="medicalHistory" name="medical_history"></textarea>
                </div>
                <div class="form-group">
                    <label for="allergies">Allergies</label>
                    <textarea id="allergies" name="allergies"></textarea>
                </div>
                <div class="form-group">
                    <label for="medications">Current Medications</label>
                    <textarea id="medications" name="medications"></textarea>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                    <small id="passwordHelp" style="color: var(--gray-500);">Leave blank to keep current password when editing.</small>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- View Patient Modal -->
    <div id="viewPatientModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Patient Details</h3>
                <button class="modal-close" onclick="closeViewModal()">&times;</button>
            </div>
            <div id="patientDetails">
                <!-- Patient details will be loaded here -->
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeViewModal()">Close</button>
                <button type="button" class="btn btn-primary" onclick="editPatient(currentViewPatientId)">Edit</button>
            </div>
        </div>
    </div>
    
    <script>
        // Global variables
        let patients = [];
        let currentPage = 1;
        let patientsPerPage = 10;
        let currentViewPatientId = null;
        let currentEditPatientId = null;
        
        // Check if admin is logged in
        document.addEventListener('DOMContentLoaded', function() {
            if (sessionStorage.getItem('isLoggedIn') !== 'true' || sessionStorage.getItem('userRole') !== 'admin') {
                window.location.href = 'admin-login.php';
                return;
            }
            
            // Load patients
            loadPatients();
        });
        
        // Load patients from API
        async function loadPatients() {
            try {
                const response = await fetch('api/patients.php');
                const result = await response.json();
                if (!result || result.success !== true) {
                    const msg = (result && result.message) ? result.message : 'Unknown error';
                    document.getElementById('patientTableBody').innerHTML = `<tr><td colspan="6">Error loading patients: ${msg}</td></tr>`;
                    return;
                }
                patients = Array.isArray(result.data) ? result.data : [];
                displayPatients();
                setupPagination();
            } catch (error) {
                console.error('Error loading patients:', error);
                document.getElementById('patientTableBody').innerHTML = `<tr><td colspan="6">Error loading patients</td></tr>`;
            }
        }
        
        // Display patients in table
        function displayPatients() {
            const tableBody = document.getElementById('patientTableBody');
            tableBody.innerHTML = '';
            const list = Array.isArray(patients) ? patients : [];
            if (list.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="6">No patients found</td></tr>`;
                return;
            }
            const startIndex = (currentPage - 1) * patientsPerPage;
            const endIndex = Math.min(startIndex + patientsPerPage, list.length);
            const paginatedPatients = list.slice(startIndex, endIndex);
            paginatedPatients.forEach(p => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${(p.first_name||'')} ${(p.last_name||'')}</td>
                    <td>${p.email||''}</td>
                    <td>${p.phone||'N/A'}</td>
                    <td>${p.last_login || '-'}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn view-btn" onclick="viewPatient(${p.id})">View</button>
                            <button class="action-btn edit-btn" onclick="editPatient(${p.id})">Edit</button>
                            <button class="action-btn delete-btn" onclick="confirmDeletePatient(${p.id})">Delete</button>
                        </div>
                    </td>`;
                tableBody.appendChild(row);
            });
        }
        
        // Setup pagination
        function setupPagination() {
            const paginationContainer = document.getElementById('pagination');
            paginationContainer.innerHTML = '';
            
            const totalPages = Math.ceil(patients.length / patientsPerPage);
            
            if (totalPages <= 1) {
                return;
            }
            
            // Previous button
            const prevButton = document.createElement('button');
            prevButton.className = 'pagination-btn';
            prevButton.textContent = '←';
            prevButton.disabled = currentPage === 1;
            prevButton.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    displayPatients();
                    setupPagination();
                }
            });
            paginationContainer.appendChild(prevButton);
            
            // Page buttons
            for (let i = 1; i <= totalPages; i++) {
                const pageButton = document.createElement('button');
                pageButton.className = `pagination-btn ${i === currentPage ? 'active' : ''}`;
                pageButton.textContent = i;
                pageButton.addEventListener('click', () => {
                    currentPage = i;
                    displayPatients();
                    setupPagination();
                });
                paginationContainer.appendChild(pageButton);
            }
            
            // Next button
            const nextButton = document.createElement('button');
            nextButton.className = 'pagination-btn';
            nextButton.textContent = '→';
            nextButton.disabled = currentPage === totalPages;
            nextButton.addEventListener('click', () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    displayPatients();
                    setupPagination();
                }
            });
            paginationContainer.appendChild(nextButton);
        }
        
        // Search patients
        function searchPatients() {
            const searchTerm = document.getElementById('searchPatients').value.toLowerCase();
            
            if (searchTerm === '') {
                loadPatients();
                return;
            }
            
            const filteredPatients = patients.filter(patient => {
                const fullName = `${patient.first_name} ${patient.last_name}`.toLowerCase();
                return fullName.includes(searchTerm) || 
                       patient.email.toLowerCase().includes(searchTerm) ||
                       (patient.phone && patient.phone.toLowerCase().includes(searchTerm));
            });
            
            patients = filteredPatients;
            currentPage = 1;
            displayPatients();
            setupPagination();
        }
        
        // Sort patients
        function sortPatients() {
            const sortBy = document.getElementById('sortPatients').value;
            
            patients.sort((a, b) => {
                switch (sortBy) {
                    case 'name': {
                        const nameA = `${a.first_name || ''} ${a.last_name || ''}`.trim().toLowerCase();
                        const nameB = `${b.first_name || ''} ${b.last_name || ''}`.trim().toLowerCase();
                        return nameA.localeCompare(nameB);
                    }
                    case 'email': {
                        const emailA = (a.email || '').toLowerCase();
                        const emailB = (b.email || '').toLowerCase();
                        return emailA.localeCompare(emailB);
                    }
                    case 'phone': {
                        const phoneA = (a.phone || '').toLowerCase();
                        const phoneB = (b.phone || '').toLowerCase();
                        if (!phoneA && phoneB) return 1;
                        if (phoneA && !phoneB) return -1;
                        return phoneA.localeCompare(phoneB);
                    }
                    case 'last_login': {
                        const lastLoginA = a.last_login ? new Date(a.last_login).getTime() : 0;
                        const lastLoginB = b.last_login ? new Date(b.last_login).getTime() : 0;
                        return lastLoginB - lastLoginA;
                    }
                    default:
                        return 0;
                }
            });
            
            currentPage = 1;
            displayPatients();
            setupPagination();
        }
        
        // Open add patient modal
        function openAddPatientModal() {
            document.getElementById('modalTitle').textContent = 'Add New Patient';
            document.getElementById('patientForm').reset();
            document.getElementById('patientId').value = '';
            document.getElementById('passwordHelp').style.display = 'none';
            currentEditPatientId = null;
            openModal();
        }
        
        // Open modal
        function openModal() {
            document.getElementById('patientModal').classList.add('active');
        }
        
        // Close modal
        function closeModal() {
            document.getElementById('patientModal').classList.remove('active');
        }
        
        // View patient details
        async function viewPatient(patientId) {
            currentViewPatientId = patientId;
            const detailsContainer = document.getElementById('patientDetails');
            detailsContainer.innerHTML = '<div>Loading...</div>';
            try {
                const res = await fetch(`api/patients.php?id=${patientId}`);
                const json = await res.json();
                if (!json.success) throw new Error(json.message||'Failed to load');
                const p = json.data;
                detailsContainer.innerHTML = `
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-4); margin-bottom: var(--spacing-6);">
                    <div>
                        <h4 style="margin: 0 0 var(--spacing-2) 0;">Personal Information</h4>
                        <p><strong>Name:</strong> ${(p.first_name||'')} ${(p.last_name||'')}</p>
                        <p><strong>Email:</strong> ${p.email||''}</p>
                        <p><strong>Phone:</strong> ${p.phone || 'N/A'}</p>
                        <p><strong>Date of Birth:</strong> ${formatDate(p.date_of_birth) || 'N/A'}</p>
                        <p><strong>Gender:</strong> ${p.gender || 'N/A'}</p>
                        <p><strong>Last Login:</strong> ${p.last_login || '-'}</p>
                        <p><strong>Address:</strong> ${p.address || 'N/A'}</p>
                    </div>
                </div>`;
                document.getElementById('viewPatientModal').classList.add('active');
            } catch (e) {
                detailsContainer.innerHTML = '<div style="color:#ef4444">Failed to load patient details</div>';
            }
        }
        
        // Close view modal
        function closeViewModal() {
            document.getElementById('viewPatientModal').classList.remove('active');
            currentViewPatientId = null;
        }
        
        // Edit patient
        async function editPatient(patientId) {
            // Close view modal if open
            closeViewModal();
            document.getElementById('modalTitle').textContent = 'Edit Patient';
            try {
                const res = await fetch(`api/patients.php?id=${patientId}`);
                const json = await res.json();
                if (!json.success) throw new Error(json.message||'Failed');
                const p = json.data;
                document.getElementById('patientId').value = p.id;
                document.getElementById('firstName').value = p.first_name||'';
                document.getElementById('lastName').value = p.last_name||'';
                document.getElementById('email').value = p.email||'';
                document.getElementById('phone').value = p.phone||'';
                document.getElementById('dob').value = p.date_of_birth||'';
                document.getElementById('gender').value = p.gender||'';
                document.getElementById('address').value = p.address||'';
                document.getElementById('medicalHistory').value = p.medical_history||'';
                document.getElementById('allergies').value = p.allergies||'';
                document.getElementById('medications').value = p.medications||'';
                document.getElementById('password').value = '';
                document.getElementById('passwordHelp').style.display = 'block';
                currentEditPatientId = patientId;
                openModal();
            } catch (e) {
                alert('Failed to load patient');
            }
        }
        
        // Confirm delete patient
        function confirmDeletePatient(patientId) {
            if (confirm('Are you sure you want to delete this patient? This action cannot be undone.')) {
                deletePatient(patientId);
            }
        }
        
        // Delete patient
        async function deletePatient(patientId) {
            try {
                const response = await fetch(`api/patients.php?id=${patientId}`, {
                    method: 'DELETE'
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Patient deleted successfully');
                    loadPatients();
                } else {
                    alert('Failed to delete patient: ' + result.message);
                }
            } catch (error) {
                console.error('Error deleting patient:', error);
                alert('An error occurred while deleting the patient');
            }
        }
        
        // Save patient (add or edit)
        async function savePatient(event) {
            event.preventDefault();
            
            const formData = new FormData(document.getElementById('patientForm'));
            const data = {};
            formData.forEach((value, key) => {
                if (value.trim() !== '' || key === 'id') {
                    data[key] = value;
                }
            });
            
            const isEdit = data.id !== '';
            const method = isEdit ? 'PUT' : 'POST';
            const url = isEdit ? `api/patients.php?id=${data.id}` : 'api/patients.php';
            
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert(isEdit ? 'Patient updated successfully' : 'Patient added successfully');
                    closeModal();
                    loadPatients();
                } else {
                    alert('Failed to save patient: ' + result.message);
                }
            } catch (error) {
                console.error('Error saving patient:', error);
                alert('An error occurred while saving the patient');
            }
        }
        
        // Helper function to format date
        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
        }
        
        // Logout function
        function logout() {
            sessionStorage.clear();
            window.location.href = 'admin-login.php';
        }
        
        // Event listeners
        document.getElementById('patientForm').addEventListener('submit', savePatient);
</script>
<script src="public/js/email-phone-validation.js"></script>
</body>
</html>