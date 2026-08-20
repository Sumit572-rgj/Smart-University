<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/FeeController.php';
$c = file_get_contents($f);

// 1. Fix the str_replace
$oldStr = "Mail::sendTemplate(\$fee['email'], 'Payment Receipt - CIT UMS', 'Payment Verified', \$msg, 'View Dashboard', 'http://localhost/cit_ums/dashboard');";
$newStr = "Mail::sendTemplate(\$fee['email'], 'Payment Receipt - CIT UMS', 'Payment Verified', \$msg, 'Download Receipt', 'http://localhost/cit_ums/fee/receipt?id=' . \$invoice_id);";

$c = str_replace($oldStr, $newStr, $c);

// If receipt method doesn't exist, append it
if (strpos($c, 'public function receipt()') === false) {
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
    $c = preg_replace('/\}\s*$/', "\n$receiptMethod\n", $c);
}

file_put_contents($f, $c);
echo "Receipt method fixed.\n";
