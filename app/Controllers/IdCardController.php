<?php
// app/Controllers/IdCardController.php

class IdCardController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }

        // Fetch mock details based on role
        $details = [];
        if ($user['role'] == 'student') {
            $details = [
                'name' => 'John Doe',
                'id_number' => 'CIT2023CS001',
                'dept' => 'Computer Science & Engg',
                'blood' => 'O+',
                'valid_till' => '2027'
            ];
        } else {
            $details = [
                'name' => 'Admin User',
                'id_number' => 'EMP-001',
                'dept' => 'Administration',
                'blood' => 'A+',
                'valid_till' => 'N/A'
            ];
        }

        $data = ['user' => $user, 'title' => 'Virtual ID Card', 'details' => $details];
        $this->view('idcard/index', $data);
    }
}
