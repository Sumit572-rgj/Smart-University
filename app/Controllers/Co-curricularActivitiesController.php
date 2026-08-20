<?php
class Co-curricularActivitiesController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }
        
        $db = (new Model())->db;
        $table_name = 'generic_cocurricularactivities';
        
        // Auto-create table if it doesn't exist for generic CRUD
        $db->exec("CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            data TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_data'])) {
            $stmt = $db->prepare("INSERT INTO $table_name (user_id, data) VALUES (?, ?)");
            $stmt->execute([$user['id'], $_POST['data']]);
            $this->redirect('/cocurricularactivities?success=Record+added');
        }

        if (isset($_GET['delete'])) {
            $stmt = $db->prepare("DELETE FROM $table_name WHERE id = ?");
            $stmt->execute([$_GET['delete']]);
            $this->redirect('/cocurricularactivities?success=Record+deleted');
        }

        $data = [
            'user' => $user,
            'title' => 'Co-curricular Activities',
            'success' => $_GET['success'] ?? ''
        ];
        
        $data['records'] = $db->query("SELECT t.*, u.username FROM $table_name t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC")->fetchAll();
        
        $this->view('cocurricularactivities/index', $data);
    }
}
