<?php
// app/Controllers/HomeController.php

class HomeController extends Controller {
    public function index() {
        // Check if already logged in
        $user = JWT::getToken();
        if ($user) {
            $this->redirect('/dashboard');
            exit;
        }

        $this->view('home/index', ['title' => 'Welcome']);
    }
}
