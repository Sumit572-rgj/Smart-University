<?php
// app/Controllers/SecurityController.php

class SecurityController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user || $user['role'] !== 'admin') { 
            $this->redirect('/dashboard'); 
        }

        $securityModel = $this->model('Security');
        $data = [
            'user' => $user, 
            'title' => 'Security & Activity Logs', 
            'logs' => $securityModel->getRecentLogs(100)
        ];
        
        $this->view('security/index', $data);
    }
}
