<?php

class FeeController extends Controller {

    public function index() {
        $user = JWT::getToken();
        if (!$user) {
            $this->redirect('/auth/login');
        }

        if ($user['role'] === 'admin' || $user['role'] === 'finance') {
            $this->redirect('/fee/admin');
        } else {
            $this->redirect('/fee/student');
        }
    }

    public function admin() {
        $user = JWT::getToken();
        if (!$user || ($user['role'] !== 'admin' && $user['role'] !== 'finance')) {
            $this->redirect('/auth/login');
        }

        $feeModel = $this->model('Fee');
        $data = [
            'user' => $user,
            'title' => 'Finance & Fees',
            'success' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['action']) && $_POST['action'] == 'verify') {
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
                        Mail::sendTemplate($fee['email'], 'Payment Receipt - CIT UMS', 'Payment Verified', $msg, 'Download Receipt', 'http://localhost/cit_ums/fee/receipt?id=' . $invoice_id);
                    }
                } else {
                    $data['error'] = 'Failed to verify payment.';
                }
            } else if (isset($_POST['action']) && $_POST['action'] == 'create') {
                $student_id = $_POST['student_id'];
                $fee_type = $_POST['fee_type'];
                $amount = $_POST['amount'];
                $discount = $_POST['discount'] ?? 0;
                $fine = $_POST['fine'] ?? 0;
                $due_date = $_POST['due_date'];

                if ($feeModel->createInvoice($student_id, $fee_type, $amount, $due_date, $discount, $fine)) {
                    $data['success'] = 'Invoice generated successfully.';
                } else {
                    $data['error'] = 'Failed to generate invoice.';
                }
            } else if (isset($_POST['action']) && $_POST['action'] == 'delete') {
                $invoice_id = $_POST['invoice_id'];
                if ($feeModel->deleteInvoice($invoice_id)) {
                    $data['success'] = 'Invoice deleted successfully.';
                } else {
                    $data['error'] = 'Failed to delete invoice.';
                }
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

        $data['invoices'] = $feeModel->getAllInvoices();
        $data['students_list'] = (new Model())->db->query("SELECT id, enrollment_no, first_name, last_name FROM students")->fetchAll();
        $this->view('fee/admin', $data);
    }

    public function student() {
        $user = JWT::getToken();
        if (!$user || $user['role'] !== 'student') {
            $this->redirect('/auth/login');
        }

        $feeModel = $this->model('Fee');
        $db = (new Model())->db;
        $stmt = $db->prepare("SELECT id FROM students WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        $student_id = $stmt->fetchColumn();

        $data = [
            'user' => $user,
            'title' => 'My Fees',
            'success' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['action']) && $_POST['action'] == 'pay') {
                $invoice_id = $_POST['invoice_id'];
                $reference_no = $_POST['reference_no'];

                if ($feeModel->markAsPaid($invoice_id, $reference_no)) {
                    $data['success'] = 'Payment submitted successfully. Pending verification.';
                } else {
                    $data['error'] = 'Failed to submit payment.';
                }
            }
        }

        $data['invoices'] = $feeModel->getInvoicesByStudentUserId($user['id']);
        $this->view('fee/student', $data);
    }

    public function receipt() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }

        $invoice_id = $_GET['id'] ?? null;
        if (!$invoice_id) { die("Invoice ID required."); }

        $db = (new Model())->db;
        $stmt = $db->prepare("SELECT f.*, s.first_name, s.last_name, s.enrollment_no, s.department FROM fees f JOIN students s ON f.student_id = s.id WHERE f.id = ?");
        $stmt->execute([$invoice_id]);
        $fee = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$fee) { die("Invoice not found."); }

        // Security check
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
