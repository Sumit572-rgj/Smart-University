<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
print_r($db->query("SELECT * FROM users WHERE role = 'admin'")->fetchAll(PDO::FETCH_ASSOC));
