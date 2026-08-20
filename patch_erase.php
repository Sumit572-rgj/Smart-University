<?php
$f1 = 'C:/xampp/htdocs/cit_ums/app/Controllers/GateController.php';
$c1 = file_get_contents($f1);
$c1 = str_replace("status IN ('checked_out', 'checked_in')", "status = 'checked_out'", $c1);
file_put_contents($f1, $c1);

$f2 = 'C:/xampp/htdocs/cit_ums/app/Controllers/OutpassController.php';
$c2 = file_get_contents($f2);
$c2 = str_replace("status IN ('checked_out', 'checked_in')", "status = 'checked_out'", $c2);
file_put_contents($f2, $c2);

echo "Updated controllers to only erase after checkout.\n";
