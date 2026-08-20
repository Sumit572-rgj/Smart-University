<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

try {
    $db->exec("CREATE TABLE IF NOT EXISTS notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        type VARCHAR(50) DEFAULT 'general',
        is_read TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    // Since users want improvements in exams, let's make sure exams has subject/date
    $db->exec("ALTER TABLE exams ADD COLUMN subject VARCHAR(100) DEFAULT NULL");
    
    // Attendance by subject
    $db->exec("ALTER TABLE attendance ADD COLUMN subject VARCHAR(100) DEFAULT NULL");

    echo "Notifications table and schema upgrades completed successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
