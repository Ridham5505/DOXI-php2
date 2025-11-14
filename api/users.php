<?php
require_once '../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Get request method and parse JSON input
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Get database connection
$conn = getDBConnection();

if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

try {
    switch ($method) {
        case 'GET':
            handleGet($conn);
            break;
        case 'POST':
            handlePost($conn, $input);
            break;
        case 'PUT':
            handlePut($conn, $input);
            break;
        case 'DELETE':
            handleDelete($conn, $input);
            break;
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
} finally {
    $conn->close();
}

// Helper: check if a column exists in current DB
function columnExists($conn, $table, $column){
    try{
        $dbRes = $conn->query('SELECT DATABASE()');
        if(!$dbRes){ return true; }
        $row = $dbRes->fetch_row();
        $dbName = $row ? $row[0] : '';
        $stmt = $conn->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $stmt->bind_param('sss', $dbName, $table, $column);
        $stmt->execute();
        $res = $stmt->get_result();
        $countRow = $res ? $res->fetch_row() : [1];
        return intval($countRow[0]) > 0;
    }catch(Throwable $e){
        // If detection fails, assume column exists to avoid breaking responses
        return true;
    }
}

function handleGet($conn) {
    // Check if fetching by ID
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($id > 0) {
        $cols = ['id','first_name','last_name','email','phone','role','date_of_birth','gender','address','last_login','created_at','updated_at'];
        if (columnExists($conn, 'users', 'specialty')) { $cols[] = 'specialty'; }
        if (columnExists($conn, 'users', 'license_number')) { $cols[] = 'license_number'; }
        if (columnExists($conn, 'users', 'years_experience')) { $cols[] = 'years_experience'; }
        if (columnExists($conn, 'users', 'practice_address')) { $cols[] = 'practice_address'; }
        if (columnExists($conn, 'users', 'bio')) { $cols[] = 'bio'; }
        
        $stmt = $conn->prepare("SELECT " . implode(', ', $cols) . " FROM users WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'User not found']);
            return;
        }
        
        $user = $result->fetch_assoc();
        unset($user['password']);
        echo json_encode(['success' => true, 'data' => $user]);
        return;
    }

    $role = $_GET['role'] ?? '';
    $search = $_GET['search'] ?? '';
    $page = intval($_GET['page'] ?? 1);
    $limit = intval($_GET['limit'] ?? 10);
    $offset = ($page - 1) * $limit;
    
    $whereClause = '';
    $params = [];
    $types = '';
    
    if ($role) {
        $whereClause .= " WHERE role = ?";
        $params[] = $role;
        $types .= 's';
    }
    
    if ($search) {
        $searchTerm = "%$search%";
        $whereClause .= $whereClause ? " AND" : " WHERE";
        $whereClause .= " (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ?)";
        $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        $types .= 'ssss';
    }
    
    // Get total count
    $countQuery = "SELECT COUNT(*) as total FROM users" . $whereClause;
    $countStmt = $conn->prepare($countQuery);
    if ($params) {
        $countStmt->bind_param($types, ...$params);
    }
    $countStmt->execute();
    $total = $countStmt->get_result()->fetch_assoc()['total'];
    
    // Get users with pagination
    $cols = ['id','first_name','last_name','email','phone','role','date_of_birth','gender','address','last_login','created_at','updated_at'];
    if (columnExists($conn, 'users', 'specialty')) { $cols[] = 'specialty'; }
    if (columnExists($conn, 'users', 'license_number')) { $cols[] = 'license_number'; }
    if (columnExists($conn, 'users', 'years_experience')) { $cols[] = 'years_experience'; }
    if (columnExists($conn, 'users', 'practice_address')) { $cols[] = 'practice_address'; }
    if (columnExists($conn, 'users', 'bio')) { $cols[] = 'bio'; }
    $query = "SELECT " . implode(', ', $cols) . " FROM users" . $whereClause . " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    
    $stmt = $conn->prepare($query);
    $params[] = $limit;
    $params[] = $offset;
    $types .= 'ii';
    
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $users = [];
    while ($row = $result->fetch_assoc()) {
        // Remove sensitive data
        unset($row['password']);
        $users[] = $row;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $users,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => $total,
            'pages' => ceil($total / $limit)
        ]
    ]);
}

