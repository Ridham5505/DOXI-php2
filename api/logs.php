<?php
require_once '../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }

$conn = getDBConnection();
if (!$conn) { http_response_code(500); echo json_encode(['success'=>false,'message'=>'DB connection failed']); exit(); }

// Create table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  level VARCHAR(10) NOT NULL DEFAULT 'INFO',
  message TEXT NOT NULL,
  user_email VARCHAR(255) NULL,
  user_id INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX(level), INDEX(user_id), INDEX(created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// Ensure required columns exist for older installs
function logs_column_exists($conn, $col){
    $dbRes = $conn->query('SELECT DATABASE()');
    if(!$dbRes){ return true; }
    $row = $dbRes->fetch_row();
    $db = $row ? $row[0] : '';
    $stmt = $conn->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=? AND TABLE_NAME='logs' AND COLUMN_NAME=?");
    if($stmt){ $stmt->bind_param('ss', $db, $col); $stmt->execute(); $r=$stmt->get_result()->fetch_row(); return intval($r[0])>0; }
    return true;
}
if(!logs_column_exists($conn,'user_email')){ $conn->query("ALTER TABLE logs ADD COLUMN user_email VARCHAR(255) NULL"); }
if(!logs_column_exists($conn,'user_id')){ $conn->query("ALTER TABLE logs ADD COLUMN user_id INT NULL"); }

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

try{
    switch ($method){
        case 'GET': handleGet($conn); break;
        case 'POST': handlePost($conn, $input); break;
        case 'PUT': handlePut($conn, $input); break;
        case 'DELETE': handleDelete($conn, $input); break;
        default: http_response_code(405); echo json_encode(['success'=>false,'message'=>'Method not allowed']);
    }
} catch (Throwable $e){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'Server error: '.$e->getMessage()]); }
finally { $conn->close(); }

function handleGet($conn){
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;
    $level = isset($_GET['level']) ? strtoupper(trim($_GET['level'])) : '';
    if ($id){
        $stmt = $conn->prepare('SELECT * FROM logs WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if (!$row){ http_response_code(404); echo json_encode(['success'=>false,'message'=>'Not found']); return; }
        echo json_encode(['success'=>true,'data'=>$row]);
        return;
    }
    $limitInt = max(1, min(1000, intval($limit)));
    $sql = 'SELECT id, level, message, user_email, user_id, created_at, updated_at FROM logs';
    if ($level && in_array($level, ['INFO','WARN','ERROR'])){
        $sql .= " WHERE level='".$conn->real_escape_string($level)."'";
    }
    $sql .= ' ORDER BY created_at DESC LIMIT '.$limitInt;
    $res = $conn->query($sql);
    if(!$res){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'Query failed: '.$conn->error]); return; }
    $rows = [];
    while($row=$res->fetch_assoc()){ $rows[]=$row; }
    echo json_encode(['success'=>true,'data'=>$rows]);
}

function handlePost($conn, $in){
    $level = isset($in['level']) ? strtoupper(trim($in['level'])) : 'INFO';
    if (!in_array($level, ['INFO','WARN','ERROR'])) $level = 'INFO';
    $message = isset($in['message']) ? trim($in['message']) : '';
    if ($message===''){ http_response_code(400); echo json_encode(['success'=>false,'message'=>'Message is required']); return; }
    $user_email = array_key_exists('user_email',$in) ? (trim($in['user_email']) ?: null) : null;
    $user_id = array_key_exists('user_id',$in) ? (is_null($in['user_id']) ? null : intval($in['user_id'])) : null;
    // Some MySQL setups have issues binding NULL for ints; use dynamic SQL
    $columns = 'level, message';
    $values = "'".$conn->real_escape_string($level)."', '".$conn->real_escape_string($message)."'";
    if ($user_email !== null && $user_email !== ''){ $columns .= ', user_email'; $values .= ", '".$conn->real_escape_string($user_email)."'"; }
    if ($user_id !== null){ $columns .= ', user_id'; $values .= ', '.intval($user_id); }
    $sql = "INSERT INTO logs ($columns) VALUES ($values)";
    if ($conn->query($sql)){ echo json_encode(['success'=>true,'id'=>$conn->insert_id]); }
    else { http_response_code(500); echo json_encode(['success'=>false,'message'=>'Insert failed: '.$conn->error, 'sql'=>$sql]); }
}

