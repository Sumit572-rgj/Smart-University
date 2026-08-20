<?php
class DepartmentMeetingsController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }
        
        $db = (new Model())->db;
        $table_name = 'mod_departmentmeetings';
        
        $db->exec("CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            meeting_title VARCHAR(100),
            meeting_date DATE,
            agenda TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_data'])) {
            $stmt = $db->prepare("INSERT INTO $table_name (user_id, meeting_title, meeting_date, agenda) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user['id'], $_POST['meeting_title'], $_POST['meeting_date'], $_POST['agenda']]);
            $this->redirect('/departmentmeetings?success=Record+added');
        }

        if (isset($_GET['delete'])) {
            $stmt = $db->prepare("DELETE FROM $table_name WHERE id = ?");
            $stmt->execute([$_GET['delete']]);
            $this->redirect('/departmentmeetings?success=Record+deleted');
        }

        $data = [
            'user' => $user,
            'title' => 'Department Meetings',
            'success' => $_GET['success'] ?? ''
        ];
        
        $data['records'] = $db->query("SELECT t.*, u.username FROM $table_name t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC")->fetchAll();
        
        $this->view('departmentmeetings/index', $data);
    }
}
