<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
$stmt = $db->query('DESCRIBE students');
print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
