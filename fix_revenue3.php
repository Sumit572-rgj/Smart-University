<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/DashboardController.php';
$lines = file($f);
foreach ($lines as &$line) {
    if (strpos($line, "'Revenue Collected'") !== false) {
        $line = "                ['label' => 'Revenue Collected', 'value' => 'Rs. ' . number_format(\$feeCollected), 'link' => '#']\n";
    }
}
file_put_contents($f, implode("", $lines));
echo "Fixed!\n";
