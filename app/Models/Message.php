<?php
// app/Models/Message.php

class Message extends Model {
    public function getInbox($user_id) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.username as sender_name, u.role as sender_role 
            FROM messages m 
            JOIN users u ON m.sender_id = u.id 
            WHERE m.receiver_id = :user_id 
            ORDER BY m.created_at DESC
        ");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetchAll();
    }

        public function getSent($user_id) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.username as receiver_name, u.role as receiver_role 
            FROM messages m 
            JOIN users u ON m.receiver_id = u.id 
            WHERE m.sender_id = :user_id 
            ORDER BY m.created_at DESC
        ");
        $stmt->execute(['user_id' => $user_id]);
        $raw_messages = $stmt->fetchAll();
        
        $deduped = [];
        $seen = [];
        foreach ($raw_messages as $msg) {
            // Hash by subject, body, and roughly the minute it was sent to dedup broadcasts and CCs
            $hash = md5($msg['subject'] . $msg['body'] . substr($msg['created_at'], 0, 16));
            
            if (!isset($seen[$hash])) {
                if (str_starts_with($msg['subject'], '[BROADCAST]')) {
                    $msg['receiver_name'] = 'Multiple Recipients (Broadcast)';
                }
                $seen[$hash] = true;
                $deduped[$hash] = $msg;
            } else {
                if (strpos($deduped[$hash]['receiver_name'], '+') === false && !str_starts_with($msg['subject'], '[BROADCAST]')) {
                    $deduped[$hash]['receiver_name'] .= ' (+ Others)';
                }
            }
        }
        return array_values($deduped);
    }

    public function sendMessage($sender_id, $receiver_id, $subject, $body) {
        $stmt = $this->db->prepare("
            INSERT INTO messages (sender_id, receiver_id, subject, body) 
            VALUES (:s, :r, :sub, :b)
        ");
        return $stmt->execute([
            's' => $sender_id,
            'r' => $receiver_id,
            'sub' => $subject,
            'b' => $body
        ]);
    }
}
