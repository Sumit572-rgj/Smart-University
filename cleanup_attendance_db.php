<?php
$db = new PDO("mysql:host=localhost;dbname=cit_ums", "root", "");

$db->exec("
    DELETE t1 FROM attendance t1
    INNER JOIN attendance t2 
    WHERE 
        t1.id < t2.id AND 
        t1.student_id = t2.student_id AND 
        t1.course_code = t2.course_code AND 
        t1.date = t2.date
");

echo "Duplicate attendance records purged.";
