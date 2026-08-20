<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

try {
    // Add parent role
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

    // Let's create a test parent account for student 1
    $pwd = password_hash('password123', PASSWORD_DEFAULT);
    $db->exec("INSERT IGNORE INTO users (username, password_hash, email, role) VALUES ('PARENT01', '$pwd', 'parent@cit.edu', 'parent')");
    $parent_user_id = $db->query("SELECT id FROM users WHERE username = 'PARENT01'")->fetchColumn();
    $student_id = $db->query("SELECT id FROM students LIMIT 1")->fetchColumn();
    
    if ($student_id && $parent_user_id) {
        $db->exec("INSERT IGNORE INTO parent_profiles (user_id, student_id, relation, contact_number) VALUES ($parent_user_id, $student_id, 'Father', '9876543210')");
    }

    echo "Parent schema updated.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
