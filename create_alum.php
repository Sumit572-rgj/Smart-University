<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

$pwd = password_hash('password123', PASSWORD_DEFAULT);
$db->exec("INSERT IGNORE INTO users (username, password_hash, email, role) VALUES ('ALUM01', '$pwd', 'alum01@cit.edu', 'alumni')");
echo "Alumni created.";
