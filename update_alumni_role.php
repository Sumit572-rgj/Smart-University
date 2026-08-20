<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

try {
    $db->exec("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'student', 'faculty', 'warden', 'security', 'alumni') NOT NULL");
    
    // Create a dummy alumni user for testing
    $pwd = password_hash('password123', PASSWORD_DEFAULT);
    $db->exec("INSERT IGNORE INTO users (username, password, email, role) VALUES ('ALUM01', '$pwd', 'alum01@cit.edu', 'alumni')");

    echo "Role updated and test alumni created.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
