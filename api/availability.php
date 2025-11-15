<?php
require_once '../config/database.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$conn = getDBConnection();
if (!$conn) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB connection failed']);
    exit();
}

if (!defined('ADMIN_ALERT_USER_ID')) {
    define('ADMIN_ALERT_USER_ID', 0);
}

$conn->query("CREATE TABLE IF NOT EXISTS doctor_availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    availability_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status ENUM('available','unavailable') NOT NULL DEFAULT 'available',
    notes VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX(doctor_id),
    INDEX(availability_date),
    INDEX(status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

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
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
} finally {
    $conn->close();
}

function sanitizeStatus($status)
{
    $status = strtolower(trim($status ?? 'available'));
    return in_array($status, ['available', 'unavailable'], true) ? $status : 'available';
}

function validatePayload($data, $requireAll = true)
{
    $errors = [];
    $fields = ['doctor_id', 'availability_date', 'start_time', 'end_time'];
    foreach ($fields as $field) {
        if ($requireAll && empty($data[$field])) {
            $errors[] = "Field '$field' is required.";
        }
    }
    if (!empty($data['start_time']) && !empty($data['end_time'])) {
        $start = strtotime($data['start_time']);
        $end = strtotime($data['end_time']);
        if ($start === false || $end === false) {
            $errors[] = 'Invalid time format.';
        } elseif ($start >= $end) {
            $errors[] = 'End time must be after start time.';
        }
    }
    return $errors;
}

function timesOverlap($startA, $endA, $startB, $endB)
{
    return ($startA < $endB) && ($startB < $endA);
}

function hasOverlap($conn, $doctorId, $date, $startTime, $endTime, $excludeId = null)
{
    $sql = "SELECT id, start_time, end_time FROM doctor_availability
            WHERE doctor_id = ? AND availability_date = ?";
    if ($excludeId) {
        $sql .= " AND id != ?";
    }
    $stmt = $conn->prepare($sql);
    if ($excludeId) {
        $stmt->bind_param('isi', $doctorId, $date, $excludeId);
    } else {
        $stmt->bind_param('is', $doctorId, $date);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $start = strtotime($startTime);
    $end = strtotime($endTime);
    while ($row = $result->fetch_assoc()) {
        if (timesOverlap($start, $end, strtotime($row['start_time']), strtotime($row['end_time']))) {
            return true;
        }
    }
    return false;
}

function getApptColumns($conn){
    $dateCol = columnExists($conn,'appointments','appt_date') ? 'appt_date' : (columnExists($conn,'appointments','date') ? 'date' : (columnExists($conn,'appointments','appointment_date') ? 'appointment_date' : 'appt_date'));
    $timeCol = columnExists($conn,'appointments','appt_time') ? 'appt_time' : (columnExists($conn,'appointments','time') ? 'time' : (columnExists($conn,'appointments','appointment_time') ? 'appointment_time' : 'appt_time'));
    return [$dateCol, $timeCol];
}

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

function cancelConflictingAppointments($conn, $doctorId, $date, $startTime, $endTime, $excludeAppointmentId = null)
{
    list($DATE_COL, $TIME_COL) = getApptColumns($conn);
    $sql = "SELECT id, patient_id, $TIME_COL AS appt_time, status FROM appointments
            WHERE doctor_id = ? AND $DATE_COL = ? AND status NOT IN ('cancelled', 'completed', 'rescheduled')";
    $stmt = $conn->prepare($sql);
    if (!$stmt) { return []; }
    $stmt->bind_param('is', $doctorId, $date);
    $stmt->execute();
    $result = $stmt->get_result();
    $affected = [];
    while ($row = $result->fetch_assoc()){
        if ($excludeAppointmentId && intval($row['id']) === intval($excludeAppointmentId)) { continue; }
        $appointmentMinutes = strtotime(substr($row['appt_time'],0,5));
        $rangeStart = strtotime($startTime);
        $rangeEnd = strtotime($endTime);
        if ($appointmentMinutes >= $rangeStart && $appointmentMinutes < $rangeEnd){
            $affected[] = $row;
        }
    }
    if (!$affected){ return []; }
    $cancelStmt = $conn->prepare("UPDATE appointments SET status = 'rescheduled', updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $notifStmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, link, is_read) VALUES (?, ?, ?, ?, 0)");
    $adminNotifStmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, link, is_read) VALUES (?, ?, ?, ?, 0)");
    foreach ($affected as $appt){
        $cancelStmt->bind_param('i', $appt['id']);
        $cancelStmt->execute();
        $displayTime = substr($appt['appt_time'], 0, 5);
        $message = "Your appointment on {$date} at {$displayTime} was cancelled because the doctor is no longer available at that time. Please reschedule your visit.";
        $link = 'book-appointment.php';
        if ($notifStmt){
            $title = 'Appointment Cancelled';
            $notifStmt->bind_param('isss', $appt['patient_id'], $title, $message, $link);
            $notifStmt->execute();
        }
        if ($adminNotifStmt){
            $adminUser = ADMIN_ALERT_USER_ID;
            $adminTitle = 'Doctor Unavailability Alert';
            $adminMessage = "Appointment #{$appt['id']} for patient #{$appt['patient_id']} with doctor #{$doctorId} on {$date} at {$displayTime} was auto-marked as rescheduled.";
            $adminLink = 'admin-dashboard.php#appointments';
            $adminNotifStmt->bind_param('isss', $adminUser, $adminTitle, $adminMessage, $adminLink);
            $adminNotifStmt->execute();
        }
    }
    return $affected;
}

function handleGet($conn)
{
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $doctorId = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;
    $date = $_GET['date'] ?? '';

    if ($id) {
        $stmt = $conn->prepare("SELECT * FROM doctor_availability WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        if (!$res) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Availability entry not found']);
            return;
        }
        echo json_encode(['success' => true, 'data' => $res]);
        return;
    }

    $where = [];
    $params = [];
    $types = '';
    if ($doctorId) {
        $where[] = 'doctor_id = ?';
        $params[] = $doctorId;
        $types .= 'i';
    }
    if ($date) {
        $where[] = 'availability_date = ?';
        $params[] = $date;
        $types .= 's';
    }
    $sql = "SELECT * FROM doctor_availability";
    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= ' ORDER BY availability_date ASC, start_time ASC';
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Query prepare failed: ' . $conn->error]);
        return;
    }
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
    echo json_encode(['success' => true, 'data' => $rows]);
}

