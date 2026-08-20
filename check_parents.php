<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    print_r($db->query('SELECT * FROM parent_profiles')->fetchAll(PDO::FETCH_ASSOC));
    print_r($db->query('SELECT id, username, role FROM users WHERE role="parent"')->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo $e->getMessage();
}
