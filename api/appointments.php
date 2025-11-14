<?php
require_once '../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }

$conn = getDBConnection();
if (!$conn) { http_response_code(500); echo json_encode(['success'=>false,'message'=>'DB connection failed']); exit(); }

// Ensure table exists (lightweight migration)
// Create with preferred column names if the table doesn't exist yet
$conn->query("CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appt_date DATE NOT NULL,
    appt_time TIME NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'scheduled',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX(patient_id), INDEX(doctor_id), INDEX(appt_date), INDEX(status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true) ?: [];

try {
    switch ($method) {
        case 'GET':
            handleGet($conn);
            break;
        case 'POST':
            handlePost($conn, $input);
            break;
        case 'PUT':
            handlePut($conn, $input);
            break;
        case 'DELETE':
            handleDelete($conn, $input);
            break;
        default:
            http_response_code(405);
            echo json_encode(['success'=>false,'message'=>'Method not allowed']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Server error: '.$e->getMessage()]);
} finally { $conn->close(); }

function columnExists($conn, $table, $column){
    try{
        $dbRes = $conn->query('SELECT DATABASE()');
        if(!$dbRes){ return true; }
        $row = $dbRes->fetch_row();
        $dbName = $row ? $row[0] : '';
        $stmt = $conn->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?");
        $stmt->bind_param('sss', $dbName, $table, $column);
        $stmt->execute();
        $res = $stmt->get_result();
        $countRow = $res ? $res->fetch_row() : [1];
        return intval($countRow[0]) > 0;
    }catch(Throwable $e){ return true; }
}

function getApptColumns($conn){
    // Map to whatever columns exist on this installation
    $dateCol = columnExists($conn,'appointments','appt_date') ? 'appt_date' : (columnExists($conn,'appointments','date') ? 'date' : (columnExists($conn,'appointments','appointment_date') ? 'appointment_date' : 'appt_date'));
    $timeCol = columnExists($conn,'appointments','appt_time') ? 'appt_time' : (columnExists($conn,'appointments','time') ? 'time' : (columnExists($conn,'appointments','appointment_time') ? 'appointment_time' : 'appt_time'));
    return [$dateCol, $timeCol];
}

function handleGet($conn){
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $date = $_GET['date'] ?? '';
    $status = $_GET['status'] ?? '';
    $doctorId = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;
    $patientId = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;

    list($DATE_COL, $TIME_COL) = getApptColumns($conn);
    if ($id){
        $stmt = $conn->prepare("SELECT a.*, a.$DATE_COL AS appt_date, a.$TIME_COL AS appt_time,
            CONCAT(p.first_name,' ',p.last_name) AS patient_name, p.email AS patient_email,
            CONCAT(d.first_name,' ',d.last_name) AS doctor_name, d.email AS doctor_email
            FROM appointments a
            JOIN users p ON p.id=a.patient_id
            JOIN users d ON d.id=a.doctor_id
            WHERE a.id=?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        if(!$res){ http_response_code(404); echo json_encode(['success'=>false,'message'=>'Appointment not found']); return; }
        echo json_encode(['success'=>true,'data'=>$res]);
        return;
    }

    $where = [];
    $params = [];
    $types = '';
    if ($date){ $where[] = "a.$DATE_COL = ?"; $params[]=$date; $types.='s'; }
    if ($status!=='' ){ $where[] = 'a.status = ?'; $params[]=$status; $types.='s'; }
    if ($doctorId){ $where[] = 'a.doctor_id = ?'; $params[]=$doctorId; $types.='i'; }
    if ($patientId){ $where[] = 'a.patient_id = ?'; $params[]=$patientId; $types.='i'; }
    $whereSql = $where ? ('WHERE '.implode(' AND ', $where)) : '';
    $sql = "SELECT a.*, a.$DATE_COL AS appt_date, a.$TIME_COL AS appt_time,
            CONCAT(p.first_name,' ',p.last_name) AS patient_name, p.email AS patient_email,
            CONCAT(d.first_name,' ',d.last_name) AS doctor_name, d.email AS doctor_email
            FROM appointments a
            JOIN users p ON p.id=a.patient_id
            JOIN users d ON d.id=a.doctor_id
            $whereSql
            ORDER BY a.$DATE_COL DESC, a.$TIME_COL DESC";
    $stmt = $conn->prepare($sql);
    if (!$stmt){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'Query prepare failed: '.$conn->error]); return; }
    if ($params){ $stmt->bind_param($types, ...$params); }
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while($row=$result->fetch_assoc()){ $rows[]=$row; }
    echo json_encode(['success'=>true,'data'=>$rows]);
}

function handlePost($conn, $input){
    $required = ['patient_id','doctor_id','appt_date','appt_time'];
    foreach($required as $f){ if (empty($input[$f])){ http_response_code(400); echo json_encode(['success'=>false,'message'=>"Field '$f' is required"]); return; } }
    list($DATE_COL, $TIME_COL) = getApptColumns($conn);
    $patientId = intval($input['patient_id']);
    $doctorId = intval($input['doctor_id']);
    // Accept alternative payload keys from other panels
    $date = $input['appt_date'] ?? ($input['date'] ?? ($input['appointment_date'] ?? ''));
    $time = $input['appt_time'] ?? ($input['time'] ?? ($input['appointment_time'] ?? ''));
    if ($date === '' || $time === ''){ http_response_code(400); echo json_encode(['success'=>false,'message'=>'Date and time are required']); return; }
    $status = $input['status'] ?? 'scheduled';
    $notes = $input['notes'] ?? '';
    $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, $DATE_COL, $TIME_COL, status, notes) VALUES (?,?,?,?,?,?)");
    if (!$stmt){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'Insert prepare failed: '.$conn->error]); return; }
    $stmt->bind_param('iissss', $patientId, $doctorId, $date, $time, $status, $notes);
    if ($stmt->execute()){
        echo json_encode(['success'=>true,'id'=>$conn->insert_id]);
    } else { http_response_code(500); echo json_encode(['success'=>false,'message'=>'Create failed: '.$stmt->error]); }
}

