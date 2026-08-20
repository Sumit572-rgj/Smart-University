<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/MessageController.php';
$c = file_get_contents($f);

// Fix the target directory
$c = str_replace('$target_dir = "public/uploads/";', '$target_dir = "uploads/";', $c);

file_put_contents($f, $c);

// Move the incorrectly uploaded files to the correct directory
$wrongDir = 'C:/xampp/htdocs/cit_ums/public/public/uploads';
$correctDir = 'C:/xampp/htdocs/cit_ums/public/uploads';

if (is_dir($wrongDir)) {
    if (!is_dir($correctDir)) {
        mkdir($correctDir, 0777, true);
    }
    
    $files = scandir($wrongDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            rename($wrongDir . '/' . $file, $correctDir . '/' . $file);
        }
    }
    // Clean up the bad directories
    rmdir($wrongDir);
    rmdir('C:/xampp/htdocs/cit_ums/public/public');
}

echo "Upload directory fixed and files moved.";
