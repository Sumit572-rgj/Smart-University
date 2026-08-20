<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/StudentController.php';
$c = file_get_contents($f);
$c = str_replace("'/cit_ums/public/uploads/' . \$filename", "'/cit_ums/uploads/' . \$filename", $c);
file_put_contents($f, $c);

$f2 = 'C:/xampp/htdocs/cit_ums/app/Controllers/MessageController.php';
$c2 = file_get_contents($f2);
$c2 = str_replace('"/cit_ums/public/uploads/" . $filename', '"/cit_ums/uploads/" . $filename', $c2);
file_put_contents($f2, $c2);

// Fix DB entries
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
$db->query("UPDATE student_documents SET file_path = REPLACE(file_path, '/cit_ums/public/uploads/', '/cit_ums/uploads/')");
$db->query("UPDATE messages SET attachment_path = REPLACE(attachment_path, '/cit_ums/public/uploads/', '/cit_ums/uploads/')");

echo "Fixed URL paths.";
