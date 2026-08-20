<?php
class DisciplinaryRecordsController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }
        
        $db = (new Model())->db;
        $table_name = 'mod_disciplinaryrecords';
        
        $db->exec("CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            incident VARCHAR(200),
            action_taken TEXT,
            incident_date DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_data'])) {
            $stmt = $db->prepare("INSERT INTO $table_name (user_id, incident, action_taken, incident_date) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user['id'], $_POST['incident'], $_POST['action_taken'], $_POST['incident_date']]);
            $this->redirect('/disciplinaryrecords?success=Record+added');
        }

        if (isset($_GET['delete'])) {
            $stmt = $db->prepare("DELETE FROM $table_name WHERE id = ?");
            $stmt->execute([$_GET['delete']]);
            $this->redirect('/disciplinaryrecords?success=Record+deleted');
        }

        $data = [
            'user' => $user,
            'title' => 'Disciplinary Records',
            'success' => $_GET['success'] ?? ''
        ];
        
        $data['records'] = $db->query("SELECT t.*, u.username FROM $table_name t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC")->fetchAll();
        
        $this->view('disciplinaryrecords/index', $data);
    }
}
