<?php
$fStudent = 'C:/xampp/htdocs/cit_ums/app/Views/fee/student.php';
$c = file_get_contents($fStudent);
$c = str_replace('action="/cit_ums/fee"', 'action="/cit_ums/fee/student"', $c);
file_put_contents($fStudent, $c);

$fAdmin = 'C:/xampp/htdocs/cit_ums/app/Views/fee/admin.php';
if (file_exists($fAdmin)) {
    $c = file_get_contents($fAdmin);
    $c = str_replace('action="/cit_ums/fee"', 'action="/cit_ums/fee/admin"', $c);
    file_put_contents($fAdmin, $c);
}

echo "Fixed form routing.\n";
