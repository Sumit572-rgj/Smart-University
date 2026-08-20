<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

try {
    $db->exec("ALTER TABLE students 
        ADD COLUMN admission_status ENUM('applied', 'admitted', 'enrolled', 'graduated', 'withdrawn') DEFAULT 'admitted',
        ADD COLUMN current_semester INT DEFAULT 1,
        ADD COLUMN cgpa DECIMAL(4,2) DEFAULT 0.00
    ");
    echo "Columns added to students table successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
