<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/home/about.php';
$c = file_get_contents($f);

// Remove the <h3> containing "Antigravity Developer"
$c = preg_replace(
    '/<h3[^>]*>Antigravity Developer<\/h3>/i',
    '',
    $c
);

file_put_contents($f, $c);
echo "Removed Antigravity Developer line.\n";
