<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
$res = $db->query('DESCRIBE outpass')->fetchAll(PDO::FETCH_ASSOC);
print_r($res);
