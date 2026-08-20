<?php
class RoomMaintenanceController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }
        
        $db = (new Model())->db;
        $table_name = 'mod_roommaintenance';
        
        $db->exec("CREATE TABLE IF NOT EXISTS $table_name (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            room_no VARCHAR(20),
            issue TEXT,
            status VARCHAR(50) DEFAULT 'Pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_data'])) {
            $stmt = $db->prepare("INSERT INTO $table_name (user_id, room_no, issue, status) VALUES (?, ?, ?, 'Pending')");
            $stmt->execute([$user['id'], $_POST['room_no'], $_POST['issue']]);
            $this->redirect('/roommaintenance?success=Issue+reported+successfully');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
            if ($user['role'] === 'warden' || $user['role'] === 'admin') {
                $stmt = $db->prepare("UPDATE $table_name SET status = ? WHERE id = ?");
                $stmt->execute([$_POST['status'], $_POST['issue_id']]);
                $this->redirect('/roommaintenance?success=Status+updated');
            }
        }

        if (isset($_GET['delete'])) {
            $stmt = $db->prepare("DELETE FROM $table_name WHERE id = ?");
            $stmt->execute([$_GET['delete']]);
            $this->redirect('/roommaintenance?success=Record+deleted');
        }

        $data = [
            'user' => $user,
            'title' => 'Room Maintenance',
            'success' => $_GET['success'] ?? ''
        ];
        
        if ($user['role'] === 'student') {
            $stmt = $db->prepare("SELECT t.*, u.username FROM $table_name t JOIN users u ON t.user_id = u.id WHERE t.user_id = ? ORDER BY t.created_at DESC");
            $stmt->execute([$user['id']]);
            $data['records'] = $stmt->fetchAll();
        } else {
            $data['records'] = $db->query("SELECT t.*, u.username FROM $table_name t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC")->fetchAll();
        }
        
        $this->view('roommaintenance/index', $data);
    }
}
