<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // Add updated_at if not exists
    $db->exec("ALTER TABLE outpass ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    echo "Added updated_at column.\n";
} catch (Exception $e) {
    echo "Column likely exists: " . $e->getMessage() . "\n";
}
