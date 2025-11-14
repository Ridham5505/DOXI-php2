<?php
/**
 * Change Password API
 * Handles secure password change with validation
 * Automatically logs out user after successful password change
 */

require_once '../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$conn = getDBConnection();
if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

try {
    // Validate required fields
    if (empty($input['user_id']) || empty($input['current_password']) || empty($input['new_password'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'User ID, current password, and new password are required']);
        exit();
    }

    $userId = intval($input['user_id']);
    $currentPassword = $input['current_password'];
    $newPassword = $input['new_password'];
    $confirmPassword = $input['confirm_password'] ?? '';

    // Validate password confirmation
    if ($newPassword !== $confirmPassword) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'New password and confirm password do not match']);
        exit();
    }

    // Validate password strength
    if (strlen($newPassword) < 8) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long']);
        exit();
    }

    // Check for uppercase letter
    if (!preg_match('/[A-Z]/', $newPassword)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Password must contain at least one uppercase letter']);
        exit();
    }

    // Check for number
    if (!preg_match('/[0-9]/', $newPassword)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Password must contain at least one number']);
        exit();
    }

    // Check for special character
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $newPassword)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Password must contain at least one special character']);
        exit();
    }

    // Verify current password
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit();
    }

    $user = $result->fetch_assoc();
    $storedPassword = $user['password'];

    // Verify current password
    if (!password_verify($currentPassword, $storedPassword)) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
        exit();
    }

    // Check if new password is same as current password
    if (password_verify($newPassword, $storedPassword)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'New password must be different from current password']);
        exit();
    }

    // Hash new password
    $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update password in database
    $updateStmt = $conn->prepare("UPDATE users SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $updateStmt->bind_param('si', $hashedNewPassword, $userId);

    if ($updateStmt->execute()) {
        // Password updated successfully
        // Note: Session destruction should be handled on the frontend
        // The frontend should clear sessionStorage and redirect to login
        echo json_encode([
            'success' => true,
            'message' => 'Password changed successfully. Please log in again.'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to update password: ' . $updateStmt->error]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
} finally {
    $conn->close();
}

?>

