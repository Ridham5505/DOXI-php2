<?php
// Database setup script - run this once to create the database and tables
require_once 'config/database.php';

echo "<h2>DOXI Database Setup</h2>";

try {
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS doxi");
echo "<p>✅ Database 'doxi' created/verified</p>";

// Use the database
$pdo->exec("USE doxi");
    
    // Create tables
    $result = createTables($pdo);
    
    if ($result) {
        echo "<p>✅ All tables created successfully</p>";
        echo "<p>✅ Default admin account created (admin@doxi.com / admin123)</p>";
        
        // Show table structure
        echo "<h3>Database Structure:</h3>";
        $tables = ['patients', 'doctors', 'admins', 'appointments', 'medical_records', 'user_sessions'];
        
        foreach ($tables as $table) {
            $stmt = $pdo->query("DESCRIBE $table");
            $columns = $stmt->fetchAll();
            
            echo "<h4>Table: $table</h4>";
            echo "<ul>";
            foreach ($columns as $column) {
                echo "<li>{$column['Field']} - {$column['Type']}</li>";
            }
            echo "</ul>";
        }
        
        echo "<h3>Next Steps:</h3>";
        echo "<ul>";
        echo "<li>✅ Database is ready for use</li>";
        echo "<li>✅ API endpoints are available at /api/register.php and /api/login.php</li>";
        echo "<li>✅ Frontend forms will now save to database</li>";
        echo "</ul>";
        
    } else {
        echo "<p>❌ Error creating tables</p>";
    }
    
} catch (PDOException $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>
