<?php
// app/Models/Security.php

class Security extends Model {
    public function logActivity($user_id, $action, $ip) {
        $stmt = $this->db->prepare("INSERT INTO activity_logs (user_id, action, ip_address) VALUES (:u, :a, :i)");
        return $stmt->execute(['u' => $user_id, 'a' => $action, 'i' => $ip]);
    }

    public function getRecentLogs($limit = 50) {
        $stmt = $this->db->prepare("
            SELECT al.*, u.username, u.role 
            FROM activity_logs al 
            LEFT JOIN users u ON al.user_id = u.id 
            ORDER BY al.created_at DESC LIMIT :limit
        ");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
