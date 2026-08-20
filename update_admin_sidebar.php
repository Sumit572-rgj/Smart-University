<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/dashboard/index.php';
$c = file_get_contents($f);

// Add to Admin sidebar menu
$adminPattern = '/(<a href="\/cit_ums\/event\/admin" class="nav-link">.*?Notice Board<\/a>)/s';
if (strpos($c, '<!-- Admin Room Maint -->') === false) {
    $c = preg_replace($adminPattern, "$1\n                <a href=\"/cit_ums/roommaintenance\" class=\"nav-link\"><!-- Admin Room Maint -->Room Maintenance</a>", $c);
}

file_put_contents($f, $c);
echo "Sidebar admin updated.";
