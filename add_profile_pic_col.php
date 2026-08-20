<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
// Add profile_pic column if it doesn't exist
try {
    $db->exec("ALTER TABLE students ADD COLUMN profile_pic VARCHAR(255) DEFAULT NULL");
    echo "Added profile_pic to students table.\n";
} catch (PDOException $e) {
    echo "Column likely already exists.\n";
}
