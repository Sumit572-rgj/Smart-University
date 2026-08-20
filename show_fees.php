<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
$columns = $db->query("DESCRIBE fees")->fetchAll(PDO::FETCH_ASSOC);
print_r($columns);
