<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set headers for CORS and JSON response
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (!isset($data['email']) || !isset($data['password']) || !isset($data['role'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Sanitize inputs
$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
$password = $data['password'];
$role = strtolower(filter_var($data['role'], FILTER_SANITIZE_STRING));

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

// Validate role
$allowedRoles = ['patient', 'doctor', 'admin'];
if (!in_array($role, $allowedRoles)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid role']);
    exit;
}

// Special handling for test accounts
if (($email === 'patient1@doxi.com' && $password === 'patient1' && $role === 'patient') || 
    ($email === 'doctor1@doxi.com' && $password === 'doctor1' && $role === 'doctor')) {
    
    error_log("Test account login: $email with role $role");
    
    // Create a default user object for test accounts
    $user = [
        'id' => ($role === 'patient') ? 1001 : 2001,
        'email' => $email,
        'role' => $role,
        'first_name' => ($role === 'patient') ? 'Test' : 'Dr. Test',
        'last_name' => 'User',
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    // Generate a session token
    $sessionToken = bin2hex(random_bytes(32));
    
    // Return success response with user details and session token
    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'user' => [
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'name' => $user['first_name'] . ' ' . $user['last_name']
        ],
        'session_token' => $sessionToken
    ]);
    exit;
}

// Database connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=doxi_healthcare', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

try {
    // Regular authentication flow
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        error_log("Login failed: Email not found - $email");
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid credentials. Please check your email and password.']);
        exit;
    }
    
    // Check role matches the stored role
    if (!isset($user['role']) || strtolower($user['role']) !== $role) {
        error_log("Login failed: Invalid role - User: $email, Expected: $role, Got: " . ($user['role'] ?? 'none'));
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid credentials. Please check your email and password.']);
        exit;
    }
    
    if (isset($user['account_status']) && strtolower((string)$user['account_status']) === 'deleted') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'This account is no longer available. Please contact support for assistance.']);
        exit;
    }

    // Skip password verification for database users
    $valid = password_verify($password, $user['password'] ?? '');
    if (!$valid && isset($user['password']) && hash_equals($user['password'], $password)) {
        // Legacy plaintext match → upgrade to hashed
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $upd = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
            $upd->execute([$newHash, $user['id']]);
            $valid = true;
        } catch (PDOException $e) {
            error_log('Failed to upgrade legacy password hash: ' . $e->getMessage());
        }
    }
    
    if (!$valid) {
        error_log("Login failed: Invalid password for $email");
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid credentials. Please check your email and password.']);
        exit;
    }
    
    if ($role === 'doctor') {
        $doctorStatus = isset($user['doctor_status']) ? strtolower($user['doctor_status']) : 'approved';
        if ($doctorStatus !== 'approved') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Your account is awaiting admin approval. You will be notified once you can log in.']);
            exit;
        }
    }
    
    // Remove password from response
    unset($user['password']);
    
    // Generate session token (simple implementation)
    $sessionToken = bin2hex(random_bytes(32));
    
    // Store session in database (optional - for session management)
    $sessionStmt = $pdo->prepare("
        INSERT INTO user_sessions (user_id, role, token, expires_at) 
        VALUES (?, ?, ?, DATE_ADD(NOW(), INTERVAL 24 HOUR))
    ");
    
    // Create sessions table if it doesn't exist
    $createSessionsTable = "
    CREATE TABLE IF NOT EXISTS user_sessions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        role ENUM('patient', 'doctor', 'admin') NOT NULL,
        token VARCHAR(64) UNIQUE NOT NULL,
        expires_at TIMESTAMP NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($createSessionsTable);
    
    try {
        $sessionStmt->execute([$user['id'], $role, $sessionToken]);
    } catch (PDOException $e) {
        // Session storage failed, but login can still proceed
        error_log("Session storage failed: " . $e->getMessage());
    }
    
    // Update last_login timestamp for this user
    try {
        $updLogin = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
        $updLogin->execute([$user['id']]);
        $user['last_login'] = date('Y-m-d H:i:s');
    } catch (PDOException $e) { /* ignore failure */ }
    
    // Log login event
    try {
        $logStmt = $pdo->prepare("INSERT INTO system_logs (level, message, user_id, user_email) VALUES ('INFO', ?, ?, ?)");
        $logMsg = strtoupper($role) . " login: " . ($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '') . " (" . $email . ")";
        // Ensure table exists
        $pdo->exec("CREATE TABLE IF NOT EXISTS system_logs (id INT AUTO_INCREMENT PRIMARY KEY, level ENUM('INFO','WARN','ERROR') DEFAULT 'INFO', message TEXT NOT NULL, user_id INT NULL, user_email VARCHAR(255) NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
        $logStmt->execute([$logMsg, $user['id'], $email]);
    } catch (PDOException $e) { /* ignore logging failure */ }

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'user' => $user,
        'role' => $role,
        'token' => $sessionToken
    ]);
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?>
