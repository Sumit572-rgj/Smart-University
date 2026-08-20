<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

try {
    // Add parent role if missing
    $db->exec("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'student', 'faculty', 'warden', 'security', 'alumni', 'parent') NOT NULL");
    
    // Parents table to link user account to a specific student
    $db->exec("CREATE TABLE IF NOT EXISTS parent_profiles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        student_id INT NOT NULL,
        relation VARCHAR(50) DEFAULT 'Parent',
        contact_number VARCHAR(20),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
    )");

    echo "Parent profiles table created successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
