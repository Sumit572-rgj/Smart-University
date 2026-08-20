<?php
class ResearchPublicationsController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }
        
        $db = (new Model())->db;
        $table_name = 'mod_researchpublications';
        
        $db->exec("CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            title VARCHAR(200),
            journal VARCHAR(100),
            publication_date DATE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_data'])) {
            $stmt = $db->prepare("INSERT INTO $table_name (user_id, title, journal, publication_date) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user['id'], $_POST['title'], $_POST['journal'], $_POST['publication_date']]);
            $this->redirect('/researchpublications?success=Record+added');
        }

        if (isset($_GET['delete'])) {
            $stmt = $db->prepare("DELETE FROM $table_name WHERE id = ?");
            $stmt->execute([$_GET['delete']]);
            $this->redirect('/researchpublications?success=Record+deleted');
        }

        $data = [
            'user' => $user,
            'title' => 'Research Publications',
            'success' => $_GET['success'] ?? ''
        ];
        
        $data['records'] = $db->query("SELECT t.*, u.username FROM $table_name t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC")->fetchAll();
        
        $this->view('researchpublications/index', $data);
    }
}
