<?php
$file = 'public/css/style.css';
$content = file_get_contents($file);
$content = str_replace("\0", "", $content); // Strip null bytes
file_put_contents($file, $content);
echo "Fixed style.css encoding.";
