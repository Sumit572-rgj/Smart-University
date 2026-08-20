<?php
// app/Controllers/DisciplineController.php

class DisciplineController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user || $user['role'] !== 'student') { $this->redirect('/dashboard'); }

        $disciplineModel = $this->model('Discipline');
        $data = [
            'user' => $user, 
            'title' => 'Disciplinary Records', 
            'records' => $disciplineModel->getStudentRecords(1) // Mock student 1
        ];
        $this->view('discipline/index', $data);
    }
}
