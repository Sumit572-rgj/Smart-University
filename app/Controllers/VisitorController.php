<?php
// app/Controllers/VisitorController.php

class VisitorController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user || $user['role'] !== 'admin') { $this->redirect('/dashboard'); }

        $visitorModel = $this->model('Visitor');
        $data = ['user' => $user, 'title' => 'Visitor Management', 'visitors' => $visitorModel->getActiveVisitors()];
        $this->view('visitor/index', $data);
    }
}
