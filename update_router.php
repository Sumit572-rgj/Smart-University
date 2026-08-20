<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Core/Router.php';
$c = file_get_contents($f);

$c = str_replace(
    "protected \$controller = 'DashboardController';", 
    "protected \$controller = 'HomeController';", 
    $c
);

file_put_contents($f, $c);
echo "Router updated to default to HomeController.";
