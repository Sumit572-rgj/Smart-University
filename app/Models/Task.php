<?php
// app/Models/Task.php

class Task extends Model {
    public function getTasks($user_id) {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    public function addTask($user_id, $title) {
        $stmt = $this->db->prepare("INSERT INTO tasks (user_id, title) VALUES (:user_id, :title)");
        return $stmt->execute(['user_id' => $user_id, 'title' => $title]);
    }
    
    public function completeTask($task_id, $user_id) {
        $stmt = $this->db->prepare("UPDATE tasks SET status = 'completed' WHERE id = :id AND user_id = :user_id");
        return $stmt->execute(['id' => $task_id, 'user_id' => $user_id]);
    }
}
