<?php
// app/Controllers/StudentController.php

class StudentController extends Controller {
        public function edit($user_id = null) {
        $user = JWT::getToken();
        if (!$user || !in_array($user['role'], ['admin', 'faculty'])) {
            $this->redirect('/dashboard');
        }
        
        $studentModel = $this->model('Student');
        $db = (new Model())->db;
        
        $student = $studentModel->getStudentByUserId($user_id);
        if (!$student) $this->redirect('/student');
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['action']) && $_POST['action'] === 'upload_doc') {
                $doc_type = filter_input(INPUT_POST, 'document_type', FILTER_SANITIZE_STRING);
                if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../../public/uploads/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    
                    $safe_name = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($_FILES['document']['name']));
                    $filename = time() . '_' . $safe_name;
                    $destPath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($_FILES['document']['tmp_name'], $destPath)) {
                        $stmt = $db->prepare("INSERT INTO student_documents (student_id, document_name, document_type, file_path) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$student['id'], basename($_FILES['document']['name']), $doc_type, BASE_URL . 'uploads/' . $filename]);
                        $success = 'Document uploaded successfully!';
                    } else {
                        $error = 'Failed to move uploaded file.';
                    }
                }
            } else if (isset($_POST['action']) && $_POST['action'] === 'update_doc') {
                $doc_id = $_POST['document_id'];
                $new_name = filter_input(INPUT_POST, 'new_name', FILTER_SANITIZE_STRING);
                if ($new_name) {
                    $stmt = $db->prepare("UPDATE student_documents SET document_name = ? WHERE id = ?");
                    $stmt->execute([$new_name, $doc_id]);
                    $success = 'Document renamed successfully!';
                }
            } else if (isset($_POST['action']) && $_POST['action'] === 'replace_doc') {
                $doc_id = $_POST['document_id'];
                if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
                    // Fetch old to delete
                    $stmt = $db->prepare("SELECT file_path FROM student_documents WHERE id = ? AND student_id = ?");
                    $stmt->execute([$doc_id, $student['id']]);
                    $doc = $stmt->fetch();
                    if ($doc) {
                        $path = __DIR__ . '/../../' . str_replace(BASE_URL . '', '', $doc['file_path']);
                        if (file_exists($path)) @unlink($path);
                    }
                    
                    $uploadDir = __DIR__ . '/../../public/uploads/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    $safe_name = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', basename($_FILES['document']['name']));
                    $filename = time() . '_' . $safe_name;
                    $destPath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($_FILES['document']['tmp_name'], $destPath)) {
                        $db->prepare("UPDATE student_documents SET document_name = ?, file_path = ? WHERE id = ?")
                           ->execute([basename($_FILES['document']['name']), BASE_URL . 'uploads/' . $filename, $doc_id]);
                        $success = 'Document replaced successfully!';
                    }
                }
            } else if (isset($_POST['action']) && $_POST['action'] === 'delete_doc') {
                $doc_id = $_POST['document_id'];
                // Fetch to delete file
                $stmt = $db->prepare("SELECT file_path FROM student_documents WHERE id = ? AND student_id = ?");
                $stmt->execute([$doc_id, $student['id']]);
                $doc = $stmt->fetch();
                if ($doc) {
                    $path = __DIR__ . '/../../' . str_replace(BASE_URL . '', '', $doc['file_path']);
                    if (file_exists($path)) @unlink($path);
                    $db->prepare("DELETE FROM student_documents WHERE id = ?")->execute([$doc_id]);
                    $success = 'Document deleted successfully!';
                }
            } else {
                $data = [
                    'enrollment_no' => filter_input(INPUT_POST, 'enrollment_no', FILTER_SANITIZE_STRING),
                    'first_name' => filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING),
                    'last_name' => filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING),
                    'dob' => filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING),
                    'department' => filter_input(INPUT_POST, 'department', FILTER_SANITIZE_STRING),
                    'section' => filter_input(INPUT_POST, 'section', FILTER_SANITIZE_STRING),
                    'batch' => filter_input(INPUT_POST, 'batch', FILTER_SANITIZE_STRING),
                    'phone' => filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING),
                    'admission_status' => filter_input(INPUT_POST, 'admission_status', FILTER_SANITIZE_STRING),
                    'current_semester' => filter_input(INPUT_POST, 'current_semester', FILTER_SANITIZE_NUMBER_INT),
                    'cgpa' => filter_input(INPUT_POST, 'cgpa', FILTER_SANITIZE_STRING),
                ];
                
                if ($studentModel->updateStudent($user_id, $data)) {
                    $this->redirect('/student?success=Student+updated+successfully');
                    exit;
                } else {
                    $error = 'Failed to update student';
                }
            }
        }
        
        $stmt = $db->prepare("SELECT * FROM student_documents WHERE student_id = ? ORDER BY uploaded_at DESC");
        $stmt->execute([$student['id']]);
        $documents = $stmt->fetchAll();
        
        $this->view('student/edit', [
            'user' => $user,
            'title' => 'Edit Student',
            'student' => $student,
            'documents' => $documents,
            'success' => $success,
            'error' => $error
        ]);
    }

    public function index() {
        $user = JWT::getToken();
        if (!$user || !in_array($user['role'], ['admin', 'faculty'])) {
            $this->redirect('/dashboard');
        }

        $studentModel = $this->model('Student');
        $db = (new Model())->db;
        $facultyDept = null;
        if ($user['role'] === 'faculty') {
            $stmt = $db->prepare("SELECT department FROM faculty WHERE user_id = ?");
            $stmt->execute([$user['id']]);
            $facultyDept = $stmt->fetchColumn();
        }
        $data['facultyDept'] = $facultyDept;
        
        $data = [
            'user' => $user,
            'title' => 'Student Management',
            'success' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['action']) && $_POST['action'] === 'delete') {
                $user_id_to_delete = $_POST['user_id'];
                if ($studentModel->deleteStudent($user_id_to_delete)) {
                    $data['success'] = 'Student deleted successfully.';
                } else {
                    $data['error'] = 'Failed to delete student.';
                }
            } elseif (isset($_POST['action']) && $_POST['action'] === 'create_parent') {
                $student_id = $_POST['student_id'];
                $parent_username = filter_input(INPUT_POST, 'parent_username', FILTER_SANITIZE_STRING);
                $parent_password = $_POST['parent_password'];
                $parent_email = filter_input(INPUT_POST, 'parent_email', FILTER_SANITIZE_EMAIL);
                $relation = filter_input(INPUT_POST, 'relation', FILTER_SANITIZE_STRING);
                $contact = filter_input(INPUT_POST, 'contact_number', FILTER_SANITIZE_STRING);

                $db = (new Model())->db;
                                $checkParent = $db->prepare("SELECT id FROM parent_profiles WHERE student_id = ?");
                $checkParent->execute([$student_id]);
                
                $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                $stmt->execute([$parent_username, $parent_email]);
                
                if ($checkParent->fetch()) {
                    $data['error'] = 'This student is already linked to a parent account. Only one parent is allowed per student.';
                } else if ($stmt->fetch()) {
                    $data['error'] = 'Parent username or email already exists.';
                } else {
                    $pwd = password_hash($parent_password, PASSWORD_DEFAULT);
                    $db->prepare("INSERT INTO users (username, password_hash, email, role) VALUES (?, ?, ?, 'parent')")
                       ->execute([$parent_username, $pwd, $parent_email]);
                    $parent_user_id = $db->lastInsertId();

                    $db->prepare("INSERT INTO parent_profiles (user_id, student_id, relation, contact_number) VALUES (?, ?, ?, ?)")
                       ->execute([$parent_user_id, $student_id, $relation, $contact]);

                    $data['success'] = 'Parent account created successfully and linked to student.';
                }
            } else {
                $postData = [
                    'enrollment_no' => filter_input(INPUT_POST, 'enrollment_no', FILTER_SANITIZE_STRING),
                    'first_name' => filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING),
                    'last_name' => filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING),
                    'dob' => filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING),
                    'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                                        'department' => ($user['role'] === 'faculty') ? $facultyDept : filter_input(INPUT_POST, 'department', FILTER_SANITIZE_STRING),
                    'section' => filter_input(INPUT_POST, 'section', FILTER_SANITIZE_STRING),
                    'batch' => filter_input(INPUT_POST, 'batch', FILTER_SANITIZE_STRING),
                    'phone' => filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING),
                    'admission_status' => filter_input(INPUT_POST, 'admission_status', FILTER_SANITIZE_STRING) ?: 'admitted',
                    'current_semester' => filter_input(INPUT_POST, 'current_semester', FILTER_SANITIZE_NUMBER_INT) ?: 1,
                    'cgpa' => filter_input(INPUT_POST, 'cgpa', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) ?: 0.00
                ];

                $result = $studentModel->createStudent($postData);
                if ($result === true) {
                    $data['success'] = 'Student added successfully.';
                } else {
                    $data['error'] = 'Failed to add student: ' . $result;
                }
            }
        }

                $data['students'] = ($user['role'] === 'faculty') ? $studentModel->getStudentsByDepartment($facultyDept) : $studentModel->getAllStudents();
        $this->view('student/index', $data);
    }

        public function profile() {
        $user = JWT::getToken();
        if (!$user || $user['role'] !== 'student') {
            $this->redirect('/dashboard');
        }

        $studentModel = $this->model('Student');
        $db = (new Model())->db;
        $facultyDept = null;
        if ($user['role'] === 'faculty') {
            $stmt = $db->prepare("SELECT department FROM faculty WHERE user_id = ?");
            $stmt->execute([$user['id']]);
            $facultyDept = $stmt->fetchColumn();
        }
        $data['facultyDept'] = $facultyDept;
        $student = $studentModel->getStudentByUserId($user['id']);

        if (!$student) {
            die("Student profile not found.");
        }

        $data = [
            'user' => $user,
            'title' => 'My Profile',
            'student' => $student,
            'success' => $_GET['success'] ?? '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
            $file = $_FILES['profile_pic'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'profile_' . time() . '.' . $ext;
                $uploadDir = __DIR__ . '/../../public/uploads/profiles/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $target = $uploadDir . $filename;
                if (move_uploaded_file($file['tmp_name'], $target)) {
                    $picUrl = BASE_URL . 'uploads/profiles/' . $filename;
                    $db->query("UPDATE students SET profile_pic = '$picUrl' WHERE id = " . $student['id']);
                    $this->redirect('/student/profile?success=Profile picture updated successfully.');
                } else {
                    $data['error'] = 'Failed to move uploaded file.';
                }
            } else {
                $data['error'] = 'Upload error code: ' . $file['error'];
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['document'])) {
            $docType = $_POST['document_type'];
            $docName = $_FILES['document']['name'];
            $tmpName = $_FILES['document']['tmp_name'];
            
            $uploadDir = __DIR__ . '/../../public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $filename = uniqid() . '_' . basename($docName);
            $targetPath = $uploadDir . $filename;
            
            if (move_uploaded_file($tmpName, $targetPath)) {
                $stmt = $db->prepare("INSERT INTO student_documents (student_id, document_name, document_type, file_path) VALUES (?, ?, ?, ?)");
                $stmt->execute([$student['id'], $docName, $docType, BASE_URL . 'uploads/' . $filename]);
                $this->redirect('/student/profile?success=Document+uploaded+successfully');
            }
        }

        $data['documents'] = $db->query("SELECT * FROM student_documents WHERE student_id = " . $student['id'])->fetchAll();

        $this->view('student/profile', $data);
    }
}
