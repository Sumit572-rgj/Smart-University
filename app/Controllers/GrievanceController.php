<?php
// app/Controllers/GrievanceController.php

class GrievanceController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }

        $grievanceModel = $this->model('Grievance');
        $data = ['user' => $user, 'title' => 'Grievance System', 'success' => '', 'error' => ''];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
            $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
            $is_anonymous = isset($_POST['anonymous']) ? true : false;
            
            $student_id = $is_anonymous ? null : 1; // Mock student 1

            if ($grievanceModel->submitGrievance($student_id, $category, $subject, $description)) {
                $data['success'] = 'Grievance submitted successfully.';
            } else {
                $data['error'] = 'Failed to submit grievance.';
            }
        }
        
        $data['my_grievances'] = $grievanceModel->getStudentGrievances(1);
        $this->view('grievance/index', $data);
    }
}
