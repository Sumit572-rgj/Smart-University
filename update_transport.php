<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

try {
    // Add vehicle & driver tracking, and GPS fields
    $db->exec("ALTER TABLE transport_routes ADD COLUMN license_number VARCHAR(100) NULL");
    $db->exec("ALTER TABLE transport_routes ADD COLUMN maintenance_date DATE NULL");
    $db->exec("ALTER TABLE transport_routes ADD COLUMN lat DECIMAL(10, 8) NULL DEFAULT '11.0168'");
    $db->exec("ALTER TABLE transport_routes ADD COLUMN lng DECIMAL(11, 8) NULL DEFAULT '76.9558'"); // Coimbatore defaults
    $db->exec("ALTER TABLE transport_routes ADD COLUMN last_updated TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP");

    echo "Transport schema updated.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
