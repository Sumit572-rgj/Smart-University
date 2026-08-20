<?php
// app/Controllers/ResearchController.php

class ResearchController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }

        $researchModel = $this->model('Research');
        $data = ['user' => $user, 'title' => 'Research & Publications', 'publications' => $researchModel->getAllPublications()];
        $this->view('research/index', $data);
    }
}
