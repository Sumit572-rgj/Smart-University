<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Models/Student.php';
$c = file_get_contents($f);

// Add updateStudent method to Model
$updateMethod = <<<PHP
    public function updateStudent(\$user_id, \$data) {
        \$stmt = \$this->db->prepare("UPDATE students SET 
            enrollment_no = :enrollment_no, 
            first_name = :first_name, 
            last_name = :last_name, 
            department = :department, 
            section = :section, 
            batch = :batch, 
            phone = :phone, 
            admission_status = :admission_status, 
            current_semester = :current_semester, 
            cgpa = :cgpa 
            WHERE user_id = :user_id");
            
        return \$stmt->execute([
            'enrollment_no' => \$data['enrollment_no'],
            'first_name' => \$data['first_name'],
            'last_name' => \$data['last_name'],
            'department' => \$data['department'],
            'section' => \$data['section'] ?? null,
            'batch' => \$data['batch'],
            'phone' => \$data['phone'],
            'admission_status' => \$data['admission_status'],
            'current_semester' => \$data['current_semester'],
            'cgpa' => \$data['cgpa'],
            'user_id' => \$user_id
        ]);
    }
    
    public function getStudentByUserId(\$user_id) {
        \$stmt = \$this->db->prepare("SELECT s.*, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE s.user_id = ?");
        \$stmt->execute([\$user_id]);
        return \$stmt->fetch();
    }
PHP;

$c = str_replace("public function deleteStudent(\$user_id) {", $updateMethod . "\n\n    public function deleteStudent(\$user_id) {", $c);
file_put_contents($f, $c);


// Add edit method to Controller
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/StudentController.php';
$c = file_get_contents($f);
$editMethod = <<<PHP
    public function edit(\$user_id = null) {
        \$user = JWT::getToken();
        if (!\$user || !in_array(\$user['role'], ['admin', 'faculty'])) {
            \$this->redirect('/dashboard');
        }
        
        \$studentModel = \$this->model('Student');
        
        if (\$_SERVER['REQUEST_METHOD'] == 'POST') {
            \$data = [
                'enrollment_no' => filter_input(INPUT_POST, 'enrollment_no', FILTER_SANITIZE_STRING),
                'first_name' => filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING),
                'last_name' => filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING),
                'department' => filter_input(INPUT_POST, 'department', FILTER_SANITIZE_STRING),
                'section' => filter_input(INPUT_POST, 'section', FILTER_SANITIZE_STRING),
                'batch' => filter_input(INPUT_POST, 'batch', FILTER_SANITIZE_STRING),
                'phone' => filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING),
                'admission_status' => filter_input(INPUT_POST, 'admission_status', FILTER_SANITIZE_STRING),
                'current_semester' => filter_input(INPUT_POST, 'current_semester', FILTER_SANITIZE_NUMBER_INT),
                'cgpa' => filter_input(INPUT_POST, 'cgpa', FILTER_SANITIZE_STRING),
            ];
            
            if (\$studentModel->updateStudent(\$user_id, \$data)) {
                \$this->redirect('/student?success=Student+updated+successfully');
            } else {
                \$this->redirect('/student?error=Failed+to+update+student');
            }
        }
        
        \$student = \$studentModel->getStudentByUserId(\$user_id);
        if (!\$student) \$this->redirect('/student');
        
        \$this->view('student/edit', [
            'user' => \$user,
            'title' => 'Edit Student',
            'student' => \$student
        ]);
    }
PHP;
$c = str_replace("public function index() {", $editMethod . "\n\n    public function index() {", $c);
file_put_contents($f, $c);


// Add Edit button to View
$f = 'C:/xampp/htdocs/cit_ums/app/Views/student/index.php';
$c = file_get_contents($f);
$newAction = <<<HTML
                                      <a href="/cit_ums/student/edit/<?= \$student['user_id'] ?>" class="text-blue-500 hover:underline text-xs mr-2">Edit</a>
                                      <form action="/cit_ums/student" method="POST" onsubmit="return confirm('Are you sure you want to delete this student?');" style="display:inline-block;">
HTML;
$c = str_replace('<form action="/cit_ums/student" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this student?\');">', $newAction, $c);
file_put_contents($f, $c);

echo "Update and Edit logic integrated.";
