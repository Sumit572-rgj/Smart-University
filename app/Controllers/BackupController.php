<?php
// app/Controllers/BackupController.php

class BackupController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user || $user['role'] !== 'admin') { 
            $this->redirect('/dashboard'); 
        }

        $data = ['user' => $user, 'title' => 'Cloud Backup', 'success' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Mock backup generation
            $filename = 'backup_cit_ums_' . date('Y_m_d_H_i_s') . '.sql';
            $data['success'] = "Backup generated successfully: $filename (Simulated)";
        }
        
        $this->view('backup/index', $data);
    }
}