function handlePut($conn, $input){
    if (empty($input['id'])){ http_response_code(400); echo json_encode(['success'=>false,'message'=>'Appointment ID is required']); return; }
    $id = intval($input['id']);
    list($DATE_COL, $TIME_COL) = getApptColumns($conn);
    $fields = [];
    $params = [];
    $types = '';
    foreach(['patient_id','doctor_id','appt_date','appt_time','status','notes'] as $f){
        if (isset($input[$f])){
            $col = $f;
            if ($f==='appt_date') $col = $DATE_COL; if ($f==='appt_time') $col = $TIME_COL;
            $fields[] = "$col = ?";
            $params[] = ($f==='patient_id' || $f==='doctor_id') ? intval($input[$f]) : $input[$f];
            $types .= ($f==='patient_id' || $f==='doctor_id') ? 'i' : 's';
        }
    }
    if (!$fields){ http_response_code(400); echo json_encode(['success'=>false,'message'=>'No fields to update']); return; }
    $params[] = $id; $types .= 'i';
    $sql = 'UPDATE appointments SET '.implode(', ',$fields).', updated_at = CURRENT_TIMESTAMP WHERE id = ?';
    $stmt = $conn->prepare($sql);
    if (!$stmt){ http_response_code(500); echo json_encode(['success'=>false,'message'=>'Update prepare failed: '.$conn->error]); return; }
    $stmt->bind_param($types, ...$params);
    if ($stmt->execute()){ echo json_encode(['success'=>true]); }
    else { http_response_code(500); echo json_encode(['success'=>false,'message'=>'Update failed: '.$stmt->error]); }
}

function handleDelete($conn, $input){
    if (empty($input['id'])){ http_response_code(400); echo json_encode(['success'=>false,'message'=>'Appointment ID is required']); return; }
    $id = intval($input['id']);
    $stmt = $conn->prepare('DELETE FROM appointments WHERE id = ?');
    $stmt->bind_param('i', $id);
    if ($stmt->execute()){ echo json_encode(['success'=>true]); }
    else { http_response_code(500); echo json_encode(['success'=>false,'message'=>'Delete failed: '.$stmt->error]); }
}

?>


