<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
try {
    $stmt = $db->prepare("
            SELECT m.id, m.sender_id, m.subject, m.body, m.attachment_path, m.created_at, m.is_read, 
                   IF(m.subject LIKE '[BROADCAST]%', 'Multiple Recipients (Broadcast)', u.username) as receiver_name, 
                   u.role as receiver_role 
            FROM messages m 
            JOIN users u ON m.receiver_id = u.id 
            WHERE m.sender_id = 1 
            GROUP BY m.subject, m.body, m.created_at
            ORDER BY m.created_at DESC
        ");
    $stmt->execute();
    print_r($stmt->fetchAll());
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
