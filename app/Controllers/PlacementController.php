<?php
// app/Controllers/PlacementController.php

class PlacementController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) {
            $this->redirect('/auth/login');
        }

        $db = (new Model())->db;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($user['role'] === 'admin') {
                if (isset($_POST['add_job'])) {
                    $stmt = $db->prepare("INSERT INTO placement_jobs (company_name, job_title, description, salary_package, deadline) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([
                        $_POST['company_name'],
                        $_POST['job_title'],
                        $_POST['description'],
                        $_POST['salary_package'],
                        $_POST['deadline']
                    ]);
                    $this->redirect('/placement?success=Job+added');
                }
            } else if ($user['role'] === 'student') {
                if (isset($_POST['apply_job'])) {
                    $studentStmt = $db->prepare("SELECT id FROM students WHERE user_id = ?");
                    $studentStmt->execute([$user['id']]);
                    $student_id = $studentStmt->fetchColumn();

                    $stmt = $db->prepare("INSERT INTO placement_applications (job_id, student_id, resume_link) VALUES (?, ?, ?)");
                    $stmt->execute([
                        $_POST['job_id'],
                        $student_id,
                        $_POST['resume_link']
                    ]);
                    $this->redirect('/placement?success=Applied+successfully');
                }
            }
        }

        $data = [
            'user' => $user,
            'title' => 'Placement Center',
            'success' => $_GET['success'] ?? '',
            'error' => $_GET['error'] ?? ''
        ];

        if ($user['role'] === 'admin') {
            $data['jobs'] = $db->query("SELECT * FROM placement_jobs ORDER BY id DESC")->fetchAll();
            $data['applications'] = $db->query("
                SELECT pa.*, pj.company_name, pj.job_title, s.first_name, s.last_name, s.enrollment_no 
                FROM placement_applications pa 
                JOIN placement_jobs pj ON pa.job_id = pj.id 
                JOIN students s ON pa.student_id = s.id 
                ORDER BY pa.id DESC
            ")->fetchAll();
            $this->view('placement/admin', $data);
        } else if ($user['role'] === 'student') {
            $data['jobs'] = $db->query("SELECT * FROM placement_jobs WHERE deadline >= CURDATE() ORDER BY deadline ASC")->fetchAll();
            
            $studentStmt = $db->prepare("SELECT id FROM students WHERE user_id = ?");
            $studentStmt->execute([$user['id']]);
            $student_id = $studentStmt->fetchColumn();

            $myApps = $db->prepare("SELECT job_id FROM placement_applications WHERE student_id = ?");
            $myApps->execute([$student_id]);
            $data['applied_job_ids'] = $myApps->fetchAll(PDO::FETCH_COLUMN);

            $this->view('placement/student', $data);
        } else {
            $this->redirect('/dashboard');
        }
    }
}
