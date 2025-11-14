<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
    exit;
}

$role = $input['role'] ?? '';
$email = $input['email'] ?? '';
$password = $input['password'] ?? '';
$confirmPassword = $input['confirmPassword'] ?? '';

// Validate required fields
if (empty($role) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

// Validate password confirmation
if ($password !== $confirmPassword) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

// Validate password strength
if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']);
    exit;
}

try {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    if (in_array($role, ['patient','doctor','admin'], true)) {
        // Base required fields for all roles
        $requiredBase = ['firstName', 'lastName'];
        foreach ($requiredBase as $field) {
            if (empty($input[$field])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
                exit;
            }
        }

        // Role-specific checks
        if ($role === 'patient') {
            $requiredPatient = ['dateOfBirth', 'gender'];
            foreach ($requiredPatient as $field) {
                if (empty($input[$field])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
                    exit;
                }
            }
        }

        if ($role === 'doctor') {
            $requiredDoctor = ['specialty', 'licenseNumber', 'yearsExperience'];
            foreach ($requiredDoctor as $field) {
                if (empty($input[$field])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
                    exit;
                }
            }
        }

        // Check if email already exists in users table
        $checkEmail = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $checkEmail->execute([$email]);
        if ($checkEmail->fetch()) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Email already registered']);
            exit;
        }

        // Check license for doctors
        if ($role === 'doctor') {
            $checkLicense = $pdo->prepare("SELECT id FROM users WHERE license_number = ?");
            $checkLicense->execute([$input['licenseNumber']]);
            if ($checkLicense->fetch()) {
                http_response_code(409);
                echo json_encode(['success' => false, 'message' => 'License number already registered']);
                exit;
            }
        }

        // Insert into unified users table
        $stmt = $pdo->prepare("INSERT INTO users (
            role, email, password, first_name, last_name, phone, date_of_birth, gender, address, specialty, license_number, years_experience, practice_address
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $role,
            $email,
            $hashedPassword,
            $input['firstName'],
            $input['lastName'],
            $input['phone'] ?? null,
            $input['dateOfBirth'] ?? null,
            $input['gender'] ?? null,
            $input['address'] ?? ($input['practiceAddress'] ?? null),
            $input['specialty'] ?? null,
            $input['licenseNumber'] ?? null,
            isset($input['yearsExperience']) ? (int)$input['yearsExperience'] : null,
            $input['practiceAddress'] ?? null
        ]);

        $userId = $pdo->lastInsertId();

        // Log registration in system logs so admin can review from dashboard
        try {
            // Ensure logs table exists (first-run safety)
            if (empty($GLOBALS['__DOXI_LOG_TABLE_READY'])) {
                $pdo->exec("CREATE TABLE IF NOT EXISTS logs (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    level VARCHAR(10) NOT NULL DEFAULT 'INFO',
                    message TEXT NOT NULL,
                    user_email VARCHAR(255) NULL,
                    user_id INT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX(level), INDEX(user_id), INDEX(created_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
                $GLOBALS['__DOXI_LOG_TABLE_READY'] = true;
            }

            $logMessageData = [
                'role' => $role,
                'firstName' => $input['firstName'],
                'lastName' => $input['lastName'],
                'email' => $email,
                'phone' => $input['phone'] ?? null,
                'dateOfBirth' => $input['dateOfBirth'] ?? null,
                'gender' => $input['gender'] ?? null,
                'address' => $input['address'] ?? ($input['practiceAddress'] ?? null),
            ];
            if ($role === 'doctor') {
                $logMessageData['specialty'] = $input['specialty'] ?? null;
                $logMessageData['licenseNumber'] = $input['licenseNumber'] ?? null;
                $logMessageData['yearsExperience'] = isset($input['yearsExperience']) ? (int)$input['yearsExperience'] : null;
                $logMessageData['practiceAddress'] = $input['practiceAddress'] ?? null;
            }
            $logStmt = $pdo->prepare("INSERT INTO logs (level, message, user_email, user_id) VALUES ('INFO', ?, ?, ?)");
            $logStmt->execute([
                'Registration: '.ucfirst($role).' | '.json_encode($logMessageData),
                $email,
                $userId
            ]);
        } catch (PDOException $logError) {
            error_log('Failed to record registration log: '.$logError->getMessage());
        }
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid role']);
        exit;
    }
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => ucfirst($role) . ' account created successfully',
        'userId' => $userId,
        'role' => $role
    ]);
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?>
