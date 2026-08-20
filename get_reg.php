<?php
require 'C:/xampp/htdocs/cit_ums/config/database.php';
require 'C:/xampp/htdocs/cit_ums/app/Core/Model.php';
$db = (new Model())->db;
$stmt = $db->query('SELECT enrollment_no FROM students WHERE id = 2');
echo $stmt->fetchColumn();
