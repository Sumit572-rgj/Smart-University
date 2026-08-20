<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
$tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
print_r($tables);
