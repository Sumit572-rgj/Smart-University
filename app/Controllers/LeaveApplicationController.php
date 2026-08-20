<?php
class LeaveApplicationController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }
        
        $db = (new Model())->db;
        $table_name = 'mod_leaveapplication';
        
        $db->exec("CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            leave_type VARCHAR(50),
            start_date DATE,
            end_date DATE,
            reason TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_data'])) {
            $stmt = $db->prepare("INSERT INTO $table_name (user_id, leave_type, start_date, end_date, reason) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$user['id'], $_POST['leave_type'], $_POST['start_date'], $_POST['end_date'], $_POST['reason']]);
            $this->redirect('/leaveapplication?success=Record+added');
        }

        if (isset($_GET['delete'])) {
            $stmt = $db->prepare("DELETE FROM $table_name WHERE id = ?");
            $stmt->execute([$_GET['delete']]);
            $this->redirect('/leaveapplication?success=Record+deleted');
        }

        $data = [
            'user' => $user,
            'title' => 'Leave Application',
            'success' => $_GET['success'] ?? ''
        ];
        
        $data['records'] = $db->query("SELECT t.*, u.username FROM $table_name t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC")->fetchAll();
        
        $this->view('leaveapplication/index', $data);
    }
}
