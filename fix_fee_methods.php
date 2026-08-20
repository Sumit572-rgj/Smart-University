<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/FeeController.php';
$c = file_get_contents($f);

$c = str_replace(
    '$feeModel->payInvoice($invoice_id, $reference_no)', 
    '$feeModel->markAsPaid($invoice_id, $reference_no)', 
    $c
);

$c = str_replace(
    '$data[\'invoices\'] = $feeModel->getStudentInvoices($student_id);', 
    '$data[\'invoices\'] = $feeModel->getInvoicesByStudentUserId($user[\'id\']);', 
    $c
);

file_put_contents($f, $c);
echo "Fixed method names.\n";
