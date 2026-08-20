<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
$stmt = $db->query('SELECT id, qr_code FROM outpass WHERE qr_code IS NOT NULL LIMIT 5');
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
