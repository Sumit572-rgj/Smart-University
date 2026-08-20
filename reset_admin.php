<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

$pwd = password_hash('admin123', PASSWORD_DEFAULT);
$db->exec("UPDATE users SET password_hash = '$pwd' WHERE username = 'admin'");
echo "Admin password reset to: admin123";
