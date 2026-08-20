<?php
// app/Controllers/AttendanceController.php

class AttendanceController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) {
            $this->redirect('/auth/login');
        }

        $db = (new Model())->db;

        $data = [
            'user' => $user,
            'title' => 'Attendance Management',
            'success' => $_GET['success'] ?? '',
            'error' => $_GET['error'] ?? ''
        ];

        if ($user['role'] === 'faculty') {
            $facultyStmt = $db->prepare("SELECT id FROM faculty WHERE user_id = ?");
            $facultyStmt->execute([$user['id']]);
            $faculty_id = $facultyStmt->fetchColumn();

            $courseModel = $this->model('Course');

            // Handle submission
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $course_id = (int)$_POST['course_id'];
                $cStmt = $db->prepare("SELECT course_code FROM courses WHERE id = ?");
                $cStmt->execute([$course_id]);
                $cCode = $cStmt->fetchColumn();

                $date = $_POST['date'];
                $attendance_data = $_POST['attendance'] ?? []; // student_id => status

                if ($cCode && $date && !empty($attendance_data)) {
                    $insertStmt = $db->prepare("INSERT INTO attendance (student_id, course_code, date, status) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE status = ?");
                    foreach ($attendance_data as $student_id => $status) {
                        $insertStmt->execute([$student_id, $cCode, $date, $status, $status]);
                    }
                    $this->redirect('/attendance?success=Attendance+saved');
                }
            }

            // Get faculty courses
            $data['courses'] = $courseModel->getCoursesByFaculty($faculty_id);

            // Get selected course students
            $selected_course_id = $_GET['course'] ?? ($data['courses'][0]['id'] ?? null);
            $selected_date = $_GET['date'] ?? date('Y-m-d');
            $data['selected_course'] = $selected_course_id;
            $data['selected_date'] = $selected_date;

            $data['students'] = [];
            if ($selected_course_id) {
                $cStmt = $db->prepare("SELECT course_code FROM courses WHERE id = ?");
                $cStmt->execute([$selected_course_id]);
                $cCode = $cStmt->fetchColumn();

                $data['students'] = $courseModel->getEnrolledStudents($selected_course_id);
                
                // Fetch existing attendance for this date/course
                $attStmt = $db->prepare("SELECT student_id, status FROM attendance WHERE course_code = ? AND date = ?");
                $attStmt->execute([$cCode, $selected_date]);
                $existing = $attStmt->fetchAll(PDO::FETCH_KEY_PAIR);

                foreach ($data['students'] as &$s) {
                    $s['status'] = $existing[$s['id']] ?? 'present'; // Default present
                }
            }

            $this->view('attendance/faculty', $data);

        } else if ($user['role'] === 'student') {
            $studentStmt = $db->prepare("SELECT id FROM students WHERE user_id = ?");
            $studentStmt->execute([$user['id']]);
            $student_id = $studentStmt->fetchColumn();

            $attStmt = $db->prepare("SELECT * FROM attendance WHERE student_id = ? ORDER BY date DESC");
            $attStmt->execute([$student_id]);
            $data['records'] = $attStmt->fetchAll();

            // Calculate percentage
            $total = count($data['records']);
            $present = count(array_filter($data['records'], fn($r) => $r['status'] === 'present' || $r['status'] === 'late'));
            $data['percentage'] = $total > 0 ? round(($present / $total) * 100, 2) : 100;

            $this->view('attendance/student', $data);
        } else {
            $this->redirect('/dashboard');
        }
    }
}
