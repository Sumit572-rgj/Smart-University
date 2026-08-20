<?php
// app/Controllers/AnalyticsController.php

class AnalyticsController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user || $user['role'] !== 'admin') { 
            $this->redirect('/dashboard'); 
        }

        $analyticsModel = $this->model('Analytics');
        $data = [
            'user' => $user, 
            'title' => 'Analytics Dashboard', 
            'stats' => $analyticsModel->getDashboardStats()
        ];
        
        $this->view('analytics/index', $data);
    }
}
