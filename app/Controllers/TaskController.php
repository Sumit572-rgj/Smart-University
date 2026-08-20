<?php
// app/Controllers/TaskController.php

class TaskController extends Controller {
    public function index() {
        $user = JWT::getToken();
        if (!$user) { $this->redirect('/auth/login'); }

        $taskModel = $this->model('Task');
        $user_id = $user['user_id'] ?? 1; // Fallback to 1 for mock

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['action']) && $_POST['action'] == 'add') {
                $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
                $taskModel->addTask($user_id, $title);
            } elseif (isset($_POST['action']) && $_POST['action'] == 'complete') {
                $task_id = $_POST['task_id'];
                $taskModel->completeTask($task_id, $user_id);
            }
            $this->redirect('/task');
        }

        $data = ['user' => $user, 'title' => 'My Tasks', 'tasks' => $taskModel->getTasks($user_id)];
        $this->view('task/index', $data);
    }
}
