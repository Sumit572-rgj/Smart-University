<?php
require_once 'config/database.php';
require_once 'app/Core/Model.php';

$db = (new Model())->db;

// Fix profile pics
$db->exec("UPDATE students SET profile_pic = REPLACE(profile_pic, '/cit_ums/public/uploads/', '/uploads/')");
$db->exec("UPDATE students SET profile_pic = REPLACE(profile_pic, '/cit_ums/uploads/', '/uploads/')");

// Fix documents
$db->exec("UPDATE student_documents SET file_path = REPLACE(file_path, '/cit_ums/public/uploads/', '/uploads/')");
$db->exec("UPDATE student_documents SET file_path = REPLACE(file_path, '/cit_ums/uploads/', '/uploads/')");

echo "Database image paths fixed successfully.";