function handlePut($conn, $in){
    if (empty($in['id'])){ http_response_code(400); echo json_encode(['success'=>false,'message'=>'ID is required']); return; }
    $id = intval($in['id']);
    $fields=[];$params=[];$types='';
    if (isset($in['level'])){ $lvl=strtoupper(trim($in['level'])); if(!in_array($lvl,['INFO','WARN','ERROR'])) $lvl='INFO'; $fields[]='level=?'; $params[]=$lvl; $types.='s'; }
    if (isset($in['message'])){ $fields[]='message=?'; $params[]=$in['message']; $types.='s'; }
    if (array_key_exists('user_email',$in)){ $fields[]='user_email=?'; $params[]=$in['user_email']; $types.='s'; }
    if (array_key_exists('user_id',$in)){ $fields[]='user_id=?'; $params[] = is_null($in['user_id']) ? null : intval($in['user_id']); $types.='i'; }
    if (!$fields){ http_response_code(400); echo json_encode(['success'=>false,'message'=>'No fields to update']); return; }
    $params[]=$id; $types.='i';
    $sql = 'UPDATE logs SET '.implode(', ',$fields).', updated_at=CURRENT_TIMESTAMP WHERE id=?';
    $stmt = $conn->prepare($sql);
    if (!$stmt){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'Update prepare failed: '.$conn->error]); return; }
    $stmt->bind_param($types, ...$params);
    if ($stmt->execute()){ echo json_encode(['success'=>true]); }
    else { http_response_code(500); echo json_encode(['success'=>false,'message'=>'Update failed: '.$stmt->error]); }
}

function handleDelete($conn, $in){
    if (empty($in['id'])){ http_response_code(400); echo json_encode(['success'=>false,'message'=>'ID is required']); return; }
    $id = intval($in['id']);
    $stmt = $conn->prepare('DELETE FROM logs WHERE id=?');
    if (!$stmt){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'Delete prepare failed: '.$conn->error]); return; }
    $stmt->bind_param('i', $id);
    if ($stmt->execute()){ echo json_encode(['success'=>true]); }
    else { http_response_code(500); echo json_encode(['success'=>false,'message'=>'Delete failed: '.$stmt->error]); }
}

?>

<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

// Ensure table
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS system_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        level ENUM('INFO','WARN','ERROR') DEFAULT 'INFO',
        message TEXT NOT NULL,
        user_id INT NULL,
        user_email VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Failed to ensure logs table']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        $level = strtoupper($input['level'] ?? 'INFO');
        if (!in_array($level, ['INFO','WARN','ERROR'])) { $level = 'INFO'; }
        $message = $input['message'] ?? '';
        $userId = $input['user_id'] ?? null;
        $userEmail = $input['user_email'] ?? null;
        if (trim($message) === '') { http_response_code(400); echo json_encode(['success'=>false,'message'=>'message required']); exit; }
        $stmt = $pdo->prepare("INSERT INTO system_logs (level, message, user_id, user_email) VALUES (?,?,?,?)");
        $stmt->execute([$level, $message, $userId, $userEmail]);
        echo json_encode(['success'=>true]);
        exit;
    }
    if ($method === 'GET') {
        $limit = max(1, min(200, intval($_GET['limit'] ?? 50)));
        $stmt = $pdo->prepare("SELECT id, level, message, user_id, user_email, created_at FROM system_logs ORDER BY id DESC LIMIT ?");
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        echo json_encode(['success'=>true,'data'=>$stmt->fetchAll()]);
        exit;
    }
    http_response_code(405);
    echo json_encode(['success'=>false,'message'=>'Method not allowed']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Database error']);
}