function handlePost($conn, $input)
{
    $errors = validatePayload($input, true);
    if ($errors) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
        return;
    }
    $doctorId = intval($input['doctor_id']);
    $date = $input['availability_date'];
    $start = $input['start_time'];
    $end = $input['end_time'];
    $status = sanitizeStatus($input['status'] ?? 'available');
    $notes = $input['notes'] ?? null;

    if (hasOverlap($conn, $doctorId, $date, $start, $end)) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'This availability overlaps with an existing entry.']);
        return;
    }

    $stmt = $conn->prepare("INSERT INTO doctor_availability (doctor_id, availability_date, start_time, end_time, status, notes) VALUES (?,?,?,?,?,?)");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Insert prepare failed: ' . $conn->error]);
        return;
    }
    $stmt->bind_param('isssss', $doctorId, $date, $start, $end, $status, $notes);
    if ($stmt->execute()) {
        if ($status === 'unavailable'){
            cancelConflictingAppointments($conn, $doctorId, $date, $start, $end);
        }
        echo json_encode(['success' => true, 'id' => $conn->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Create failed: ' . $stmt->error]);
    }
}

function handlePut($conn, $input)
{
    if (empty($input['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Availability ID is required']);
        return;
    }
    $id = intval($input['id']);
    $errors = validatePayload($input, false);
    if ($errors) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
        return;
    }

    $fields = [];
    $params = [];
    $types = '';

    $possible = [
        'doctor_id' => 'i',
        'availability_date' => 's',
        'start_time' => 's',
        'end_time' => 's',
        'status' => 's',
        'notes' => 's'
    ];

    foreach ($possible as $field => $type) {
        if (array_key_exists($field, $input) && $input[$field] !== null && $input[$field] !== '') {
            $value = $field === 'status' ? sanitizeStatus($input[$field]) : $input[$field];
            $fields[] = "$field = ?";
            $params[] = $field === 'doctor_id' ? intval($value) : $value;
            $types .= $type;
        }
    }

    if (!$fields) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No fields to update']);
        return;
    }

    $doctorId = isset($input['doctor_id']) ? intval($input['doctor_id']) : null;
    $date = $input['availability_date'] ?? null;
    $start = $input['start_time'] ?? null;
    $end = $input['end_time'] ?? null;
    $status = isset($input['status']) ? sanitizeStatus($input['status']) : null;

    if ($doctorId && $date && $start && $end && hasOverlap($conn, $doctorId, $date, $start, $end, $id)) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'This availability overlaps with an existing entry.']);
        return;
    }

    $params[] = $id;
    $types .= 'i';
    $sql = 'UPDATE doctor_availability SET ' . implode(', ', $fields) . ', updated_at = CURRENT_TIMESTAMP WHERE id = ?';
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Update prepare failed: ' . $conn->error]);
        return;
    }
    $stmt->bind_param($types, ...$params);
    if ($stmt->execute()) {
        if ($status === 'unavailable' && $doctorId && $date && $start && $end){
            cancelConflictingAppointments($conn, $doctorId, $date, $start, $end);
        }
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
    }
}

function handleDelete($conn, $input)
{
    if (empty($input['id'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Availability ID is required']);
        return;
    }
    $id = intval($input['id']);
    $stmt = $conn->prepare('DELETE FROM doctor_availability WHERE id = ?');
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Delete failed: ' . $stmt->error]);
    }
}
?>


