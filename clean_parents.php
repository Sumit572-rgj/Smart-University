<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

$db->exec("DELETE FROM users WHERE role = 'parent' AND id NOT IN (SELECT user_id FROM parent_profiles)");
echo "Cleaned up orphaned parent accounts.";
