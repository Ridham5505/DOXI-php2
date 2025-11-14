<?php
// Production-safe error handling for JSON APIs
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Set headers for CORS and JSON response
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
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

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Get patient ID from URL if provided
$patientId = isset($_GET['id']) ? $_GET['id'] : null;

// Handle different HTTP methods
switch ($method) {
    case 'GET':
        // Retrieve patient(s)
        if ($patientId) {
            // Get specific patient
            getPatient($pdo, $patientId);
        } else {
            // Get all patients or filter by email if provided
            $email = isset($_GET['email']) ? $_GET['email'] : null;
            if ($email) {
                getPatientByEmail($pdo, $email);
            } else {
                getAllPatients($pdo);
            }
        }
        break;
        
    case 'POST':
        // Create new patient
        createPatient($pdo);
        break;
        
    case 'PUT':
        // Update existing patient
        if (!$patientId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Patient ID is required for update']);
            exit;
        }
        updatePatient($pdo, $patientId);
        break;
        
    case 'DELETE':
        // Delete patient
        if (!$patientId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Patient ID is required for deletion']);
            exit;
        }
        deletePatient($pdo, $patientId);
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}

// Helper: check if column exists in current database
function columnExists($pdo, $table, $column) {
    try {
        $dbName = $pdo->query('SELECT DATABASE()')->fetchColumn();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $stmt->execute([$dbName, $table, $column]);
        return $stmt->fetchColumn() > 0;
    } catch (Throwable $e) {
        // If detection fails, assume it exists to avoid hiding data unintentionally
        return true;
    }
}

// Function to get all patients
function getAllPatients($pdo) {
    try {
        // Build column list robustly (gender/last_login may be absent in some DBs)
        $cols = ['id','first_name','last_name','email','phone','date_of_birth','address','created_at'];
        if (columnExists($pdo, 'users', 'gender')) { $cols[] = 'gender'; }
        else { $cols[] = 'NULL AS gender'; }
        if (columnExists($pdo, 'users', 'last_login')) { $cols[] = 'last_login'; }
        else { $cols[] = 'NULL AS last_login'; }
        $sql = 'SELECT ' . implode(', ', $cols) . " FROM users WHERE role = 'patient'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get medical records count for each patient
        foreach ($patients as &$patient) {
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM medical_records WHERE patient_id = ?");
            $stmt->execute([$patient['id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $patient['medical_records_count'] = (int)$result['count'];
        }
        
        http_response_code(200);
        echo json_encode(['success' => true, 'data' => $patients]);
    } catch (PDOException $e) {
        error_log('Failed to get patients: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to retrieve patients']);
    }
}

// Function to get a specific patient by ID
function getPatient($pdo, $id) {
    try {
        $cols = ['id','first_name','last_name','email','phone','date_of_birth','address','created_at'];
        if (columnExists($pdo, 'users', 'gender')) { $cols[] = 'gender'; } else { $cols[] = 'NULL AS gender'; }
        if (columnExists($pdo, 'users', 'last_login')) { $cols[] = 'last_login'; } else { $cols[] = 'NULL AS last_login'; }
        $sql = 'SELECT ' . implode(', ', $cols) . " FROM users WHERE id = ? AND role = 'patient'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$patient) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Patient not found']);
            exit;
        }
        
        // Get patient's medical records
        $stmt = $pdo->prepare("SELECT id, record_type, title, description, file_path, doctor_id, created_at FROM medical_records WHERE patient_id = ? ORDER BY created_at DESC");
        $stmt->execute([$id]);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $patient['medical_records'] = $records;
        
        http_response_code(200);
        echo json_encode(['success' => true, 'data' => $patient]);
    } catch (PDOException $e) {
        error_log('Failed to get patient: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to retrieve patient']);
    }
}

// Function to get a patient by email
function getPatientByEmail($pdo, $email) {
    try {
        $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, phone, date_of_birth, gender, address, last_login, created_at FROM users WHERE email = ? AND role = 'patient'");
        $stmt->execute([$email]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$patient) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Patient not found']);
            exit;
        }
        
        // Get patient's medical records
        $stmt = $pdo->prepare("SELECT id, record_type, title, description, file_path, doctor_id, created_at FROM medical_records WHERE patient_id = ? ORDER BY created_at DESC");
        $stmt->execute([$patient['id']]);
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $patient['medical_records'] = $records;
        
        http_response_code(200);
        echo json_encode(['success' => true, 'data' => $patient]);
    } catch (PDOException $e) {
        error_log('Failed to get patient: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to retrieve patient']);
    }
}

// Function to create a new patient
function createPatient($pdo) {
    // Get POST data
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Validate required fields
    if (!isset($data['email']) || !isset($data['password']) || !isset($data['first_name']) || !isset($data['last_name'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }
    
    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$data['email']]);
        if ($stmt->fetch()) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            exit;
        }
        
        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Insert new patient
        $stmt = $pdo->prepare("
            INSERT INTO users (
                email, password, role, first_name, last_name, phone, date_of_birth, gender, 
                address, created_at
            ) VALUES (
                ?, ?, 'patient', ?, ?, ?, ?, ?, ?, NOW()
            )
        ");
        
        $stmt->execute([
            $data['email'],
            $hashedPassword,
            $data['first_name'],
            $data['last_name'],
            $data['phone'] ?? null,
            $data['date_of_birth'] ?? null,
            $data['gender'] ?? null,
            $data['address'] ?? null
        ]);
        
        $newPatientId = $pdo->lastInsertId();
        
        http_response_code(201);
        echo json_encode([
            'success' => true, 
            'message' => 'Patient created successfully',
            'data' => ['id' => $newPatientId]
        ]);
    } catch (PDOException $e) {
        error_log('Failed to create patient: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to create patient']);
    }
}

// Function to update an existing patient
function updatePatient($pdo, $id) {
    // Get PUT data
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Validate if patient exists
    try {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ? AND role = 'patient'");
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Patient not found']);
            exit;
        }
        
        // Build update query dynamically based on provided fields
        $updateFields = [];
        $params = [];
        
        // Fields that can be updated
        $allowedFields = [
            'first_name', 'last_name', 'phone', 'date_of_birth', 'gender',
            'address'
        ];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updateFields[] = "$field = ?";
                $params[] = $data[$field];
            }
        }
        
        // Handle password update separately
        if (isset($data['password']) && !empty($data['password'])) {
            $updateFields[] = "password = ?";
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Add updated_at timestamp
        $updateFields[] = "updated_at = NOW()";
        
        // Add patient ID to params
        $params[] = $id;
        
        // Execute update if there are fields to update
        if (count($updateFields) > 0) {
            $sql = "UPDATE users SET " . implode(", ", $updateFields) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Patient updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No fields to update']);
        }
    } catch (PDOException $e) {
        error_log('Failed to update patient: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to update patient']);
    }
}

// Function to delete a patient
function deletePatient($pdo, $id) {
    try {
        // Check if patient exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ? AND role = 'patient'");
        $stmt->execute([$id]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Patient not found']);
            exit;
        }
        
        // Begin transaction
        $pdo->beginTransaction();
        
        // Delete related records first
        $stmt = $pdo->prepare("DELETE FROM medical_records WHERE patient_id = ?");
        $stmt->execute([$id]);
        
        // Delete patient
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'patient'");
        $stmt->execute([$id]);
        
        // Commit transaction
        $pdo->commit();
        
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Patient deleted successfully']);
    } catch (PDOException $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        error_log('Failed to delete patient: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to delete patient']);
    }
}
?>