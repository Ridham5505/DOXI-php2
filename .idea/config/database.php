<?php
// Database configuration
$host = 'localhost';
$dbname = 'doxi_healthcare';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Provide MySQLi connection for endpoints that expect it
function getDBConnection() {
    $host = isset($GLOBALS['host']) ? $GLOBALS['host'] : 'localhost';
    $dbname = isset($GLOBALS['dbname']) ? $GLOBALS['dbname'] : 'doxi_healthcare';
    $username = isset($GLOBALS['username']) ? $GLOBALS['username'] : 'root';
    $password = isset($GLOBALS['password']) ? $GLOBALS['password'] : '';
    
    $conn = @new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        error_log('MySQLi connection failed: ' . $conn->connect_error);
        return null;
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

// Function to create tables if they don't exist
function createTables($pdo) {
    // Unified users table (stores admins, doctors, and patients)
    $usersTable = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        role ENUM('admin','doctor','patient') NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        date_of_birth DATE NULL,
        gender ENUM('Female', 'Male', 'Other', 'Prefer not to say') NULL,
        address TEXT,
        specialty VARCHAR(100),
        license_number VARCHAR(100) UNIQUE,
        years_experience INT,
        practice_address TEXT,
        last_login TIMESTAMP NULL DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    // Appointments table
    $appointmentsTable = "
    CREATE TABLE IF NOT EXISTS appointments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        patient_id INT NOT NULL,
        doctor_id INT NOT NULL,
        appointment_date DATE NOT NULL,
        appointment_time TIME NOT NULL,
        status ENUM('scheduled', 'completed', 'cancelled', 'rescheduled') DEFAULT 'scheduled',
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    
    // Medical records table
    $medicalRecordsTable = "
    CREATE TABLE IF NOT EXISTS medical_records (
        id INT AUTO_INCREMENT PRIMARY KEY,
        patient_id INT NOT NULL,
        doctor_id INT NOT NULL,
        record_type ENUM('consultation', 'test_result', 'prescription', 'diagnosis') NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        file_path VARCHAR(500),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    
    try {
        $pdo->exec($usersTable);
        // Ensure last_login exists for tracking patient logins
        try { $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS last_login TIMESTAMP NULL DEFAULT NULL"); } catch (PDOException $e) { /* ignore if unsupported or already exists */ }
        $pdo->exec($appointmentsTable);
        $pdo->exec($medicalRecordsTable);
        
        // Insert default admin if not exists
        $adminCheck = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $adminCheck->execute(['admin@doxi.com']);
        
        if ($adminCheck->fetchColumn() == 0) {
            $insertAdmin = $pdo->prepare("INSERT INTO users (role, email, password, first_name, last_name) VALUES (?, ?, ?, ?, ?)");
            $insertAdmin->execute([
                'admin',
                'admin@doxi.com',
                password_hash('admin123', PASSWORD_DEFAULT),
                'System',
                'Administrator'
            ]);
        }
        
        return true;
    } catch(PDOException $e) {
        error_log("Error creating tables: " . $e->getMessage());
        return false;
    }
}

// Initialize database
createTables($pdo);
?>
