<?php
require_once '../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }

$conn = getDBConnection();
if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB connection failed']);
    exit();
}

$conn->query("CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    link VARCHAR(255) DEFAULT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX(user_id),
    INDEX(is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

try {
    switch ($method) {
        case 'GET':
            handleGet($conn);
            break;
        case 'PUT':
            handlePut($conn, $input);
            break;
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: '.$e->getMessage()]);
} finally {
    $conn->close();
}

function handleGet($conn){
    $userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    $unreadOnly = isset($_GET['unread']) ? intval($_GET['unread']) : 0;
    if (!$userId){
        http_response_code(400);
        echo json_encode(['success'=>false,'message'=>'user_id is required']);
        return;
    }
    $sql = "SELECT id, title, message, link, is_read, created_at
            FROM notifications
            WHERE user_id = ?";
    if ($unreadOnly){
        $sql .= " AND is_read = 0";
    }
    $sql .= " ORDER BY created_at DESC LIMIT 100";
    $stmt = $conn->prepare($sql);
    if (!$stmt){
        http_response_code(500);
        echo json_encode(['success'=>false,'message'=>'Query failed: '.$conn->error]);
        return;
    }
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = [];
    while($row = $res->fetch_assoc()){
        $rows[] = $row;
    }
    echo json_encode(['success'=>true,'data'=>$rows]);
}

function handlePut($conn, $input){
    $ids = $input['ids'] ?? [];
    if (!$ids || !is_array($ids)){
        http_response_code(400);
        echo json_encode(['success'=>false,'message'=>'ids array is required']);
        return;
    }
    $ids = array_filter(array_map('intval', $ids));
    if (!$ids){
        echo json_encode(['success'=>true]);
        return;
    }
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));
    $sql = "UPDATE notifications SET is_read = 1 WHERE id IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    if (!$stmt){
        http_response_code(500);
        echo json_encode(['success'=>false,'message'=>'Update failed: '.$conn->error]);
        return;
    }
    $stmt->bind_param($types, ...$ids);
    if ($stmt->execute()){
        echo json_encode(['success'=>true]);
    } else {
        http_response_code(500);
        echo json_encode(['success'=>false,'message'=>'Failed to mark notifications as read.']);
    }
}

