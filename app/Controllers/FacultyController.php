<?php
// app/Controllers/FacultyController.php

class FacultyController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user || $user['role'] !== 'admin') {
            $this->redirect('/dashboard');
        }

        $facultyModel = $this->model('Faculty');
        
        $data = [
            'user' => $user,
            'title' => 'Faculty Management',
            'success' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['action']) && $_POST['action'] === 'delete') {
                $user_id_to_delete = $_POST['user_id'];
                if ($facultyModel->deleteFaculty($user_id_to_delete)) {
                    $data['success'] = 'Faculty deleted successfully.';
                } else {
                    $data['error'] = 'Failed to delete faculty.';
                }
            } else {
                $postData = [
                    'employee_id' => $_POST['employee_id'],
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name'],
                    'email' => $_POST['email'],
                    'department' => $_POST['department'],
                    'designation' => $_POST['designation']
                ];

                if ($facultyModel->createFaculty($postData)) {
                    $data['success'] = 'Faculty member added successfully.';
                } else {
                    $data['error'] = 'Failed to add faculty. Employee ID or Email might already exist.';
                }
            }
        }

        $data['faculty'] = $facultyModel->getAllFaculty();
        $this->view('faculty/index', $data);
    }
}
