<?php
class DashboardController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) {
            $this->redirect('/auth/login');
        }

        $db = (new Model())->db;
        $data = [
            'user' => $user,
            'title' => 'Dashboard'
        ];

        if ($user['role'] === 'admin') {
            $studentsCount = $db->query("SELECT COUNT(*) FROM students")->fetchColumn();
            
            $facDb = $db->query("SHOW TABLES LIKE 'faculty'")->rowCount() > 0;
            $facultyCount = $facDb ? $db->query("SELECT COUNT(*) FROM faculty")->fetchColumn() : 0;
            
            $outDb = $db->query("SHOW TABLES LIKE 'outpass'")->rowCount() > 0;
            $outpassCount = $outDb ? $db->query("SELECT COUNT(*) FROM outpass WHERE status = 'pending_admin'")->fetchColumn() : 0;
            
            $feeDb = $db->query("SHOW TABLES LIKE 'fees'")->rowCount() > 0;
            $feeCollected = 0;
            if ($feeDb) {
                $feeCollected = $db->query("SELECT SUM(amount) FROM fees WHERE status IN ('paid', 'verified')")->fetchColumn();
            }

            $data['stats'] = [
                ['label' => 'Total Students', 'value' => $studentsCount, 'link' => BASE_URL . 'student'],
                ['label' => 'Total Faculty', 'value' => $facultyCount, 'link' => BASE_URL . 'faculty'],
                ['label' => 'Pending Admin Outpasses', 'value' => $outpassCount, 'link' => BASE_URL . 'outpass'],
                ['label' => 'Revenue Collected', 'value' => 'Rs. ' . number_format((float)$feeCollected), 'link' => '#']
            ];
            $data['recent_activities'] = [
                'Review outpasses approved by Warden.',
                'Check financial fee collection status.'
            ];
        } else if ($user['role'] === 'warden') {
            $outDb = $db->query("SHOW TABLES LIKE 'outpass'")->rowCount() > 0;
            $outpassCount = $outDb ? $db->query("SELECT COUNT(*) FROM outpass WHERE status = 'pending_warden'")->fetchColumn() : 0;
            
            $data['stats'] = [
                ['label' => 'Pending Warden Outpasses', 'value' => $outpassCount, 'link' => BASE_URL . 'outpass']
            ];
            $data['recent_activities'] = [
                'Review outpasses applied by students.'
            ];
        } else if ($user['role'] === 'faculty') {
            $outDb = $db->query("SHOW TABLES LIKE 'outpass'")->rowCount() > 0;
            $outpassCount = $outDb ? $db->query("SELECT COUNT(*) FROM outpass WHERE status = 'pending_faculty'")->fetchColumn() : 0;
            
            $data['stats'] = [
                ['label' => 'Pending Faculty Outpasses', 'value' => $outpassCount, 'link' => BASE_URL . 'outpass']
            ];
            $data['recent_activities'] = [
                'Review student outpass applications.',
                'Mark daily attendance for your classes.'
            ];
        } else if ($user['role'] === 'student') {
            $student = $db->prepare("SELECT id FROM students WHERE user_id = ?");
            $student->execute([$user['id']]);
            $student_id = $student->fetchColumn();

            $outDb = $db->query("SHOW TABLES LIKE 'outpass'")->rowCount() > 0;
            $outpassCount = $outDb ? $db->query("SELECT COUNT(*) FROM outpass WHERE student_id = " . (int)$student_id)->fetchColumn() : 0;
            
            $feeDb = $db->query("SHOW TABLES LIKE 'fees'")->rowCount() > 0;
            $feePending = 0;
            if ($feeDb) {
                $feePending = $db->query("SELECT SUM(amount) FROM fees WHERE student_id = " . (int)$student_id . " AND status IN ('pending', 'overdue')")->fetchColumn();
                if (!$feePending) $feePending = 0;
            }

                        $haPercent = 'No Data';
            $haDb = $db->query("SHOW TABLES LIKE 'hostel_attendance'")->rowCount() > 0;
            if ($haDb && $student_id) {
                $totalDays = $db->query("SELECT COUNT(*) FROM hostel_attendance WHERE student_id = " . (int)$student_id . " AND status != 'Outpass'")->fetchColumn();
                $presentDays = $db->query("SELECT COUNT(*) FROM hostel_attendance WHERE student_id = " . (int)$student_id . " AND status = 'Present'")->fetchColumn();
                if ($totalDays > 0) {
                    $haPercent = round(($presentDays / $totalDays) * 100) . '%';
                }
            }

            $data['stats'] = [
                ['label' => 'Total Outpasses', 'value' => $outpassCount, 'link' => BASE_URL . 'outpass'],
                ['label' => 'Pending Fees', 'value' => 'Rs. ' . number_format((float)$feePending), 'link' => BASE_URL . 'fee'],
                ['label' => 'Hostel Attendance', 'value' => $haPercent, 'link' => BASE_URL . 'hostelattendance']
            ];
            $data['recent_activities'] = [
                'Check your recent outpass status.',
                'Pay any pending semester fees.'
            ];
        } else {
             $data['stats'] = [];
             $data['recent_activities'] = [];
        }

        $this->view('dashboard/index', $data);
    }
}
