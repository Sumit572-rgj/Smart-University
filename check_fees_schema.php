<?php
$db = new PDO("mysql:host=localhost;dbname=cit_ums", "root", "");
$stmt = $db->query("DESCRIBE fees");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
