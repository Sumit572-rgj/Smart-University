<?php
// app/Controllers/ApiController.php

class ApiController extends Controller {
    public function index() {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Welcome to CIT UMS Mobile API',
            'version' => '1.0.0',
            'endpoints' => [
                '/api/login' => 'POST: Authenticate user',
                '/api/profile' => 'GET: Get user profile'
            ]
        ]);
    }
}
