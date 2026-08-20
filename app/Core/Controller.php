<?php
// app/Core/Controller.php

class Controller {
    public function view($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . "/../../app/Views/" . $view . ".php";
                if (file_exists($viewFile)) {
            require_once $viewFile;
            
            // Globally inject chatbot on all pages except receipt
            if ($view !== 'fee/receipt') {
                $chatbotFile = __DIR__ . "/../../app/Views/partials/chatbot.php";
                if (file_exists($chatbotFile)) {
                    require_once $chatbotFile;
                }
            }
        } else {
            die("View does not exist: " . $viewFile);
        }
    }

    public function model($model) {
        $modelFile = __DIR__ . "/../../app/Models/" . $model . ".php";
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        }
        die("Model does not exist: " . $modelFile);
    }
    
    public function redirect($url) {
        header("Location: " . rtrim(BASE_URL, '/') . $url);
        exit;
    }
}
