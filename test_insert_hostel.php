<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    $stmt = $db->prepare("INSERT INTO hostel_maintenance (room_id, reported_by, issue_description) VALUES (?, ?, ?)");
    $stmt->execute([1, 1, 'Test issue']);
    echo "Inserted into hostel_maintenance successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
