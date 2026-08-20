<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
print_r($db->query('SELECT * FROM parent_profiles')->fetchAll(PDO::FETCH_ASSOC));
