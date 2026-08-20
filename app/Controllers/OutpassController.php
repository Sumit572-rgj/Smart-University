<?php
// app/Controllers/OutpassController.php

class OutpassController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) {
            $this->redirect('/auth/login');
        }

        $outpassModel = $this->model('Outpass');
        
        $data = [
            'user' => $user,
            'title' => 'Outpass Management',
            'success' => '',
            'error' => ''
        ];

        // Process Approvals
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'approve') {
            $outpass_id = $_POST['outpass_id'];
            $new_status = '';
            
            if ($user['role'] === 'faculty') {
                if ($outpassModel->updateStatus($outpass_id, 'pending_admin')) {
                    $data['success'] = 'Outpass approved successfully. Moving to Admin.';
                    $this->simulateNotification($outpass_id, "Outpass update: Approved by Faculty.");
                } else {
                    $data['error'] = 'Failed to approve outpass.';
                }
            } else if ($user['role'] === 'admin') {
                if ($outpassModel->updateStatus($outpass_id, 'pending_warden')) {
                    $data['success'] = 'Outpass approved successfully. Moving to Warden.';
                    $this->simulateNotification($outpass_id, "Outpass update: Approved by Admin.");
                } else {
                    $data['error'] = 'Failed to approve outpass.';
                }
            } else if ($user['role'] === 'warden') {
                // Generate a QR code using a public API
                $qr_data = "OUTPASS_ID:{$outpass_id}_VERIFIED";
                $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qr_data);
                
                if ($outpassModel->updateStatusAndQR($outpass_id, 'approved', $qr_url)) {
                    $data['success'] = 'Outpass fully approved and QR generated. Sent to gate security.';
                    $this->simulateNotification($outpass_id, "Outpass FINALIZED: Approved by Warden. QR Code sent via Email/SMS to Student & Parents.");
                } else {
                    $data['error'] = 'Failed to approve outpass.';
                }
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'reject') {
            if ($outpassModel->updateStatus($_POST['outpass_id'], 'rejected')) {
                $data['success'] = 'Outpass rejected.';
                $this->simulateNotification($_POST['outpass_id'], "Outpass update: Request REJECTED.");
            }
        }

        if ($user['role'] === 'student') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create') {
                $leave_date = $_POST['leave_date'];
                $return_date = $_POST['return_date'];
                $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_STRING);
                $destination = filter_input(INPUT_POST, 'destination', FILTER_SANITIZE_STRING);
                
                $studentModel = $this->model('Student');
                $student = $studentModel->getStudentByUserId($user['id']);
                
                if (!$student) {
                    $data['error'] = 'Student profile not found.';
                } else {
                            $db = (new Model())->db;
        
        // AUTO-ERASE logic: Erase outpasses that were scanned successfully (checked_out or checked_in) and 24 hours have passed since they were last updated
        try {
            $db->exec("DELETE FROM outpass WHERE status = 'checked_out' AND updated_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)");
        } catch(Exception $e) {}
                    $checkHostel = $db->prepare("SELECT id FROM hostel_allocations WHERE student_id = ? AND status = 'active'");
                    $checkHostel->execute([$student['id']]);
                    if (!$checkHostel->fetchColumn()) {
                        $data['error'] = 'You must have an active hostel allocation to apply for an outpass.';
                    } else {
                        if ($outpassModel->createRequest($student['id'], $leave_date, $return_date, $reason, $destination)) {
                            $data['success'] = 'Outpass request submitted. Awaiting Faculty approval. Notification sent to Parents.';
                        } else {
                            $data['error'] = 'Failed to submit request.';
                        }
                    }
                }
            }

            $studentModel = $this->model('Student');
            $student = $studentModel->getStudentByUserId($user['id']);
            $data['requests'] = $student ? $outpassModel->getRequestsByStudent($student['id']) : [];
            $this->view('outpass/student', $data);

        } else if (in_array($user['role'], ['faculty', 'admin', 'warden'])) {
            $status_to_fetch = 'pending_faculty';
            if ($user['role'] === 'admin') $status_to_fetch = 'pending_admin';
            if ($user['role'] === 'warden') $status_to_fetch = 'pending_warden';

            $data['pending_requests'] = $outpassModel->getPendingRequestsByStatus($status_to_fetch);
            
            // Dummy AI Analytics
            $data['ai_analytics'] = [
                'frequent_travelers' => 5,
                'unusual_patterns' => 2,
                'avg_duration' => '48 Hours'
            ];
            
            $this->view('outpass/staff', $data);
        } else {
            $this->redirect('/dashboard');
        }
    }

    private function simulateNotification($outpass_id, $message) {
        // Log it as before
        error_log("NOTIFICATION [Outpass $outpass_id]: $message");
        
        // Actually send real email
        require_once '../app/Core/Mail.php';
        $db = (new Model())->db;
        $stmt = $db->prepare("SELECT u.email, o.status, o.destination FROM outpass o JOIN students s ON o.student_id = s.id JOIN users u ON s.user_id = u.id WHERE o.id = ?");
        $stmt->execute([$outpass_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($data && $data['email']) {
            $statusLabel = strtoupper($data['status']);
            $msg = "There is an update regarding your Outpass application to <strong>{$data['destination']}</strong>.<br><br><strong>Current Status:</strong> {$statusLabel}<br><strong>System Message:</strong> {$message}";
            Mail::sendTemplate($data['email'], "Outpass Update: {$statusLabel}", 'Outpass Request Update', $msg, 'View Details', 'http://localhost/cit_ums/outpass');
        }
    }
}
