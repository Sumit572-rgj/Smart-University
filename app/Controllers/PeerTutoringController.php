<?php
class PeerTutoringController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }
        
        $db = (new Model())->db;
        $table_name = 'mod_peertutoring';
        
        $db->exec("CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            subject VARCHAR(100),
            available_time VARCHAR(100),
            contact VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_data'])) {
            $stmt = $db->prepare("INSERT INTO $table_name (user_id, subject, available_time, contact) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user['id'], $_POST['subject'], $_POST['available_time'], $_POST['contact']]);
            $this->redirect('/peertutoring?success=Record+added');
        }

        if (isset($_GET['delete'])) {
            $stmt = $db->prepare("DELETE FROM $table_name WHERE id = ?");
            $stmt->execute([$_GET['delete']]);
            $this->redirect('/peertutoring?success=Record+deleted');
        }

        $data = [
            'user' => $user,
            'title' => 'Peer Tutoring',
            'success' => $_GET['success'] ?? ''
        ];
        
        $data['records'] = $db->query("SELECT t.*, u.username FROM $table_name t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC")->fetchAll();
        
        $this->view('peertutoring/index', $data);
    }
}
