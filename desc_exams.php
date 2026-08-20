<?php
$host = 'localhost';
$dbname = 'cit_ums';
$username = 'root';
$password = '';
$db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
print_r($db->query('DESCRIBE exam_results')->fetchAll(PDO::FETCH_ASSOC));
print_r($db->query('DESCRIBE fees')->fetchAll(PDO::FETCH_ASSOC));
