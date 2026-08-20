<?php
// app/Controllers/GateController.php

class GateController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user || $user['role'] !== 'security') {
            $this->redirect('/dashboard');
        }

                $db = (new Model())->db;
        
        // AUTO-ERASE logic: Erase outpasses that were scanned successfully (checked_out or checked_in) and 24 hours have passed since they were last updated
        try {
            $db->exec("DELETE FROM outpass WHERE status = 'checked_out' AND updated_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)");
        } catch(Exception $e) {}
        $data = [
            'user' => $user,
            'title' => 'Gate Security Scanner',
            'success' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $outpass_id = $_POST['outpass_id'] ?? '';
            
                        // Allow scanning QR which might be the ID directly, a URL encoded string, or a formatted string
            $scanned_id = trim(urldecode($outpass_id));
            
            // Flexible regex to catch OUTPASS_ID:1_VERIFIED or OUTPASS_ID%3A1_VERIFIED or any variation
            if (preg_match('/OUTPASS_ID.*?(\d+)_VERIFIED/i', $scanned_id, $matches)) {
                $scanned_id = $matches[1];
            } else if (preg_match('/(\d+)/', $scanned_id, $matches)) {
                // Fallback: if it's just a number or a weird string with a number, grab the first number
                $scanned_id = $matches[1];
            }
            
            if (is_numeric($scanned_id)) {
                $outpass_id = (int)$scanned_id;
                $stmt = $db->prepare("SELECT o.*, s.first_name, s.last_name, s.enrollment_no, s.department, s.profile_pic  
                                      FROM outpass o 
                                      JOIN students s ON o.student_id = s.id 
                                      WHERE o.id = ?");
                $stmt->execute([$outpass_id]);
                $outpass = $stmt->fetch();

                if ($outpass) {
                    $data['scanned_student'] = $outpass;
                    if ($outpass['status'] === 'approved') {
                        // Mark as checked out
                        $update = $db->prepare("UPDATE outpass SET status = 'checked_out' WHERE id = ?");
                        $update->execute([$outpass_id]);
                        $data['success'] = "CHECKED OUT: {$outpass['first_name']} {$outpass['last_name']} ({$outpass['enrollment_no']}) has left the campus.";
                        $data['scanned_student']['current_action'] = 'CHECKED OUT';
                        $data['scanned_student']['action_color'] = '#f59e0b'; // orange
                    } else if ($outpass['status'] === 'checked_out') {
                        // Mark as checked in
                        $update = $db->prepare("UPDATE outpass SET status = 'checked_in' WHERE id = ?");
                        $update->execute([$outpass_id]);
                        $data['success'] = "CHECKED IN: {$outpass['first_name']} {$outpass['last_name']} ({$outpass['enrollment_no']}) has returned to the campus.";
                        $data['scanned_student']['current_action'] = 'CHECKED IN';
                        $data['scanned_student']['action_color'] = '#10b981'; // green
                    } else if ($outpass['status'] === 'checked_in') {
                        $data['error'] = "Error: Outpass #$outpass_id has already been used and checked in.";
                    } else {
                        $data['error'] = "Error: Outpass #$outpass_id is currently '{$outpass['status']}' and not approved for exit.";
                    }
                } else {
                    $data['error'] = "Invalid Outpass ID. No record found.";
                }
            } else {
                $data['error'] = "Invalid Outpass ID format. Scanned raw: [" . htmlspecialchars($outpass_id) . "] parsed as: [" . htmlspecialchars($scanned_id) . "]";
            }
        }

        // Fetch today's activity for the security guard to see
        $data['activities'] = $db->query("SELECT o.id, o.status, s.first_name, s.last_name, s.enrollment_no 
                                          FROM outpass o 
                                          JOIN students s ON o.student_id = s.id 
                                          WHERE o.status = 'checked_out' 
                                          ORDER BY o.id DESC LIMIT 20")->fetchAll();

        $this->view('gate/index', $data);
    }
}
