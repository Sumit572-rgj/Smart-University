<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/DashboardController.php';
$c = file_get_contents($f);

// For Admin Revenue
$c = str_replace(
    "\$feeDb = \$db->query(\"SHOW TABLES LIKE 'fee_payments'\")->rowCount() > 0;",
    "\$feeDb = \$db->query(\"SHOW TABLES LIKE 'fees'\")->rowCount() > 0;",
    $c
);
$c = str_replace(
    "\$feeCollected = \$db->query(\"SELECT SUM(amount) FROM fee_payments WHERE status = 'SUCCESS'\")->fetchColumn();",
    "\$feeCollected = \$db->query(\"SELECT SUM(amount) FROM fees WHERE status IN ('paid', 'verified')\")->fetchColumn();",
    $c
);

// For Student Pending Fee
$c = str_replace(
    "\$totalPaid = \$db->query(\"SELECT SUM(amount) FROM fee_payments WHERE student_id = \" . (int)\$student_id . \" AND status = 'SUCCESS'\")->fetchColumn();\n                \$feePending = max(0, 150000 - (int)\$totalPaid); // Assuming 1.5L fee",
    "\$feePending = \$db->query(\"SELECT SUM(amount) FROM fees WHERE student_id = \" . (int)\$student_id . \" AND status IN ('pending', 'overdue')\")->fetchColumn();\n                if (!\$feePending) \$feePending = 0;",
    $c
);

file_put_contents($f, $c);
echo "Fixed fee calculation queries in DashboardController.\n";
