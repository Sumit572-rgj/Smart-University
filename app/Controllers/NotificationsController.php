<?php
// app/Controllers/NotificationsController.php

class NotificationsController extends Controller {
    public function mark_read() {
        $user = JWT::getToken();
        if ($user) {
            // We can quickly connect to DB here
            $db = $this->model('User')->db;
            $stmt = $db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
            $stmt->execute([$user['id']]);
        }
        
        $referer = $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'dashboard';
        header("Location: " . $referer);
        exit;
    }
}
