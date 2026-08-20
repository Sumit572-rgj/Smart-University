<?php
// app/Controllers/CourseController.php

class CourseController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) $this->redirect('/auth/login');
        
        $courseModel = $this->model('Course');
        $facultyModel = $this->model('Faculty');
        $studentModel = $this->model('Student');
        
        $data = [
            'user' => $user,
            'title' => 'Course Management',
            'success' => '',
            'error' => ''
        ];

        if ($user['role'] === 'admin') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $action = $_POST['action'] ?? '';
                if ($action == 'create') {
                    if ($courseModel->createCourse($_POST['code'], $_POST['title'], $_POST['credits'])) {
                        $data['success'] = "Course created.";
                    }
                } elseif ($action == 'assign_faculty') {
                    if ($courseModel->assignFaculty($_POST['course_id'], $_POST['faculty_id'])) {
                        $data['success'] = "Faculty assigned.";
                    }
                } elseif ($action == 'enroll_student') {
                    $stData = $studentModel->db->prepare("SELECT id FROM students WHERE enrollment_no = ?");
                    $stData->execute([$_POST['enrollment_no']]);
                    $st = $stData->fetch();
                    if ($st && $courseModel->enrollStudent($_POST['course_id'], $st['id'])) {
                        $data['success'] = "Student enrolled.";
                    } else {
                        $data['error'] = "Failed to enroll (invalid ID or already enrolled).";
                    }
                } elseif ($action == 'upload_syllabus') {
                    if (isset($_FILES['syllabus']) && $_FILES['syllabus']['error'] == 0) {
                        $ext = pathinfo($_FILES['syllabus']['name'], PATHINFO_EXTENSION);
                        if (strtolower($ext) == 'pdf') {
                            $filename = 'syl_' . $_POST['course_id'] . '_' . time() . '.pdf';
                            $dest = __DIR__ . '/../../public/uploads/syllabus/' . $filename;
                            if (move_uploaded_file($_FILES['syllabus']['tmp_name'], $dest)) {
                                $courseModel->updateSyllabus($_POST['course_id'], BASE_URL . 'uploads/syllabus/' . $filename);
                                $data['success'] = "Syllabus uploaded.";
                            }
                        } else {
                            $data['error'] = "Only PDF files allowed.";
                        }
                    }
                } elseif ($action == 'add_timetable') {
                    if ($courseModel->addTimetable($_POST['course_id'], $_POST['day'], $_POST['start'], $_POST['end'], $_POST['room'])) {
                        $data['success'] = "Timetable added.";
                    }
                }
            }

            $data['courses'] = $courseModel->getAllCourses();
            $data['faculties'] = $facultyModel->db->query("SELECT * FROM faculty")->fetchAll();
            $this->view('course/admin', $data);
            
        } elseif ($user['role'] === 'student') {
            $stu = $studentModel->db->prepare("SELECT id FROM students WHERE user_id = ?");
            $stu->execute([$user['id']]);
            $student = $stu->fetch();
            
            $data['my_courses'] = $student ? $courseModel->getCoursesByStudent($student['id']) : [];
            
            // Timetable logic
            $ttData = [];
            if ($student) {
                $stmt = $courseModel->db->prepare("
                    SELECT t.*, c.course_code, c.course_title 
                    FROM timetables t 
                    JOIN courses c ON t.course_id = c.id
                    JOIN course_students cs ON c.id = cs.course_id
                    WHERE cs.student_id = ?
                    ORDER BY FIELD(t.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), t.start_time
                ");
                $stmt->execute([$student['id']]);
                $ttData = $stmt->fetchAll();
            }
            $data['timetable'] = $ttData;
            $this->view('course/student', $data);
            
        } elseif ($user['role'] === 'faculty') {
            $fac = $facultyModel->db->prepare("SELECT id FROM faculty WHERE user_id = ?");
            $fac->execute([$user['id']]);
            $faculty = $fac->fetch();
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'upload_syllabus') {
                if (isset($_FILES['syllabus']) && $_FILES['syllabus']['error'] == 0) {
                    $ext = pathinfo($_FILES['syllabus']['name'], PATHINFO_EXTENSION);
                    if (strtolower($ext) == 'pdf') {
                        $filename = 'syl_' . $_POST['course_id'] . '_' . time() . '.pdf';
                        $dest = __DIR__ . '/../../public/uploads/syllabus/' . $filename;
                        if (move_uploaded_file($_FILES['syllabus']['tmp_name'], $dest)) {
                            $courseModel->updateSyllabus($_POST['course_id'], BASE_URL . 'uploads/syllabus/' . $filename);
                            $data['success'] = "Syllabus uploaded.";
                        }
                    } else {
                        $data['error'] = "Only PDF files allowed.";
                    }
                }
            }

            $data['my_courses'] = $faculty ? $courseModel->getCoursesByFaculty($faculty['id']) : [];
            $this->view('course/faculty', $data);
            
        } else {
            $this->redirect('/dashboard');
        }
    }
}
