<?php
// Fix the Controller
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/StudentController.php';
$c = file_get_contents($f);
$c = str_replace(
    "'last_name' => filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING),",
    "'last_name' => filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING),\n                    'dob' => filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING),",
    $c
);
file_put_contents($f, $c);

// Fix the user's specific student data
require_once 'C:/xampp/htdocs/cit_ums/config/database.php';
require_once 'C:/xampp/htdocs/cit_ums/app/Core/Model.php';
$db = (new Model())->db;
$db->exec("UPDATE students SET dob = '2009-08-02' WHERE enrollment_no = 's4'");

echo "Controller fixed and student s4 updated.\n";
