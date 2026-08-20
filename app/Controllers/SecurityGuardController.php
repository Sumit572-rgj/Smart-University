<?php
// app/Controllers/SecurityGuardController.php

class SecurityGuardController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user || $user['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }

        $db = (new Model())->db;
        $data = [
            'user' => $user,
            'title' => 'Security Guard Management',
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
                        $stmt = $db->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'security')");
                        $stmt->execute([$username, $email, $hash]);
                        $data['success'] = 'Security Guard added successfully.';
                    } catch (PDOException $e) {
                        $data['error'] = 'Failed to add security guard (username/email might exist).';
                    }
                } else {
                    $data['error'] = 'Please fill all fields.';
                }
            } else if (isset($_POST['action']) && $_POST['action'] == 'delete') {
                $guard_id = $_POST['guard_id'];
                try {
                    $stmt = $db->prepare("DELETE FROM users WHERE id = ? AND role = 'security'");
                    $stmt->execute([$guard_id]);
                    $data['success'] = 'Security Guard deleted successfully.';
                } catch (PDOException $e) {
                    $data['error'] = 'Failed to delete security guard.';
                }
            }
        }

        $data['guards'] = $db->query("SELECT id, username, email, created_at FROM users WHERE role = 'security'")->fetchAll();
        $this->view('security_guard/index', $data);
    }
}
