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
$conn->query("CREATE TABLE IF NOT EXISTS reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  doctor_id INT NOT NULL,
  patient_id INT NULL,
  rating TINYINT NOT NULL,
  comment TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX(doctor_id), INDEX(patient_id), INDEX(rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

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
  $doctorId = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;
  $patientId = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;
  $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;
  if ($id){
    $stmt = $conn->prepare("SELECT r.*, CONCAT(d.first_name,' ',d.last_name) AS doctor_name, CONCAT(p.first_name,' ',p.last_name) AS patient_name, p.email AS patient_email FROM reviews r LEFT JOIN users d ON d.id=r.doctor_id LEFT JOIN users p ON p.id=r.patient_id WHERE r.id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if (!$row){ http_response_code(404); echo json_encode(['success'=>false,'message'=>'Not found']); return; }
    echo json_encode(['success'=>true,'data'=>$row]); return;
  }
  $where = [];$params=[];$types='';
  if ($doctorId){ $where[]='r.doctor_id=?'; $params[]=$doctorId; $types.='i'; }
  if ($patientId){ $where[]='r.patient_id=?'; $params[]=$patientId; $types.='i'; }
  $whereSql = $where?('WHERE '.implode(' AND ',$where)) : '';
  $sql = "SELECT r.*, CONCAT(d.first_name,' ',d.last_name) AS doctor_name, CONCAT(p.first_name,' ',p.last_name) AS patient_name, p.email AS patient_email FROM reviews r LEFT JOIN users d ON d.id=r.doctor_id LEFT JOIN users p ON p.id=r.patient_id $whereSql ORDER BY r.created_at DESC LIMIT ?";
  $stmt = $conn->prepare($sql);
  if (!$stmt){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'Query prepare failed: '.$conn->error]); return; }
  $params[] = $limit; $types.='i';
  $stmt->bind_param($types, ...$params);
  $stmt->execute();
  $res = $stmt->get_result();
  $rows = [];
  while($row=$res->fetch_assoc()){ $rows[]=$row; }
  echo json_encode(['success'=>true,'data'=>$rows]);
}

function handlePost($conn, $in){
  $required=['doctor_id','rating','comment'];
  foreach($required as $f){ if (!isset($in[$f]) || $in[$f]===''){ http_response_code(400); echo json_encode(['success'=>false,'message'=>"Field '$f' is required"]); return; } }
  $doctorId = intval($in['doctor_id']);
  $patientId = isset($in['patient_id']) ? intval($in['patient_id']) : null;
  $rating = intval($in['rating']);
  $comment = $in['comment'];
  $stmt = $conn->prepare('INSERT INTO reviews (doctor_id, patient_id, rating, comment) VALUES (?,?,?,?)');
  if (!$stmt){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'Insert prepare failed: '.$conn->error]); return; }
  if ($patientId){ $stmt->bind_param('iiis',$doctorId,$patientId,$rating,$comment); }
  else { $null = null; $stmt->bind_param('iiis',$doctorId,$null,$rating,$comment); }
  if ($stmt->execute()){ echo json_encode(['success'=>true,'id'=>$conn->insert_id]); }
  else { http_response_code(500); echo json_encode(['success'=>false,'message'=>'Insert failed: '.$stmt->error]); }
}

function handlePut($conn, $in){
  if (empty($in['id'])){ http_response_code(400); echo json_encode(['success'=>false,'message'=>'ID is required']); return; }
  $id = intval($in['id']);
  $fields=[];$params=[];$types='';
  foreach(['rating','comment','doctor_id','patient_id'] as $f){
    if (isset($in[$f])){ $fields[] = "$f = ?"; $params[] = in_array($f,['doctor_id','patient_id','rating']) ? intval($in[$f]) : $in[$f]; $types .= in_array($f,['doctor_id','patient_id','rating']) ? 'i' : 's'; }
  }
  if (!$fields){ http_response_code(400); echo json_encode(['success'=>false,'message'=>'No fields to update']); return; }
  $params[] = $id; $types.='i';
  $sql = 'UPDATE reviews SET '.implode(', ',$fields).', updated_at=CURRENT_TIMESTAMP WHERE id=?';
  $stmt = $conn->prepare($sql);
  if (!$stmt){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'Update prepare failed: '.$conn->error]); return; }
  $stmt->bind_param($types, ...$params);
  if ($stmt->execute()){ echo json_encode(['success'=>true]); }
  else { http_response_code(500); echo json_encode(['success'=>false,'message'=>'Update failed: '.$stmt->error]); }
}

function handleDelete($conn, $in){
  if (empty($in['id'])){ http_response_code(400); echo json_encode(['success'=>false,'message'=>'ID is required']); return; }
  $id = intval($in['id']);
  $stmt = $conn->prepare('DELETE FROM reviews WHERE id=?');
  if (!$stmt){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'Delete prepare failed: '.$conn->error]); return; }
  $stmt->bind_param('i', $id);
  if ($stmt->execute()){ echo json_encode(['success'=>true]); }
  else { http_response_code(500); echo json_encode(['success'=>false,'message'=>'Delete failed: '.$stmt->error]); }
}

?>


