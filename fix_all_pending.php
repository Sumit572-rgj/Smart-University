<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

try {
    // Messaging
    $db->exec("ALTER TABLE messages ADD COLUMN attachment_path VARCHAR(255) DEFAULT NULL");
    
    $db->exec("CREATE TABLE IF NOT EXISTS forums (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS forum_posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        forum_id INT NOT NULL,
        user_id INT NOT NULL,
        content TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (forum_id) REFERENCES forums(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    // Transport
    $db->exec("ALTER TABLE transport_routes ADD COLUMN driver_license VARCHAR(50) DEFAULT NULL");
    $db->exec("ALTER TABLE transport_routes ADD COLUMN next_maintenance DATE DEFAULT NULL");
    $db->exec("ALTER TABLE transport_routes ADD COLUMN gps_lat DECIMAL(10,8) DEFAULT NULL");
    $db->exec("ALTER TABLE transport_routes ADD COLUMN gps_lng DECIMAL(11,8) DEFAULT NULL");
    
    // Alumni
    $db->exec("CREATE TABLE IF NOT EXISTS alumni_donations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        alumni_id INT NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        purpose VARCHAR(100),
        donated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (alumni_id) REFERENCES alumni_profiles(id) ON DELETE CASCADE
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS alumni_jobs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        alumni_id INT NOT NULL,
        company VARCHAR(100),
        position VARCHAR(100),
        description TEXT,
        link VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (alumni_id) REFERENCES alumni_profiles(id) ON DELETE CASCADE
    )");

    echo "All pending schema updates checked/fixed.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
