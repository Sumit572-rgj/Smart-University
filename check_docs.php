<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
print_r($db->query('SELECT * FROM student_documents')->fetchAll(PDO::FETCH_ASSOC));
