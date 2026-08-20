<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
$stmt = $db->query('SELECT id, attachment_path FROM messages WHERE attachment_path IS NOT NULL ORDER BY id DESC LIMIT 5');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
