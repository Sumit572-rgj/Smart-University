<?php
// app/Controllers/AlumniController.php

class AlumniController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }

        $alumniModel = $this->model('Alumni');
        $data = ['user' => $user, 'title' => 'Alumni Network', 'alumni' => $alumniModel->getAllAlumni()];
        $this->view('alumni/index', $data);
    }
}
