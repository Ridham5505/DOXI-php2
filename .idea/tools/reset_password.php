<?php
// Quick helper to reset a user's password using PHP's password_hash
// Usage: /DOXI/tools/reset_password.php?email=user@example.com&password=newpass

header('Content-Type: application/json');

require_once '../config/database.php';

$email = isset($_GET['email']) ? trim(strtolower($_GET['email'])) : '';
$newPassword = isset($_GET['password']) ? $_GET['password'] : '';

if ($email === '' || $newPassword === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Provide email and password query params']);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if (!$user) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }

    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $upd = $pdo->prepare('UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?');
    $upd->execute([$hash, $user['id']]);

    echo json_encode(['success' => true, 'message' => 'Password updated', 'email' => $email]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB error', 'error' => $e->getMessage()]);
}
?>


