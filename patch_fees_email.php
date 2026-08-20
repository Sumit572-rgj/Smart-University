<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/FeeController.php';
$c = file_get_contents($f);

// 1. Hook for 'verify' (Payment Receipt)
$verifyOld = <<<'PHP'
                } else if (isset($_POST['action']) && $_POST['action'] == 'verify') {
                    $invoice_id = $_POST['invoice_id'];
                    if ($feeModel->markAsVerified($invoice_id)) {
                        $data['success'] = 'Payment verified successfully.';
                    } else {
PHP;

$verifyNew = <<<'PHP'
                } else if (isset($_POST['action']) && $_POST['action'] == 'verify') {
                    $invoice_id = $_POST['invoice_id'];
                    if ($feeModel->markAsVerified($invoice_id)) {
                        $data['success'] = 'Payment verified successfully.';
                        // Send Receipt Email
                        require_once '../app/Core/Mail.php';
                        $db = (new Model())->db;
                        $stmt = $db->prepare("SELECT f.*, u.email FROM fees f JOIN students s ON f.student_id = s.id JOIN users u ON s.user_id = u.id WHERE f.id = ?");
                        $stmt->execute([$invoice_id]);
                        $fee = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($fee && $fee['email']) {
                            $msg = "Your payment for Invoice <strong>{$fee['invoice_no']}</strong> has been successfully verified by the Finance Department.<br><br><strong>Fee Type:</strong> " . ucfirst($fee['fee_type']) . "<br><strong>Amount Paid:</strong> Rs. " . number_format($fee['amount']) . "<br><strong>Reference No:</strong> {$fee['reference_no']}<br><br>Thank you for your timely payment.";
                            Mail::sendTemplate($fee['email'], 'Payment Receipt - CIT UMS', 'Payment Verified', $msg, 'View Invoice', 'http://localhost/cit_ums/fee');
                        }
                    } else {
PHP;

$c = str_replace($verifyOld, $verifyNew, $c);

// 2. Hook for 'remind' (Payment Reminder)
$remindOld = <<<'PHP'
                } else if (isset($_POST['action']) && $_POST['action'] == 'remind') {
                    $invoice_id = $_POST['invoice_id'];
                    // In a real app, send an email here.
                    $data['success'] = 'Payment reminder sent to student.';
                }
PHP;

$remindNew = <<<'PHP'
                } else if (isset($_POST['action']) && $_POST['action'] == 'remind') {
                    $invoice_id = $_POST['invoice_id'];
                    require_once '../app/Core/Mail.php';
                    $db = (new Model())->db;
                    $stmt = $db->prepare("SELECT f.*, u.email FROM fees f JOIN students s ON f.student_id = s.id JOIN users u ON s.user_id = u.id WHERE f.id = ?");
                    $stmt->execute([$invoice_id]);
                    $fee = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($fee && $fee['email']) {
                        $msg = "This is a reminder that you have a pending fee invoice that requires your attention.<br><br><strong>Invoice No:</strong> {$fee['invoice_no']}<br><strong>Fee Type:</strong> " . ucfirst($fee['fee_type']) . "<br><strong>Amount Due:</strong> Rs. " . number_format($fee['amount']) . "<br><strong>Due Date:</strong> {$fee['due_date']}<br><br>Please clear your dues as soon as possible to avoid late fines.";
                        Mail::sendTemplate($fee['email'], 'Fee Payment Reminder - CIT UMS', 'Payment Reminder', $msg, 'Pay Now', 'http://localhost/cit_ums/fee');
                    }
                    $data['success'] = 'Payment reminder sent to student.';
                }
PHP;

$c = str_replace($remindOld, $remindNew, $c);

file_put_contents($f, $c);
echo "Fee email hooks added.\n";
