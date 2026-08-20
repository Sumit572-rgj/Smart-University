<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

try {
    $db->exec("ALTER TABLE fees 
        ADD COLUMN discount_amount DECIMAL(10,2) DEFAULT 0.00,
        ADD COLUMN fine_amount DECIMAL(10,2) DEFAULT 0.00
    ");
    echo "Fees table updated with discount and fine columns.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
