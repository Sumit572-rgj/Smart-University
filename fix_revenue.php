<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/DashboardController.php';
$c = file_get_contents($f);

$c = str_replace(
    "['label' => 'Revenue Collected', 'value' => ',1' . number_format(\$feeCollected), 'link' => '#']",
    "['label' => 'Revenue Collected', 'value' => '₹' . number_format(\$feeCollected), 'link' => '#']",
    $c
);

file_put_contents($f, $c);
echo "Fixed revenue formatting.";
