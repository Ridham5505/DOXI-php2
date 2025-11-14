<?php
require_once '../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }

$conn = getDBConnection();
if (!$conn) { http_response_code(500); echo json_encode(['success'=>false,'message'=>'DB connection failed']); exit(); }

$conn->query("CREATE TABLE IF NOT EXISTS doctor_patient_notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    patient_id INT NOT NULL,
    title VARCHAR(120) NOT NULL,
    note TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX(doctor_id), INDEX(patient_id)
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
} finally {
    $conn->close();
}

function handleGet($conn) {
    $doctorId = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;
    $patientId = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id) {
        $stmt = $conn->prepare('SELECT * FROM doctor_patient_notes WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if (!$row) { http_response_code(404); echo json_encode(['success'=>false,'message'=>'Note not found']); return; }
        echo json_encode(['success'=>true,'data'=>$row]);
        return;
    }

    if (!$doctorId) {
        http_response_code(400);
        echo json_encode(['success'=>false,'message'=>'doctor_id is required']);
        return;
    }

    $where = ['doctor_id = ?'];
    $params = [$doctorId];
    $types = 'i';
    if ($patientId) {
        $where[] = 'patient_id = ?';
        $params[] = $patientId;
        $types .= 'i';
    }
    $sql = 'SELECT * FROM doctor_patient_notes WHERE '.implode(' AND ', $where).' ORDER BY updated_at DESC';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();
    $rows = [];
    while ($row = $res->fetch_assoc()) { $rows[] = $row; }
    echo json_encode(['success'=>true,'data'=>$rows]);
}

function handlePost($conn, $input) {
    $required = ['doctor_id','patient_id','title','note'];
    foreach ($required as $field) {
        if (!isset($input[$field]) || trim((string)$input[$field]) === '') {
            http_response_code(400);
            echo json_encode(['success'=>false,'message'=>"Field '$field' is required"]);
            return;
        }
    }

    $doctorId = intval($input['doctor_id']);
    $patientId = intval($input['patient_id']);
    $title = mb_substr(trim($input['title']), 0, 120);
    $note = trim($input['note']);

    $stmt = $conn->prepare('INSERT INTO doctor_patient_notes (doctor_id, patient_id, title, note) VALUES (?,?,?,?)');
    if (!$stmt) { http_response_code(500); echo json_encode(['success'=>false,'message'=>'Insert prepare failed: '.$conn->error]); return; }
    $stmt->bind_param('iiss', $doctorId, $patientId, $title, $note);
    if ($stmt->execute()) {
        echo json_encode(['success'=>true,'id'=>$conn->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['success'=>false,'message'=>'Failed to create note: '.$stmt->error]);
    }
}

function handlePut($conn, $input) {
    if (empty($input['id'])) {
        http_response_code(400);
        echo json_encode(['success'=>false,'message'=>'ID is required']);
        return;
    }
    $id = intval($input['id']);
    $fields = [];
    $params = [];
    $types = '';

    if (isset($input['title'])) {
        $fields[] = 'title = ?';
        $params[] = mb_substr(trim($input['title']), 0, 120);
        $types .= 's';
    }
    if (isset($input['note'])) {
        $fields[] = 'note = ?';
        $params[] = trim($input['note']);
        $types .= 's';
    }
    if (isset($input['patient_id'])) {
        $fields[] = 'patient_id = ?';
        $params[] = intval($input['patient_id']);
        $types .= 'i';
    }

    if (!$fields) {
        http_response_code(400);
        echo json_encode(['success'=>false,'message'=>'No fields to update']);
        return;
    }

    $params[] = $id;
    $types .= 'i';
    $sql = 'UPDATE doctor_patient_notes SET '.implode(', ', $fields).', updated_at = CURRENT_TIMESTAMP WHERE id = ?';
    $stmt = $conn->prepare($sql);
    if (!$stmt) { http_response_code(500); echo json_encode(['success'=>false,'message'=>'Update prepare failed: '.$conn->error]); return; }
    $stmt->bind_param($types, ...$params);
    if ($stmt->execute()) {
        echo json_encode(['success'=>true]);
    } else {
        http_response_code(500);
        echo json_encode(['success'=>false,'message'=>'Failed to update note: '.$stmt->error]);
    }
}

function handleDelete($conn, $input) {
    if (empty($input['id'])) {
        http_response_code(400);
        echo json_encode(['success'=>false,'message'=>'ID is required']);
        return;
    }
    $id = intval($input['id']);
    $stmt = $conn->prepare('DELETE FROM doctor_patient_notes WHERE id = ?');
    if (!$stmt) { http_response_code(500); echo json_encode(['success'=>false,'message'=>'Delete prepare failed: '.$conn->error]); return; }
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        echo json_encode(['success'=>true]);
    } else {
        http_response_code(500);
        echo json_encode(['success'=>false,'message'=>'Failed to delete note: '.$stmt->error]);
    }
}
