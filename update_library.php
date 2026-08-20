<?php
require_once 'app/Core/Database.php';

$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

try {
    $db->exec("ALTER TABLE library_books ADD COLUMN resource_type ENUM('book', 'journal', 'digital') DEFAULT 'book'");
    $db->exec("ALTER TABLE library_books ADD COLUMN subject VARCHAR(100) NULL");
    $db->exec("ALTER TABLE library_books ADD COLUMN digital_link VARCHAR(255) NULL");
    $db->exec("ALTER TABLE library_issues ADD COLUMN fine_amount DECIMAL(10, 2) DEFAULT 0.00");
    echo "Library tables updated successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
