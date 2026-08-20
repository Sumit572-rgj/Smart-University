<?php
// app/Controllers/AboutController.php

class AboutController extends Controller {
    public function index() {
        $data = [
            'title' => 'About the Developer'
        ];
        $this->view('home/about', $data);
    }
}
