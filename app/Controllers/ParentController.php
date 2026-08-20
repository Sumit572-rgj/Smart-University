<?php
// app/Controllers/ParentController.php

class ParentController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }
        if ($user['role'] !== 'parent') { $this->redirect('/dashboard'); }

        $db = (new Model())->db;
        $data = ['user' => $user, 'title' => 'Parent & Guardian Portal'];

        // Get linked children
        $stmt = $db->prepare("
            SELECT p.relation, s.id as student_id, s.first_name, s.last_name, s.enrollment_no, s.department 
            FROM parent_profiles p 
            JOIN students s ON p.student_id = s.id 
            WHERE p.user_id = ?
        ");
        $stmt->execute([$user['id']]);
        $children = $stmt->fetchAll();

        $childrenData = [];
        foreach ($children as $child) {
            $sid = $child['student_id'];
            
            // 1. Attendance Summary
            $attendance = $db->query("
                SELECT 
                    COUNT(*) as total_classes, 
                    SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count 
                FROM attendance WHERE student_id = $sid
            ")->fetch();
            $att_perc = $attendance['total_classes'] > 0 ? round(($attendance['present_count'] / $attendance['total_classes']) * 100, 2) : 0;
            
            // 2. Exam Results
            $exams = $db->query("
                SELECT e.title as exam_name, er.score as marks_obtained, er.total_marks 
                FROM exam_results er 
                JOIN exams e ON er.exam_id = e.id 
                WHERE er.student_id = $sid ORDER BY er.submitted_at DESC
            ")->fetchAll();

            foreach ($exams as &$ex) {
                $perc = ($ex['total_marks'] > 0) ? ($ex['marks_obtained'] / $ex['total_marks']) * 100 : 0;
                $ex['grade'] = $perc >= 90 ? 'A+' : ($perc >= 80 ? 'A' : ($perc >= 70 ? 'B' : ($perc >= 60 ? 'C' : 'F')));
            }

            // 3. Fee Status
            $fees = $db->query("
                SELECT id, fee_type, (amount - discount_amount + fine_amount) as total_amount, due_date, status 
                FROM fees WHERE student_id = $sid
            ")->fetchAll();

            // 4. Notifications / Events
            $notices = $db->query("
                SELECT title, description, event_date 
                FROM events WHERE type = 'notice' AND visibility IN ('all', 'students', 'parents') 
                ORDER BY event_date DESC LIMIT 3
            ")->fetchAll();

            $childrenData[] = [
                'info' => $child,
                'attendance_percent' => $att_perc,
                'total_classes' => $attendance['total_classes'],
                'exams' => $exams,
                'fees' => $fees,
                'notices' => $notices
            ];
        }

        $data['children'] = $childrenData;
        $data['success'] = $_GET['success'] ?? '';
        $data['error'] = $_GET['error'] ?? '';
        
        $this->view('parent/index', $data);
    }

    public function pay() {
        $user = JWT::getToken();
        if (!$user || $user['role'] !== 'parent') { $this->redirect('/auth/login'); }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fee_id = $_POST['fee_id'] ?? null;
            $razorpay_payment_id = $_POST['razorpay_payment_id'] ?? null;
            
            if ($fee_id && $razorpay_payment_id) {
                $db = (new Model())->db;
                $stmt = $db->prepare("UPDATE fees SET status = 'paid', reference_no = ? WHERE id = ?");
                if ($stmt->execute([$razorpay_payment_id, $fee_id])) {
                    $this->redirect('/parent?success=Payment+Successful');
                } else {
                    $this->redirect('/parent?error=Database+update+failed');
                }
            } else {
                $this->redirect('/parent?error=Payment+verification+failed');
            }
        }
    }
}
