<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

$user = $db->query("SELECT * FROM users WHERE username = 'admin'")->fetch(PDO::FETCH_ASSOC);

if (password_verify('admin123', $user['password_hash'])) {
    echo "Password verifies perfectly.";
} else {
    echo "Password verification failed.";
}
