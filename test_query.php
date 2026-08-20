<?php
require 'C:/xampp/htdocs/cit_ums/config/database.php';
require 'C:/xampp/htdocs/cit_ums/app/Core/Model.php';
$db = (new Model())->db;
$stmt = $db->prepare("SELECT s.id as student_id, s.first_name, ha.status as marked_status FROM students s LEFT JOIN hostel_attendance ha ON s.id = ha.student_id AND ha.attendance_date = ?");
$stmt->execute(['2026-08-19']);
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
