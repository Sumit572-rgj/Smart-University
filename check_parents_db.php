<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

$users = $db->query("SELECT * FROM users WHERE role = 'parent'")->fetchAll(PDO::FETCH_ASSOC);
print_r($users);
