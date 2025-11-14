<?php
require_once '../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }

$conn = getDBConnection();
if (!$conn) { http_response_code(500); echo json_encode(['success'=>false,'message'=>'DB connection failed']); exit(); }

// Create settings table (simple key/value store)
$conn->query("CREATE TABLE IF NOT EXISTS settings (
  `key` VARCHAR(64) PRIMARY KEY,
  `value` TEXT NOT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

try{
  switch ($method){
    case 'GET': handleGet($conn); break;
    case 'POST':
    case 'PUT': handleUpsert($conn, $input); break;
    default: http_response_code(405); echo json_encode(['success'=>false,'message'=>'Method not allowed']);
  }
}catch(Throwable $e){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'Server error: '.$e->getMessage()]); }
finally { $conn->close(); }

function handleGet($conn){
  $res = $conn->query('SELECT `key`, `value` FROM settings');
  if(!$res){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'Query failed: '.$conn->error]); return; }
  $data=[]; while($row=$res->fetch_assoc()){ $data[$row['key']] = json_decode($row['value'], true); if ($data[$row['key']]===null){ $data[$row['key']] = $row['value']; } }
  echo json_encode(['success'=>true,'data'=>$data]);
}

function handleUpsert($conn, $in){
  if (!is_array($in) || !$in){ http_response_code(400); echo json_encode(['success'=>false,'message'=>'No settings provided']); return; }
  $stmt = $conn->prepare('INSERT INTO settings(`key`,`value`) VALUES(?,?) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`)');
  if(!$stmt){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'Prepare failed: '.$conn->error]); return; }
  foreach($in as $k=>$v){
    $val = is_array($v) ? json_encode($v) : (is_bool($v) ? ($v ? 'true' : 'false') : (string)$v);
    $stmt->bind_param('ss', $k, $val);
    if(!$stmt->execute()){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'Save failed: '.$stmt->error]); return; }
  }
  echo json_encode(['success'=>true]);
}

?>


