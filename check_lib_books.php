<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
print_r($db->query('SELECT * FROM library_books')->fetchAll(PDO::FETCH_ASSOC));
