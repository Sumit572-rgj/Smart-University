<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

try {
    $db->exec("CREATE TABLE IF NOT EXISTS hostel_assets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        room_id INT NOT NULL,
        asset_name VARCHAR(100) NOT NULL,
        condition_status ENUM('good', 'needs_repair', 'damaged') DEFAULT 'good',
        last_checked DATE,
        FOREIGN KEY (room_id) REFERENCES hostel_rooms(id) ON DELETE CASCADE
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS hostel_maintenance (
        id INT AUTO_INCREMENT PRIMARY KEY,
        room_id INT NOT NULL,
        reported_by INT NOT NULL,
        issue_description TEXT NOT NULL,
        status ENUM('pending', 'in_progress', 'resolved') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (room_id) REFERENCES hostel_rooms(id) ON DELETE CASCADE,
        FOREIGN KEY (reported_by) REFERENCES users(id) ON DELETE CASCADE
    )");

    echo "Hostel Assets and Maintenance tables created successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
