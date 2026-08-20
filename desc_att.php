<?php
$db = new PDO("mysql:host=localhost;dbname=cit_ums", "root", "");
print_r($db->query('DESCRIBE attendance')->fetchAll(PDO::FETCH_ASSOC));
