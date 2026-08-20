<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $stmt = $db->prepare("INSERT INTO mod_roommaintenance (user_id, room_no, issue, status) VALUES (?, ?, ?, 'Pending')");
    $stmt->execute([1, '101', 'Test issue']);
    echo "Inserted successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
