<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/HostelAttendanceController.php';

$newController = <<<'PHP'
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
            $stmt = $db->prepare("
                INSERT INTO hostel_attendance (student_id, attendance_date, status, warden_id)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE status = VALUES(status), warden_id = VALUES(warden_id)
            ");
            
            foreach ($attendance_data as $student_id => $status) {
                $stmt->execute([$student_id, $date, $status, $user['id']]);
            }
            
            $this->redirect('/hostelattendance?date=' . $date . '&success=Attendance+Saved');
        }
    }
}
PHP;

file_put_contents($f, $newController);

// Build Views
if (!is_dir('C:/xampp/htdocs/cit_ums/app/Views/hostelattendance')) {
    mkdir('C:/xampp/htdocs/cit_ums/app/Views/hostelattendance');
}

$wardenView = <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?> - Warden Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/cit_ums/public/css/style.css">
    <style>
        .att-radio { display: none; }
        .att-label { padding: 0.5rem 1rem; border: 1px solid #cbd5e1; border-radius: 0.25rem; cursor: pointer; font-weight: 600; transition: all 0.2s; font-size: 0.85rem; }
        .att-radio:checked + .att-label.present { background: #10b981; color: white; border-color: #10b981; }
        .att-radio:checked + .att-label.absent { background: #ef4444; color: white; border-color: #ef4444; }
        .att-radio:checked + .att-label.outpass { background: #f59e0b; color: white; border-color: #f59e0b; }
        .att-label.disabled { opacity: 0.5; cursor: not-allowed; }
    </style>
</head>
<body id="chaos-body">

<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>
    <main class="main-content">
        <header class="topbar shadow-sm">
            <div class="welcome font-medium text-lg">🌙 Nightly Hostel Attendance</div>
        </header>

        <div class="p-6">
            <?php if($success): ?>
                <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg font-medium"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div class="card p-6 mb-6">
                <form method="GET" action="/cit_ums/hostelattendance" class="flex gap-4 items-end">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Select Date</label>
                        <input type="date" name="date" value="<?= htmlspecialchars($selectedDate) ?>" class="form-input" style="width: 200px;">
                    </div>
                    <button type="submit" class="btn btn-primary" style="background: #334155; color: white; padding: 0.5rem 1.5rem; border-radius: 0.25rem;">Fetch Roll Call</button>
                </form>
            </div>

            <div class="card p-6">
                <form method="POST" action="/cit_ums/hostelattendance/save">
                    <input type="hidden" name="attendance_date" value="<?= htmlspecialchars($selectedDate) ?>">
                    
                    <div style="overflow-x: auto;">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>Reg No</th>
                                    <th>Student Name</th>
                                    <th>Department</th>
                                    <th class="text-center">Mark Attendance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($students as $s): ?>
                                    <?php 
                                        $isOnOutpass = ($s['outpass_status'] === 'approved'); 
                                        $currentStatus = $s['marked_status'];
                                        if ($isOnOutpass) $currentStatus = 'Outpass';
                                        if (!$currentStatus) $currentStatus = 'Present'; // Default
                                    ?>
                                    <tr>
                                        <td class="font-bold text-gray-700"><?= htmlspecialchars($s['enrollment_no']) ?></td>
                                        <td><?= htmlspecialchars($s['first_name'] . ' ' . $s['last_name']) ?></td>
                                        <td><?= htmlspecialchars($s['department']) ?></td>
                                        <td>
                                            <div class="flex gap-2 justify-center">
                                                <!-- Present -->
                                                <input type="radio" name="attendance[<?= $s['student_id'] ?>]" id="p_<?= $s['student_id'] ?>" value="Present" class="att-radio" <?= $currentStatus === 'Present' ? 'checked' : '' ?> <?= $isOnOutpass ? 'disabled' : '' ?>>
                                                <label for="p_<?= $s['student_id'] ?>" class="att-label present <?= $isOnOutpass ? 'disabled' : '' ?>">Present</label>
                                                
                                                <!-- Absent -->
                                                <input type="radio" name="attendance[<?= $s['student_id'] ?>]" id="a_<?= $s['student_id'] ?>" value="Absent" class="att-radio" <?= $currentStatus === 'Absent' ? 'checked' : '' ?> <?= $isOnOutpass ? 'disabled' : '' ?>>
                                                <label for="a_<?= $s['student_id'] ?>" class="att-label absent <?= $isOnOutpass ? 'disabled' : '' ?>">Absent</label>
                                                
                                                <!-- Outpass -->
                                                <?php if($isOnOutpass): ?>
                                                    <input type="hidden" name="attendance[<?= $s['student_id'] ?>]" value="Outpass">
                                                    <input type="radio" checked class="att-radio">
                                                    <label class="att-label outpass">On Outpass</label>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="btn btn-primary" style="background: var(--cit-orange); color: white; padding: 0.75rem 2rem; border-radius: 0.25rem; font-weight: bold; font-size: 1.1rem;">Save Attendance</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
</body>
</html>
HTML;

$studentView = <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?> - Student Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/cit_ums/public/css/style.css">
</head>
<body id="chaos-body">

<div class="dashboard-layout">
    <?php include '../app/Views/partials/sidebar.php'; ?>
    <main class="main-content">
        <header class="topbar shadow-sm">
            <div class="welcome font-medium text-lg">🌙 My Hostel Attendance</div>
        </header>

        <div class="p-6 max-w-4xl mx-auto">
            <div class="card p-6">
                <h3 class="text-xl font-bold mb-6 text-gray-800">Last 30 Days Record</h3>
                
                <?php if(empty($attendance)): ?>
                    <p class="text-gray-500 text-center py-8">No attendance records found yet.</p>
                <?php else: ?>
                    <div style="overflow-x: auto;">
                        <table class="table w-full">
                            <thead>
                                <tr style="background: #f8fafc;">
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Logged At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($attendance as $a): ?>
                                    <tr>
                                        <td class="font-bold text-gray-700"><?= date('D, d M Y', strtotime($a['attendance_date'])) ?></td>
                                        <td>
                                            <?php if($a['status'] === 'Present'): ?>
                                                <span style="color: #047857; background: #d1fae5; padding: 0.25rem 0.75rem; border-radius: 999px; font-weight: bold; font-size: 0.85rem;">Present</span>
                                            <?php elseif($a['status'] === 'Absent'): ?>
                                                <span style="color: #b91c1c; background: #fee2e2; padding: 0.25rem 0.75rem; border-radius: 999px; font-weight: bold; font-size: 0.85rem;">Absent</span>
                                            <?php else: ?>
                                                <span style="color: #b45309; background: #fef3c7; padding: 0.25rem 0.75rem; border-radius: 999px; font-weight: bold; font-size: 0.85rem;">On Outpass</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-gray-500 text-sm"><?= date('h:i A', strtotime($a['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>
</body>
</html>
HTML;

file_put_contents('C:/xampp/htdocs/cit_ums/app/Views/hostelattendance/warden.php', $wardenView);
file_put_contents('C:/xampp/htdocs/cit_ums/app/Views/hostelattendance/student.php', $studentView);

// Inject link to sidebar
$sidebarFile = 'C:/xampp/htdocs/cit_ums/app/Views/partials/sidebar.php';
$sb = file_get_contents($sidebarFile);
if (!strpos($sb, '/cit_ums/hostelattendance')) {
    $link = "\n                  <a href=\"/cit_ums/hostelattendance\" class=\"nav-link <?= strpos(\$uri, '/cit_ums/hostelattendance') === 0 ? 'active' : '' ?>\"><span style=\"margin-right:8px; font-size:1.1rem;\">🌙</span>Hostel Roll Call</a>";
    $sb = str_replace('<div class="text-xs text-gray-500 font-bold uppercase mt-4 mb-2 px-4">Admin Features</div>', '<div class="text-xs text-gray-500 font-bold uppercase mt-4 mb-2 px-4">Admin Features</div>' . $link, $sb);
    
    // Inject for students too under their dashboard
    $sb = str_replace('<a href="/cit_ums/outpass" class="nav-link <?= strpos($uri, \'/cit_ums/outpass\') === 0 ? \'active\' : \'\' ?>"><span style="margin-right:8px; font-size:1.1rem;">✈️</span>Outpass</a>', '<a href="/cit_ums/outpass" class="nav-link <?= strpos($uri, \'/cit_ums/outpass\') === 0 ? \'active\' : \'\' ?>"><span style="margin-right:8px; font-size:1.1rem;">✈️</span>Outpass</a>' . "\n              <a href=\"/cit_ums/hostelattendance\" class=\"nav-link <?= strpos(\$uri, '/cit_ums/hostelattendance') === 0 ? 'active' : '' ?>\"><span style=\"margin-right:8px; font-size:1.1rem;\">🌙</span>My Attendance</a>", $sb);
    
    file_put_contents($sidebarFile, $sb);
}

echo "Hostel Attendance Feature Complete.\n";
