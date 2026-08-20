<?php
// app/Controllers/TimetableController.php

class TimetableController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }

        $timetableModel = $this->model('Timetable');
        // Mock data: Assume student is in CSE, Semester 5
        $data = [
            'user' => $user, 
            'title' => 'Smart Timetable', 
            'timetable' => $timetableModel->getStudentTimetable('CSE', 5)
        ];
        
        $this->view('timetable/index', $data);
    }
}
