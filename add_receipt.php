<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/FeeController.php';
$c = file_get_contents($f);

// 1. Change the email button to point to the new receipt endpoint
$c = preg_replace(
    "/Mail::sendTemplate\(\\$fee\['email'\], 'Payment Receipt - CIT UMS', 'Payment Verified', \\$msg, 'View Dashboard', 'http:\/\/localhost\/cit_ums\/dashboard'\);/",
    "Mail::sendTemplate(\$fee['email'], 'Payment Receipt - CIT UMS', 'Payment Verified', \$msg, 'Download Receipt', 'http://localhost/cit_ums/fee/receipt?id=' . \$invoice_id);",
    $c
);

// 2. Add the receipt method to the end of FeeController
$receiptMethod = <<<'PHP'
    public function receipt() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }

        $invoice_id = $_GET['id'] ?? null;
        if (!$invoice_id) { die("Invoice ID required."); }

        $db = (new Model())->db;
        $stmt = $db->prepare("SELECT f.*, s.first_name, s.last_name, s.enrollment_no, s.course FROM fees f JOIN students s ON f.student_id = s.id WHERE f.id = ?");
        $stmt->execute([$invoice_id]);
        $fee = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$fee) { die("Invoice not found."); }

        // Security check: Only allow the student who owns the invoice, or an admin/warden to view it
        if ($user['role'] === 'student') {
            $stmt = $db->prepare("SELECT id FROM students WHERE user_id = ?");
            $stmt->execute([$user['id']]);
            $student_id = $stmt->fetchColumn();
            if ($fee['student_id'] != $student_id) {
                die("Unauthorized.");
            }
        }

        $this->view('fee/receipt', ['fee' => $fee, 'user' => $user]);
    }
}
PHP;

$c = preg_replace('/}\s*$/', "\n$receiptMethod\n", $c);
file_put_contents($f, $c);
echo "Receipt method and email link updated.\n";
