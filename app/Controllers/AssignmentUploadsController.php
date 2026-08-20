<?php
class AssignmentUploadsController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }
        
        $db = (new Model())->db;
        $table_name = 'mod_assignmentuploads';
        
        $db->exec("CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            course_code VARCHAR(50),
            title VARCHAR(100),
            file_link VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_data'])) {
            $stmt = $db->prepare("INSERT INTO $table_name (user_id, course_code, title, file_link) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user['id'], $_POST['course_code'], $_POST['title'], $_POST['file_link']]);
            $this->redirect('/assignmentuploads?success=Record+added');
        }

        if (isset($_GET['delete'])) {
            $stmt = $db->prepare("DELETE FROM $table_name WHERE id = ?");
            $stmt->execute([$_GET['delete']]);
            $this->redirect('/assignmentuploads?success=Record+deleted');
        }

        $data = [
            'user' => $user,
            'title' => 'Assignment Uploads',
            'success' => $_GET['success'] ?? ''
        ];
        
        $data['records'] = $db->query("SELECT t.*, u.username FROM $table_name t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC")->fetchAll();
        
        $this->view('assignmentuploads/index', $data);
    }
}
