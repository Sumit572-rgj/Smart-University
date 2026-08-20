<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

try {
    $db->exec("ALTER TABLE library_issues ADD COLUMN fine_amount DECIMAL(10,2) DEFAULT 0.00");
    echo "Fine amount column added to library_issues.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
