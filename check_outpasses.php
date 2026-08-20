<?php
require 'C:/xampp/htdocs/cit_ums/config/database.php';
require 'C:/xampp/htdocs/cit_ums/app/Core/Model.php';
$db = (new Model())->db;
$stmt = $db->query('SHOW COLUMNS FROM outpasses');
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($cols as $c) { echo $c['Field'] . "\n"; }
