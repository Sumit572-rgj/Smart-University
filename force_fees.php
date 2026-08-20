<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/FeeController.php';
$c = file_get_contents($f);

// 1. Regex replace verify logic
$verifyOld = '/\} else if \(isset\(\$_POST\[\'action\'\]\) && \$_POST\[\'action\'\] == \'verify\'\) \{[\s\S]*?\} else if \(isset\(\$_POST\[\'action\'\]\) && \$_POST\[\'action\'\] == \'create\'\) \{/';
$verifyNew = <<<'PHP'
} else if (isset($_POST['action']) && $_POST['action'] == 'verify') {
                    $invoice_id = $_POST['invoice_id'];
                    if ($feeModel->markAsVerified($invoice_id)) {
                        $data['success'] = 'Payment verified successfully.';
                        
                        require_once '../app/Core/Mail.php';
                        $db = (new Model())->db;
                        $stmt = $db->prepare("SELECT f.*, u.email FROM fees f JOIN students s ON f.student_id = s.id JOIN users u ON s.user_id = u.id WHERE f.id = ?");
                        $stmt->execute([$invoice_id]);
                        $fee = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($fee && $fee['email']) {
                            $msg = "Your payment for Invoice <strong>{$fee['invoice_no']}</strong> has been successfully verified by the Finance Department.<br><br><strong>Fee Type:</strong> " . ucfirst($fee['fee_type']) . "<br><strong>Amount Paid:</strong> Rs. " . number_format($fee['amount']) . "<br><strong>Reference No:</strong> {$fee['reference_no']}<br><br>Thank you for your timely payment.";
                            Mail::sendTemplate($fee['email'], 'Payment Receipt - CIT UMS', 'Payment Verified', $msg, 'View Dashboard', 'http://localhost/cit_ums/dashboard');
                        }
                    } else {
                        $data['error'] = 'Failed to verify payment.';
                    }
                } else if (isset($_POST['action']) && $_POST['action'] == 'create') {
PHP;

$c = preg_replace($verifyOld, $verifyNew, $c);

// 2. Regex replace remind logic
$remindOld = '/\} else if \(isset\(\$_POST\[\'action\'\]\) && \$_POST\[\'action\'\] == \'remind\'\) \{[\s\S]*?\}\s*\}/';
$remindNew = <<<'PHP'
} else if (isset($_POST['action']) && $_POST['action'] == 'remind') {
                    require_once '../app/Core/Mail.php';
                    $db = (new Model())->db;
                    $stmt = $db->query("SELECT f.*, u.email FROM fees f JOIN students s ON f.student_id = s.id JOIN users u ON s.user_id = u.id WHERE f.status IN ('pending', 'overdue')");
                    $fees = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    $sentCount = 0;
                    foreach ($fees as $fee) {
                        if ($fee['email']) {
                            $msg = "This is an automated reminder that you have a pending fee invoice requiring your attention.<br><br><strong>Invoice No:</strong> {$fee['invoice_no']}<br><strong>Fee Type:</strong> " . ucfirst($fee['fee_type']) . "<br><strong>Amount Due:</strong> Rs. " . number_format($fee['amount']) . "<br><strong>Due Date:</strong> {$fee['due_date']}<br><br>Please clear your dues promptly to avoid late fines.";
                            Mail::sendTemplate($fee['email'], 'Fee Payment Reminder - CIT UMS', 'Payment Reminder', $msg, 'Pay Now', 'http://localhost/cit_ums/dashboard');
                            $sentCount++;
                        }
                    }
                    $data['success'] = "Reminders successfully sent to {$sentCount} students with pending dues.";
                }
            }
PHP;

$c = preg_replace($remindOld, $remindNew, $c);

file_put_contents($f, $c);
echo "Fees logic patched.\n";
