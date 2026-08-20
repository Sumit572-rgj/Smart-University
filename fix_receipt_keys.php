<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/fee/receipt.php';
$c = file_get_contents($f);

// Replace discount and fine with discount_amount and fine_amount
$c = str_replace('$fee[\'discount\']', '$fee[\'discount_amount\']', $c);
$c = str_replace('$fee[\'fine\']', '$fee[\'fine_amount\']', $c);

file_put_contents($f, $c);
echo "Fixed array keys in receipt.\n";
