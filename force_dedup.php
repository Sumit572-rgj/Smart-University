<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Models/Message.php';
$c = file_get_contents($f);

// Replace getSent body entirely
$newSent = <<<PHP
    public function getSent(\$user_id) {
        \$stmt = \$this->db->prepare("
            SELECT m.*, u.username as receiver_name, u.role as receiver_role 
            FROM messages m 
            JOIN users u ON m.receiver_id = u.id 
            WHERE m.sender_id = :user_id 
            ORDER BY m.created_at DESC
        ");
        \$stmt->execute(['user_id' => \$user_id]);
        \$raw_messages = \$stmt->fetchAll();
        
        \$deduped = [];
        \$seen = [];
        foreach (\$raw_messages as \$msg) {
            // Hash by subject, body, and roughly the minute it was sent to dedup broadcasts and CCs
            \$hash = md5(\$msg['subject'] . \$msg['body'] . substr(\$msg['created_at'], 0, 16));
            
            if (!isset(\$seen[\$hash])) {
                if (str_starts_with(\$msg['subject'], '[BROADCAST]')) {
                    \$msg['receiver_name'] = 'Multiple Recipients (Broadcast)';
                }
                \$seen[\$hash] = true;
                \$deduped[\$hash] = \$msg;
            } else {
                if (strpos(\$deduped[\$hash]['receiver_name'], '+') === false && !str_starts_with(\$msg['subject'], '[BROADCAST]')) {
                    \$deduped[\$hash]['receiver_name'] .= ' (+ Others)';
                }
            }
        }
        return array_values(\$deduped);
    }
PHP;

// Find start and end
$startStr = "public function getSent(\$user_id) {";
$endStr = "public function sendMessage(";

$start = strpos($c, $startStr);
$end = strpos($c, $endStr);

$oldFunc = substr($c, $start, $end - $start);

$c = str_replace($oldFunc, $newSent . "\n\n    ", $c);
file_put_contents($f, $c);
echo "Deduplication logic injected.";
