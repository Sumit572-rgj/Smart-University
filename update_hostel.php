<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');

$db->exec("CREATE TABLE IF NOT EXISTS hostel_assets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL,
    room_id INT NULL,
    status ENUM('good', 'needs_repair', 'broken') DEFAULT 'good',
    FOREIGN KEY (room_id) REFERENCES hostel_rooms(id) ON DELETE SET NULL
)");

$db->exec("CREATE TABLE IF NOT EXISTS hostel_maintenance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT NOT NULL,
    student_id INT NULL,
    issue_type ENUM('electrical', 'plumbing', 'furniture', 'cleaning', 'other') NOT NULL,
    description TEXT NOT NULL,
    status ENUM('pending', 'in_progress', 'resolved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES hostel_rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL
)");

echo "Database updated for hostel facilities.";
