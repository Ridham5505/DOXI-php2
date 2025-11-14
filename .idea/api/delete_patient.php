<?php
// Include database connection
require_once 'database.php';

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get the request body
$data = json_decode(file_get_contents('php://input'), true);

// Check if email is provided
if (!isset($data['email']) || empty($data['email'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email is required']);
    exit;
}

$email = $data['email'];

try {
    // Start transaction
    $conn->begin_transaction();

    // First, get the patient ID
    $stmt = $conn->prepare("SELECT id FROM patients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $conn->rollback();
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Patient not found']);
        exit;
    }
    
    $patient = $result->fetch_assoc();
    $patientId = $patient['id'];
    
    // Delete related records first (appointments, medical records, etc.)
    $stmt = $conn->prepare("DELETE FROM appointments WHERE patient_id = ?");
    $stmt->bind_param("i", $patientId);
    $stmt->execute();
    
    $stmt = $conn->prepare("DELETE FROM medical_records WHERE patient_id = ?");
    $stmt->bind_param("i", $patientId);
    $stmt->execute();
    
    $stmt = $conn->prepare("DELETE FROM prescriptions WHERE patient_id = ?");
    $stmt->bind_param("i", $patientId);
    $stmt->execute();
    
    // Finally, delete the patient record
    $stmt = $conn->prepare("DELETE FROM patients WHERE id = ?");
    $stmt->bind_param("i", $patientId);
    $stmt->execute();
    
    // Delete user account if exists
    $stmt = $conn->prepare("DELETE FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    // Commit transaction
    $conn->commit();
    
    echo json_encode(['success' => true, 'message' => 'Patient record deleted successfully']);
    
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

// Close connection
$conn->close();
?>