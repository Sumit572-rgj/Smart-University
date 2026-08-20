<?php
// Fix FeeController.php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/FeeController.php';
$c = file_get_contents($f);
$c = str_replace('s.course', 's.department', $c);
file_put_contents($f, $c);

// Fix receipt.php
$f2 = 'C:/xampp/htdocs/cit_ums/app/Views/fee/receipt.php';
$c2 = file_get_contents($f2);
$c2 = str_replace('$fee[\'course\']', '$fee[\'department\']', $c2);
$c2 = str_replace('Course:', 'Department:', $c2);
file_put_contents($f2, $c2);

echo "Fixed course column error.\n";
