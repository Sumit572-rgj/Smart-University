<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/StudentController.php';
$c = file_get_contents($f);

// Replace the entire profile() function to clean up the duplicates
$pattern = '/public function profile\(\) \{.*?\n    \}/s';

$replacement = <<<PHP
    public function profile() {
        \$user = JWT::getToken();
        if (!\$user || \$user['role'] !== 'student') {
            \$this->redirect('/dashboard');
        }

        \$studentModel = \$this->model('Student');
        \$db = (new Model())->db;
        \$facultyDept = null;
        if (\$user['role'] === 'faculty') {
            \$stmt = \$db->prepare("SELECT department FROM faculty WHERE user_id = ?");
            \$stmt->execute([\$user['id']]);
            \$facultyDept = \$stmt->fetchColumn();
        }
        \$data['facultyDept'] = \$facultyDept;
        \$student = \$studentModel->getStudentByUserId(\$user['id']);

        if (!\$student) {
            die("Student profile not found.");
        }

        \$data = [
            'user' => \$user,
            'title' => 'My Profile',
            'student' => \$student,
            'success' => \$_GET['success'] ?? '',
            'error' => ''
        ];

        if (\$_SERVER['REQUEST_METHOD'] === 'POST' && isset(\$_FILES['profile_pic'])) {
            \$file = \$_FILES['profile_pic'];
            if (\$file['error'] === UPLOAD_ERR_OK) {
                \$ext = pathinfo(\$file['name'], PATHINFO_EXTENSION);
                \$filename = 'profile_' . time() . '.' . \$ext;
                \$uploadDir = 'C:/xampp/htdocs/cit_ums/public/uploads/profiles/';
                if (!is_dir(\$uploadDir)) {
                    mkdir(\$uploadDir, 0777, true);
                }
                \$target = \$uploadDir . \$filename;
                if (move_uploaded_file(\$file['tmp_name'], \$target)) {
                    \$picUrl = '/cit_ums/uploads/profiles/' . \$filename;
                    \$db->query("UPDATE students SET profile_pic = '\$picUrl' WHERE id = " . \$student['id']);
                    \$this->redirect('/student/profile?success=Profile picture updated successfully.');
                } else {
                    \$data['error'] = 'Failed to move uploaded file.';
                }
            } else {
                \$data['error'] = 'Upload error code: ' . \$file['error'];
            }
        }

        if (\$_SERVER['REQUEST_METHOD'] === 'POST' && isset(\$_FILES['document'])) {
            \$docType = \$_POST['document_type'];
            \$docName = \$_FILES['document']['name'];
            \$tmpName = \$_FILES['document']['tmp_name'];
            
            \$uploadDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir(\$uploadDir)) {
                mkdir(\$uploadDir, 0777, true);
            }
            
            \$filename = uniqid() . '_' . basename(\$docName);
            \$targetPath = \$uploadDir . \$filename;
            
            if (move_uploaded_file(\$tmpName, \$targetPath)) {
                \$stmt = \$db->prepare("INSERT INTO student_documents (student_id, document_name, document_type, file_path) VALUES (?, ?, ?, ?)");
                \$stmt->execute([\$student['id'], \$docName, \$docType, '/cit_ums/uploads/' . \$filename]);
                \$this->redirect('/student/profile?success=Document+uploaded+successfully');
            }
        }

        \$data['documents'] = \$db->query("SELECT * FROM student_documents WHERE student_id = " . \$student['id'])->fetchAll();

        \$this->view('student/profile', \$data);
    }
PHP;

$c = preg_replace($pattern, $replacement, $c, 1);
file_put_contents($f, $c);
echo "StudentController profile method cleaned up.";
