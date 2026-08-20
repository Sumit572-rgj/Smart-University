<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Models/Message.php';
$c = file_get_contents($f);

$oldSent = <<<PHP
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

$newSent = <<<PHP
    public function getSent(\$user_id) {
        \$stmt = \$this->db->prepare("
            SELECT m.*, 
                   IF(m.subject LIKE '[BROADCAST]%', 'Multiple Recipients (Broadcast)', u.username) as receiver_name, 
                   u.role as receiver_role 
            FROM messages m 
            JOIN users u ON m.receiver_id = u.id 
            WHERE m.sender_id = :user_id 
            ORDER BY m.created_at DESC
        ");
        \$stmt->execute(['user_id' => \$user_id]);
        \$raw_messages = \$stmt->fetchAll();
        
        // Deduplicate broadcasts in PHP
        \$deduped = [];
        \$seen = [];
        foreach (\$raw_messages as \$msg) {
            if (str_starts_with(\$msg['subject'], '[BROADCAST]')) {
                // Use subject and body as unique key for broadcasts
                \$hash = md5(\$msg['subject'] . \$msg['body']);
                if (!isset(\$seen[\$hash])) {
                    \$seen[\$hash] = true;
                    \$deduped[] = \$msg;
                }
            } else {
                // Normal DMs just go straight in (or we can dedup CCs if they exist)
                // If they CC parents, it might have exact same subject/body but different receiver.
                // If we want to dedup DMs that have the same body/subject (e.g. parent CCs), we can group by them too:
                \$hash = md5(\$msg['subject'] . \$msg['body'] . substr(\$msg['created_at'], 0, 16));
                if (!isset(\$seen[\$hash])) {
                    \$seen[\$hash] = true;
                    // Change receiver name to indicate multiple recipients if we grouped it
                    \$deduped[] = \$msg;
                } else {
                    // Update the existing deduplicated entry to say "+ Others"
                    foreach (\$deduped as &\$d) {
                        if (md5(\$d['subject'] . \$d['body'] . substr(\$d['created_at'], 0, 16)) === \$hash) {
                            if (strpos(\$d['receiver_name'], '+') === false) {
                                \$d['receiver_name'] .= ' (+ Others)';
                            }
                            break;
                        }
                    }
                }
            }
        }
        return \$deduped;
    }
PHP;

$c = str_replace($oldSent, $newSent, $c);
file_put_contents($f, $c);
echo "PHP deduplication applied.";
