<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Models/Student.php';
$c = file_get_contents($f);

$c = str_replace('ORDER BY s.created_at DESC', 'ORDER BY s.enrollment_no DESC', $c);

file_put_contents($f, $c);
echo "Fixed order by clause in getStudentsByDepartment.";
