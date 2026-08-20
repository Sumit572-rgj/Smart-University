<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/GateController.php';
$c = file_get_contents($f);

$oldCode = "SELECT o.*, s.first_name, s.last_name, s.enrollment_no, s.department";
$newCode = "SELECT o.*, s.first_name, s.last_name, s.enrollment_no, s.department, s.profile_pic";

$c = str_replace($oldCode, $newCode, $c);
file_put_contents($f, $c);
echo "GateController updated to fetch profile_pic.";