function handlePost($conn, $input) {
    // Validate required fields
    $required = ['first_name', 'last_name', 'email', 'password', 'role'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Field '$field' is required"]);
            return;
        }
    }
    
    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $input['email']);
    $checkStmt->execute();
    if ($checkStmt->get_result()->num_rows > 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        return;
    }
    
    // Hash password
    $hashedPassword = password_hash($input['password'], PASSWORD_DEFAULT);
    
    // Prepare insert query (use variables for bind_param by reference)
    $hasSpecialty = columnExists($conn, 'users', 'specialty');
    if ($hasSpecialty){
        $query = "INSERT INTO users (first_name, last_name, email, password, role, phone, specialty, date_of_birth, gender, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } else {
        $query = "INSERT INTO users (first_name, last_name, email, password, role, phone, date_of_birth, gender, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }
    
    $stmt = $conn->prepare($query);
    $firstName = $input['first_name'];
    $lastName = $input['last_name'];
    $email = $input['email'];
    $role = $input['role'];
    $phone = isset($input['phone']) ? $input['phone'] : '';
    $specialty = isset($input['specialty']) ? $input['specialty'] : '';
    $dateOfBirth = (isset($input['date_of_birth']) && $input['date_of_birth'] !== '') ? $input['date_of_birth'] : null;
    $gender = (isset($input['gender']) && $input['gender'] !== '') ? $input['gender'] : null;
    $address = isset($input['address']) ? $input['address'] : '';
    if ($hasSpecialty){
        $stmt->bind_param("ssssssssss",
            $firstName,
            $lastName,
            $email,
            $hashedPassword,
            $role,
            $phone,
            $specialty,
            $dateOfBirth,
            $gender,
            $address
        );
    } else {
        $stmt->bind_param("sssssssss",
            $firstName,
            $lastName,
            $email,
            $hashedPassword,
            $role,
            $phone,
            $dateOfBirth,
            $gender,
            $address
        );
    }
    
    if ($stmt->execute()) {
        $userId = $conn->insert_id;
        echo json_encode([
            'success' => true,
            'message' => 'User created successfully',
            'user_id' => $userId
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to create user: ' . $stmt->error]);
    }
}

function handlePut($conn, $input) {
    if (empty($input['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'User ID is required']);
        return;
    }
    
    // Check if user exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $checkStmt->bind_param("i", $input['id']);
    $checkStmt->execute();
    if ($checkStmt->get_result()->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'User not found']);
        return;
    }
    
    // Check if email is being changed and already exists
    if (!empty($input['email'])) {
        $emailCheckStmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $emailCheckStmt->bind_param("si", $input['email'], $input['id']);
        $emailCheckStmt->execute();
        if ($emailCheckStmt->get_result()->num_rows > 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            return;
        }
    }
    
    // Build update query dynamically
    $fields = [];
    $params = [];
    $types = '';
    
    $allowedFields = ['first_name', 'last_name', 'email', 'phone', 'role', 'date_of_birth', 'gender', 'address'];
    if (columnExists($conn, 'users', 'specialty')) { $allowedFields[] = 'specialty'; }
    if (columnExists($conn, 'users', 'license_number')) { $allowedFields[] = 'license_number'; }
    if (columnExists($conn, 'users', 'years_experience')) { $allowedFields[] = 'years_experience'; }
    if (columnExists($conn, 'users', 'practice_address')) { $allowedFields[] = 'practice_address'; }
    if (columnExists($conn, 'users', 'bio')) { $allowedFields[] = 'bio'; }
    
    // Handle years_experience as integer
    if (isset($input['years_experience']) && $input['years_experience'] !== '') {
        $input['years_experience'] = intval($input['years_experience']);
    }
    
    foreach ($allowedFields as $field) {
        if (isset($input[$field])) {
            $fields[] = "$field = ?";
            // Handle integer types
            if ($field === 'years_experience') {
                $params[] = intval($input[$field]);
                $types .= 'i';
            } else {
                $params[] = $input[$field];
                $types .= 's';
            }
        }
    }
    
    // Handle password update separately
    if (!empty($input['password'])) {
        $fields[] = "password = ?";
        $params[] = password_hash($input['password'], PASSWORD_DEFAULT);
        $types .= 's';
    }
    
    if (empty($fields)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No fields to update']);
        return;
    }
    
    $fields[] = "updated_at = CURRENT_TIMESTAMP";
    $params[] = $input['id'];
    $types .= 'i';
    
    $query = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to update user: ' . $stmt->error]);
    }
}

function handleDelete($conn, $input) {
    if (empty($input['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'User ID is required']);
        return;
    }
    
    // Check if user exists
    $checkStmt = $conn->prepare("SELECT id, role FROM users WHERE id = ?");
    $checkStmt->bind_param("i", $input['id']);
    $checkStmt->execute();
    $user = $checkStmt->get_result()->fetch_assoc();
    
    if (!$user) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'User not found']);
        return;
    }
    
    // Prevent deletion of admin users
    if ($user['role'] === 'admin') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Cannot delete admin users']);
        return;
    }
    
    // Delete user
    $deleteStmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $deleteStmt->bind_param("i", $input['id']);
    
    if ($deleteStmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to delete user: ' . $deleteStmt->error]);
    }
}
?>
