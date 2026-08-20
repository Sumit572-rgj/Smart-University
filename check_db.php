<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
$res = $db->query('DESCRIBE hostel_maintenance')->fetchAll(PDO::FETCH_ASSOC);
print_r($res);
