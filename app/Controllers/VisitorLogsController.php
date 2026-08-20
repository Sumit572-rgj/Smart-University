<?php
class VisitorLogsController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }
        
        $db = (new Model())->db;
        $table_name = 'mod_visitorlogs';
        
        $db->exec("CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            visitor_name VARCHAR(100),
            purpose VARCHAR(200),
            entry_time DATETIME,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_data'])) {
            $stmt = $db->prepare("INSERT INTO $table_name (user_id, visitor_name, purpose, entry_time) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user['id'], $_POST['visitor_name'], $_POST['purpose'], $_POST['entry_time']]);
            $this->redirect('/visitorlogs?success=Record+added');
        }

        if (isset($_GET['delete'])) {
            $stmt = $db->prepare("DELETE FROM $table_name WHERE id = ?");
            $stmt->execute([$_GET['delete']]);
            $this->redirect('/visitorlogs?success=Record+deleted');
        }

        $data = [
            'user' => $user,
            'title' => 'Visitor Logs',
            'success' => $_GET['success'] ?? ''
        ];
        
        $data['records'] = $db->query("SELECT t.*, u.username FROM $table_name t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC")->fetchAll();
        
        $this->view('visitorlogs/index', $data);
    }
}
