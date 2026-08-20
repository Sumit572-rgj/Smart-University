<?php
require 'C:/xampp/htdocs/cit_ums/config/database.php';
require 'C:/xampp/htdocs/cit_ums/app/Core/Model.php';
$db = (new Model())->db;
$stmt = $db->query("SELECT enrollment_no, dob FROM students WHERE enrollment_no = 's4'");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
