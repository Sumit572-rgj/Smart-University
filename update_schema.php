<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

try {
    $db->exec("ALTER TABLE students ADD COLUMN admission_status ENUM('applied', 'admitted', 'enrolled', 'graduated', 'withdrawn') DEFAULT 'admitted'");
} catch(Exception $e) {}

try {
    $db->exec("ALTER TABLE students ADD COLUMN current_semester INT DEFAULT 1");
} catch(Exception $e) {}

try {
    $db->exec("ALTER TABLE students ADD COLUMN cgpa DECIMAL(4,2) DEFAULT 0.00");
} catch(Exception $e) {}

$db->exec("CREATE TABLE IF NOT EXISTS student_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    document_name VARCHAR(255) NOT NULL,
    document_type ENUM('id_proof', 'certificate', 'transcript', 'other') DEFAULT 'other',
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
)");

echo 'Database updated.';
