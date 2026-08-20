<?php
class HostelAttendanceController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }
        
        $db = (new Model())->db;
        
        // Ensure table exists
        $db->exec("CREATE TABLE IF NOT EXISTS hostel_attendance (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_id INT NOT NULL,
            attendance_date DATE NOT NULL,
            status ENUM('Present', 'Absent', 'Outpass') NOT NULL,
            warden_id INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_attendance (student_id, attendance_date)
        )");

        $data = [
            'user' => $user,
            'title' => 'Hostel Attendance',
            'success' => $_GET['success'] ?? '',
            'error' => $_GET['error'] ?? ''
        ];
        
        // Date selection logic
        $selectedDate = $_GET['date'] ?? date('Y-m-d');
        $data['selectedDate'] = $selectedDate;
        
        if ($user['role'] === 'admin' || $user['role'] === 'warden') {
            // Warden/Admin View: Show all students to mark attendance
            $stmt = $db->prepare("
                SELECT s.id as student_id, s.first_name, s.last_name, s.enrollment_no, s.department,
                       ha.status as marked_status,
                       (SELECT status FROM outpass o 
                        WHERE o.student_id = s.id 
                        AND o.status = 'approved' 
                        AND ? BETWEEN o.leave_date AND o.return_date 
                        LIMIT 1) as outpass_status
                FROM students s
                LEFT JOIN hostel_attendance ha ON s.id = ha.student_id AND ha.attendance_date = ?
                ORDER BY s.enrollment_no ASC
            ");
            $stmt->execute([$selectedDate, $selectedDate]);
            $data['students'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $this->view('hostelattendance/warden', $data);
            
        } else {
            // Student View: Show their own attendance history
            $stmt = $db->prepare("SELECT id FROM students WHERE user_id = ?");
            $stmt->execute([$user['id']]);
            $studentId = $stmt->fetchColumn();
            
            if ($studentId) {
                $stmtAtt = $db->prepare("SELECT * FROM hostel_attendance WHERE student_id = ? ORDER BY attendance_date DESC LIMIT 30");
                $stmtAtt->execute([$studentId]);
                $data['attendance'] = $stmtAtt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $data['attendance'] = [];
            }
            
            $this->view('hostelattendance/student', $data);
        }
    }
    
    public function save() {
        $user = JWT::getToken();
        if (!$user || ($user['role'] !== 'admin' && $user['role'] !== 'warden')) {
            $this->redirect('/dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['attendance_date'];
            $attendance_data = $_POST['attendance'] ?? [];
            
            $db = (new Model())->db;
            // To ensure compatibility with older and newer MySQL without VALUES() deprecation warnings
            $stmtCheck = $db->prepare("SELECT id FROM hostel_attendance WHERE student_id = ? AND attendance_date = ?");
            $stmtUpdate = $db->prepare("UPDATE hostel_attendance SET status = ?, warden_id = ? WHERE id = ?");
            $stmtInsert = $db->prepare("INSERT INTO hostel_attendance (student_id, attendance_date, status, warden_id) VALUES (?, ?, ?, ?)");
            
            foreach ($attendance_data as $student_id => $status) {
                $stmtCheck->execute([$student_id, $date]);
                $existingId = $stmtCheck->fetchColumn();
                
                if ($existingId) {
                    $stmtUpdate->execute([$status, $user['id'], $existingId]);
                } else {
                    $stmtInsert->execute([$student_id, $date, $status, $user['id']]);
                }
            }
            
            $this->redirect('/hostelattendance?date=' . $date . '&success=Attendance+Saved');
        }
    }
}