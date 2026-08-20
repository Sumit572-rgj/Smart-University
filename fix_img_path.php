<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/home/about.php';
$c = file_get_contents($f);

$c = str_replace(
    '<img src="developer.jpg"', 
    '<img src="/cit_ums/public/developer.jpg"', 
    $c
);

file_put_contents($f, $c);
echo "Image path fixed.\n";
