<?php
// app/Controllers/FeatureController.php

class FeatureController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) {
            $this->redirect('/auth/login');
        }

        $featureName = isset($_GET['name']) ? $_GET['name'] : 'Feature';

        $data = [
            'user' => $user,
            'title' => htmlspecialchars($featureName) . ' - Coming Soon',
            'feature_name' => htmlspecialchars($featureName)
        ];

        $this->view('feature/coming_soon', $data);
    }
}
