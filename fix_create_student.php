<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Models/Student.php';
$c = file_get_contents($f);

$oldCode = <<<PHP
            // Insert Student
            \$stmtStudent = \$this->db->prepare("
                INSERT INTO students (user_id, enrollment_no, first_name, last_name, department, batch, phone, admission_status, current_semester, cgpa) 
                VALUES (:user_id, :enrollment_no, :first_name, :last_name, :department, :batch, :phone, :admission_status, :current_semester, :cgpa)
            ");
PHP;

$newCode = <<<PHP
            // Insert Student
            \$stmtStudent = \$this->db->prepare("
                INSERT INTO students (user_id, enrollment_no, first_name, last_name, department, batch, phone, admission_status, current_semester, cgpa, section) 
                VALUES (:user_id, :enrollment_no, :first_name, :last_name, :department, :batch, :phone, :admission_status, :current_semester, :cgpa, :section)
            ");
PHP;

$c = str_replace($oldCode, $newCode, $c);
file_put_contents($f, $c);
echo "createStudent fixed.";
