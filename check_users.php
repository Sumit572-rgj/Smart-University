<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
print_r($db->query("SELECT username FROM users LIMIT 10")->fetchAll(PDO::FETCH_COLUMN));
