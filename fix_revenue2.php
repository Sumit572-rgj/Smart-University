<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/DashboardController.php';
$c = file_get_contents($f);

// Find the line that has Revenue Collected and replace it entirely using Regex to avoid encoding matching errors
$pattern = "/\['label' => 'Revenue Collected',\s*'value'\s*=>\s*.*?\.\s*number_format\(\\$feeCollected\),\s*'link'\s*=>\s*'#'\]/";
$replacement = "['label' => 'Revenue Collected', 'value' => 'Rs. ' . number_format(\$feeCollected), 'link' => '#']";

$c = preg_replace($pattern, $replacement, $c);

file_put_contents($f, $c);
echo "Fixed revenue to Rs.\n";
