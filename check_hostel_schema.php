<?php
require 'C:/xampp/htdocs/cit_ums/config/database.php';
require 'C:/xampp/htdocs/cit_ums/app/Core/Model.php';
$db = (new Model())->db;
$stmt = $db->query('SHOW CREATE TABLE hostel_attendance');
print_r($stmt->fetch(PDO::FETCH_ASSOC));
