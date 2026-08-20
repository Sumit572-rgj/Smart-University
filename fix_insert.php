<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/HostelController.php';
$c = file_get_contents($f);

$c = str_replace(
    'INSERT INTO hostel_maintenance (room_id, reported_by, description) VALUES (?, ?, ?)',
    'INSERT INTO hostel_maintenance (room_id, reported_by, issue_description) VALUES (?, ?, ?)',
    $c
);

file_put_contents($f, $c);
echo "Controller insert fixed.\n";
