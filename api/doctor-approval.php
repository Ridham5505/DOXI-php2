<?php
require_once '../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$doctorId = isset($input['doctor_id']) ? intval($input['doctor_id']) : 0;
$status = strtolower(trim($input['status'] ?? 'approved'));

$validStatuses = ['pending','approved','rejected'];
if (!$doctorId || !in_array($status, $validStatuses, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid doctor or status']);
    exit();
}

$conn = getDBConnection();
if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

try {
    $conn->begin_transaction();

    $stmt = $conn->prepare("UPDATE users SET doctor_status = ?, approved_at = CASE WHEN ? = 'approved' THEN NOW() ELSE approved_at END WHERE id = ? AND role = 'doctor'");
    $stmt->bind_param('ssi', $status, $status, $doctorId);
    $stmt->execute();
    if ($stmt->affected_rows === 0) {
        $conn->rollback();
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Doctor not found']);
        exit();
    }

    $message = '';
    if ($status === 'approved') {
        $message = 'Your doctor account has been approved. You can now login to your dashboard.';
    } elseif ($status === 'rejected') {
        $message = 'Your doctor account request was rejected. Please contact support for more information.';
    } else {
        $message = 'Your doctor account status was updated.';
    }

    $notifStmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, link, is_read) VALUES (?, ?, ?, ?, 0)");
    $title = $status === 'approved' ? 'Doctor Account Approved' : 'Doctor Account Update';
    if ($status === 'approved') {
        $message = 'Your doctor account has been approved. Please complete your profile before accessing the dashboard.';
        $link = 'doctor-settings.php?setup=1';
        $profileStmt = $conn->prepare("UPDATE users SET profile_complete = 0 WHERE id = ?");
        $profileStmt->bind_param('i', $doctorId);
        $profileStmt->execute();
    } else {
        $link = 'login.php?role=doctor';
    }
    $notifStmt->bind_param('isss', $doctorId, $title, $message, $link);
    $notifStmt->execute();

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Doctor status updated']);
} catch (Throwable $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to update doctor status']);
}
?>

