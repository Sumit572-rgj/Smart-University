<?php
$db = new PDO('mysql:host=localhost;dbname=cit_ums', 'root', '');
// Fix DB spaces
$db->query("UPDATE student_documents SET file_path = REPLACE(file_path, ' ', '_')");

// Fix Disk spaces
$dir = 'C:/xampp/htdocs/cit_ums/public/uploads/';
if (is_dir($dir)) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            if (strpos($file, ' ') !== false) {
                $new_file = str_replace(' ', '_', $file);
                rename($dir . $file, $dir . $new_file);
            }
        }
    }
}
echo "Fixed existing spaces.";
