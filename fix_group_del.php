<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/MessageController.php';
$c = file_get_contents($f);

$oldDel = <<<PHP
        if (\$_SERVER['REQUEST_METHOD'] === 'POST' && isset(\$_POST['delete_message'])) {
            \$msg_id = \$_POST['delete_message'];
            \$stmt = \$db->prepare("DELETE FROM messages WHERE id = ? AND (sender_id = ? OR receiver_id = ?)");
            \$stmt->execute([\$msg_id, \$user['id'], \$user['id']]);
            \$this->redirect('/message?success=Message+deleted');
            exit;
        }
PHP;

$newDel = <<<PHP
        if (\$_SERVER['REQUEST_METHOD'] === 'POST' && isset(\$_POST['delete_message'])) {
            \$msg_id = \$_POST['delete_message'];
            
            \$stmt = \$db->prepare("SELECT subject, body, created_at, sender_id FROM messages WHERE id = ?");
            \$stmt->execute([\$msg_id]);
            \$msg = \$stmt->fetch();
            
            if (\$msg && \$msg['sender_id'] == \$user['id']) {
                // Sender is deleting their sent message. Delete all identical grouped copies (like broadcasts/CCs)
                \$delStmt = \$db->prepare("DELETE FROM messages WHERE sender_id = ? AND subject = ? AND body = ? AND created_at = ?");
                \$delStmt->execute([\$user['id'], \$msg['subject'], \$msg['body'], \$msg['created_at']]);
            } else {
                // Receiver is deleting from their inbox
                \$delStmt = \$db->prepare("DELETE FROM messages WHERE id = ? AND receiver_id = ?");
                \$delStmt->execute([\$msg_id, \$user['id']]);
            }
            
            \$this->redirect('/message?success=Message+deleted');
            exit;
        }
PHP;

$c = str_replace($oldDel, $newDel, $c);
file_put_contents($f, $c);
echo "Delete group logic applied.";
