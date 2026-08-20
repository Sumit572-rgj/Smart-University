<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

try {
    $db->exec("ALTER TABLE library_books 
        ADD COLUMN resource_type ENUM('physical', 'digital', 'journal') DEFAULT 'physical',
        ADD COLUMN subject VARCHAR(100) DEFAULT NULL,
        ADD COLUMN digital_link VARCHAR(255) DEFAULT NULL
    ");
    echo "Columns added to library_books.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
