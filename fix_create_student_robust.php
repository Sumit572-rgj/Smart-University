<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Models/Student.php';
$c = file_get_contents($f);

// Use a more robust replacement strategy
$pattern = '/INSERT INTO students \(([^)]+)\)\s+VALUES \(([^)]+)\)/s';
$replacement = 'INSERT INTO students (user_id, enrollment_no, first_name, last_name, department, batch, phone, admission_status, current_semester, cgpa, section) VALUES (:user_id, :enrollment_no, :first_name, :last_name, :department, :batch, :phone, :admission_status, :current_semester, :cgpa, :section)';

$c = preg_replace($pattern, $replacement, $c, 1);
file_put_contents($f, $c);
echo "createStudent fixed correctly.";
