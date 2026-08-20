<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/ExamController.php';
$c = file_get_contents($f);

$publishMethod = <<<'PHP'
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
PHP;

$c = preg_replace('/\}\s*$/', "\n$publishMethod\n}\n", $c);
file_put_contents($f, $c);
echo "Added publish endpoint.\n";
