<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Models/Message.php';
$c = file_get_contents($f);

$oldSent = <<<PHP
    public function getSent(\$user_id) {
        \$stmt = \$this->db->prepare("
            SELECT m.*, u.username as receiver_name, u.role as receiver_role 
            FROM messages m 
            JOIN users u ON m.receiver_id = u.id 
            WHERE m.sender_id = :user_id 
            ORDER BY m.created_at DESC
        ");
        \$stmt->execute(['user_id' => \$user_id]);
        return \$stmt->fetchAll();
    }
PHP;

$newSent = <<<PHP
    public function getSent(\$user_id) {
        \$stmt = \$this->db->prepare("
            SELECT m.id, m.sender_id, m.subject, m.body, m.attachment_path, m.created_at, m.is_read, 
                   IF(m.subject LIKE '[BROADCAST]%', 'Multiple Recipients (Broadcast)', u.username) as receiver_name, 
                   u.role as receiver_role 
            FROM messages m 
            JOIN users u ON m.receiver_id = u.id 
            WHERE m.sender_id = :user_id 
            GROUP BY m.subject, m.body, m.created_at
            ORDER BY m.created_at DESC
        ");
        \$stmt->execute(['user_id' => \$user_id]);
        return \$stmt->fetchAll();
    }
PHP;

$c = str_replace($oldSent, $newSent, $c);
file_put_contents($f, $c);
echo "Sent deduplicated.";
