<?php
class LaundryManagementController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }
        
        $db = (new Model())->db;
        $table_name = 'mod_laundrymanagement';
        
        $db->exec("CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            bag_id VARCHAR(50),
            weight_kg DECIMAL(5,2),
            status VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_data'])) {
            $stmt = $db->prepare("INSERT INTO $table_name (user_id, bag_id, weight_kg, status) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user['id'], $_POST['bag_id'], $_POST['weight_kg'], $_POST['status']]);
            $this->redirect('/laundrymanagement?success=Record+added');
        }

        if (isset($_GET['delete'])) {
            $stmt = $db->prepare("DELETE FROM $table_name WHERE id = ?");
            $stmt->execute([$_GET['delete']]);
            $this->redirect('/laundrymanagement?success=Record+deleted');
        }

        $data = [
            'user' => $user,
            'title' => 'Laundry Management',
            'success' => $_GET['success'] ?? ''
        ];
        
        $data['records'] = $db->query("SELECT t.*, u.username FROM $table_name t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC")->fetchAll();
        
        $this->view('laundrymanagement/index', $data);
    }
}
