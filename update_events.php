<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

try {
    $db->exec("ALTER TABLE events ADD COLUMN type ENUM('event', 'notice') DEFAULT 'event'");
    $db->exec("ALTER TABLE events ADD COLUMN visibility ENUM('all', 'students', 'faculty', 'warden') DEFAULT 'all'");
    $db->exec("ALTER TABLE events ADD COLUMN notify TINYINT(1) DEFAULT 0");
    echo "Events table updated successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
