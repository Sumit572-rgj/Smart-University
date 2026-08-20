<?php
// app/Controllers/ExamController.php

class ExamController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) {
            $this->redirect('/auth/login');
        }

        $examModel = $this->model('Exam');
        $courseModel = $this->model('Course');
        $facultyModel = $this->model('Faculty');

        if ($user['role'] === 'admin' || $user['role'] === 'faculty') {
            $data = [
                'user' => $user,
                'title' => 'Exam Management',
                'success' => '',
                'error' => ''
            ];
            
            $faculty = null;
            if ($user['role'] === 'faculty') {
                $fac = $facultyModel->db->prepare("SELECT id FROM faculty WHERE user_id = ?");
                $fac->execute([$user['id']]);
                $faculty = $fac->fetch();
                $data['my_courses'] = $faculty ? $courseModel->getCoursesByFaculty($faculty['id']) : [];
            } else {
                $data['my_courses'] = $courseModel->getAllCourses();
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['action']) && $_POST['action'] == 'delete') {
                    $exam_id = $_POST['exam_id'];
                    if ($examModel->deleteExam($exam_id)) {
                        $data['success'] = 'Exam deleted successfully.';
                    } else {
                        $data['error'] = 'Failed to delete exam.';
                    }
                } else if (isset($_POST['action']) && $_POST['action'] == 'create') {
                    $faculty_id = $faculty ? $faculty['id'] : null;
                    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
                    $course_id = (int)$_POST['course_id'];
                    $start_time = $_POST['start_time'];
                    $duration = (int)$_POST['duration_minutes'];

                    if ($examModel->createExam($faculty_id, $title, $course_id, $start_time, $duration)) {
                        $data['success'] = 'Exam scheduled successfully.';
                    } else {
                        $data['error'] = 'Failed to schedule exam.';
                    }
                }
            }

            // Route for manage questions
            if (isset($_GET['manage'])) {
                $exam_id = $_GET['manage'];
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_question') {
                    $q = $_POST['question_text'];
                    $a = $_POST['option_a'];
                    $b = $_POST['option_b'];
                    $c = $_POST['option_c'];
                    $d = $_POST['option_d'];
                    $correct = $_POST['correct_option'];
                    $marks = (int)($_POST['marks'] ?? 1);
                    $examModel->addQuestion($exam_id, $q, $a, $b, $c, $d, $correct, $marks);
                    $data['success'] = "Question added.";
                } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete_question') {
                    $examModel->deleteQuestion($_POST['question_id']);
                    $data['success'] = "Question deleted.";
                }

                $data['exam'] = $examModel->getExamById($exam_id);
                $data['questions'] = $examModel->getQuestions($exam_id);
                $data['title'] = 'Manage Questions - ' . $data['exam']['title'];
                return $this->view('exam/manage_questions', $data);
            }

            $data['exams'] = $user['role'] === 'faculty' && $faculty ? $examModel->getAllExams($faculty['id']) : $examModel->getAllExams();
            $this->view('exam/admin', $data);

        } else if ($user['role'] === 'student') {
            $studentModel = $this->model('Student');
            $student = $studentModel->getStudentByUserId($user['id']);
            $student_id = $student ? $student['id'] : null;

            $data = [
                'user' => $user,
                'title' => 'My Exams',
            ];

            $data['exams'] = $examModel->getAllExams();
            if ($student_id) {
                $data['results'] = $examModel->getResultsByStudent($student_id);
            } else {
                $data['results'] = [];
            }

            $this->view('exam/student', $data);

        } else {
            $this->redirect('/dashboard');
        }
    }

    public function take($id) {
        $user = JWT::getToken();
        if (!$user || $user['role'] !== 'student') {
            $this->redirect('/dashboard');
        }

        $examModel = $this->model('Exam');
        $exam = $examModel->getExamById($id);

        if (!$exam) {
            die("Exam not found.");
        }

        $now = time();
        $startTime = strtotime($exam['start_time']);
        $endTime = $startTime + ($exam['duration_minutes'] * 60);

        if ($now < $startTime) {
            die("Exam has not started yet. Please wait until " . date('d M Y h:i A', $startTime));
        }

        if ($now > $endTime) {
            die("Exam has already ended.");
        }

        $studentModel = $this->model('Student');
        $student = $studentModel->getStudentByUserId($user['id']);
        if (!$student) die("Student profile not found.");

        $attempt_id = $examModel->startAttempt($student['id'], $id);
        $attempt = $examModel->getAttempt($student['id'], $id);

        if ($attempt['status'] === 'completed') {
            die("You have already completed this exam.");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'submit_exam') {
            $answers = $_POST['answers'] ?? [];
            if ($examModel->submitExam($attempt_id, $answers)) {
                $this->redirect('/exam');
            } else {
                die("Failed to submit exam.");
            }
        }

        $data = [
            'user' => $user,
            'title' => 'Take Exam - ' . $exam['title'],
            'exam' => $exam,
            'questions' => $examModel->getQuestions($id),
            'end_time' => $endTime
        ];

        $this->view('exam/take', $data);
    }

    public function results() {
        $user = class_exists('JWT') ? JWT::getToken() : null; // allow public access
        $data = ['title' => 'Exam Results', 'error' => '', 'results' => null, 'student' => null, 'user' => $user];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reg_no = trim($_POST['register_number'] ?? '');
            $dob = trim($_POST['dob'] ?? ''); 
            
            // Clean up common typo characters like colons or slashes
            $dob = str_replace([':', '/'], '-', $dob);
            
            // Attempt to parse Date of Birth (handles YYYY-MM-DD, DD-MM-YYYY, etc.)
            $time = strtotime($dob);
            if (!$time) {
                // Fallback if parsing fails completely
                $dob_sql = '1970-01-01';
            } else {
                $dob_sql = date('Y-m-d', $time);
            }
            
            $db = (new Model())->db;
            // Join users table to get the student's email/username if needed, but we already have enrollment_no
            $stmt = $db->prepare("SELECT * FROM students WHERE enrollment_no = ? AND dob = ?");
            $stmt->execute([$reg_no, $dob_sql]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($student) {
                $stmtRes = $db->prepare("SELECT * FROM results WHERE student_id = ? ORDER BY exam_name DESC");
                $stmtRes->execute([$student['id']]);
                $results = $stmtRes->fetchAll(PDO::FETCH_ASSOC);
                
                if (empty($results)) {
                    $data['error'] = 'No results published yet for this student.';
                } else {
                    $data['student'] = $student;
                    $data['results'] = $results;
                }
            } else {
                $data['error'] = 'Invalid Registration Number or Date of Birth. Please try again.';
            }
        }
        
        $this->view('exam/results', $data);
    }

    public function publish() {
        $user = JWT::getToken();
        if (!$user || ($user['role'] !== 'admin' && $user['role'] !== 'faculty')) {
            $this->redirect('/dashboard');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reg_no = trim($_POST['register_number'] ?? '');
            $exam_name = trim($_POST['exam_name'] ?? '');
            $subject = trim($_POST['subject'] ?? '');
            $marks = intval($_POST['marks'] ?? 0);
            $max_marks = intval($_POST['max_marks'] ?? 100);
            
            $db = (new Model())->db;
            
            // Find Student ID
            $stmt = $db->prepare("SELECT id FROM students WHERE enrollment_no = ?");
            $stmt->execute([$reg_no]);
            $studentId = $stmt->fetchColumn();
            
            if (!$studentId) {
                // You could flash an error session here, but for simplicity we'll just redirect to exam
                $this->redirect('/exam?error=Student_Not_Found');
            }
            
            // Calculate Grade and GPA
            $percent = ($marks / $max_marks) * 100;
            if ($percent >= 90) { $grade = 'A+'; $gpa = 10.0; }
            elseif ($percent >= 80) { $grade = 'A'; $gpa = 9.0; }
            elseif ($percent >= 70) { $grade = 'B+'; $gpa = 8.0; }
            elseif ($percent >= 60) { $grade = 'B'; $gpa = 7.0; }
            elseif ($percent >= 50) { $grade = 'C'; $gpa = 6.0; }
            else { $grade = 'F'; $gpa = 0.0; }
            
            $insert = $db->prepare("INSERT INTO results (student_id, exam_name, subject, marks, max_marks, grade, gpa) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insert->execute([$studentId, $exam_name, $subject, $marks, $max_marks, $grade, $gpa]);
            
            $this->redirect('/exam?success=Result_Published');
        }
    }
}
