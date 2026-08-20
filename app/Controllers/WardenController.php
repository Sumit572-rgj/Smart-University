<?php
// app/Controllers/WardenController.php

class WardenController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user || $user['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }

        $db = (new Model())->db;
        $data = [
            'user' => $user,
            'title' => 'Warden Management',
            'success' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['action']) && $_POST['action'] == 'create') {
                $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'];

                if ($username && $email && $password) {
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    try {
                        $stmt = $db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'warden')");
                        $stmt->execute([$username, $email, $hash]);
                        $data['success'] = 'Warden added successfully.';
                    } catch (PDOException $e) {
                        $data['error'] = 'Failed to add warden (username/email might exist).';
                    }
                } else {
                    $data['error'] = 'Please fill all fields.';
                }
            } else if (isset($_POST['action']) && $_POST['action'] == 'delete') {
                $warden_id = $_POST['warden_id'];
                try {
                    $stmt = $db->prepare("DELETE FROM users WHERE id = ? AND role = 'warden'");
                    $stmt->execute([$warden_id]);
                    $data['success'] = 'Warden deleted successfully.';
                } catch (PDOException $e) {
                    $data['error'] = 'Failed to delete warden.';
                }
            }
        }

        $data['wardens'] = $db->query("SELECT id, username, email, created_at FROM users WHERE role = 'warden'")->fetchAll();
        $this->view('warden/index', $data);
    }
}
