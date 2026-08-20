<?php
// app/Controllers/HealthController.php

class HealthController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) {
            $this->redirect('/auth/login');
        }

        $healthModel = $this->model('Health');

        if ($user['role'] === 'student') {
            $data = [
                'user' => $user,
                'title' => 'Health & Wellness',
                'success' => '',
                'error' => ''
            ];

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $postData = [
                    'blood_group' => $_POST['blood_group'],
                    'medical_conditions' => filter_input(INPUT_POST, 'medical_conditions', FILTER_SANITIZE_STRING),
                    'allergies' => filter_input(INPUT_POST, 'allergies', FILTER_SANITIZE_STRING),
                    'emergency_contact' => filter_input(INPUT_POST, 'emergency_contact', FILTER_SANITIZE_STRING),
                    'emergency_phone' => filter_input(INPUT_POST, 'emergency_phone', FILTER_SANITIZE_STRING)
                ];

                if ($healthModel->saveRecord(1, $postData)) { // Mock student 1
                    $data['success'] = 'Health records updated successfully.';
                } else {
                    $data['error'] = 'Failed to update records.';
                }
            }

            $data['record'] = $healthModel->getRecordByStudent(1);
            $this->view('health/student', $data);
        } else {
            $this->redirect('/dashboard');
        }
    }
}
