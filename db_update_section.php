<?php
$db = new PDO("mysql:host=localhost;dbname=cit_ums", "root", "");
try {
    $db->exec("ALTER TABLE students ADD COLUMN section VARCHAR(10) DEFAULT NULL");
    echo "Added section column successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
