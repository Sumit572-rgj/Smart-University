<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/MessageController.php';
$c = file_get_contents($f);

$old = <<<PHP
        \$db = (new Model())->db;

        if (\$_SERVER['REQUEST_METHOD'] === 'POST' && isset(\$_POST['send_message'])) {
PHP;

$new = <<<PHP
        \$db = (new Model())->db;

        if (\$_SERVER['REQUEST_METHOD'] === 'POST' && isset(\$_POST['delete_message'])) {
            \$msg_id = \$_POST['delete_message'];
            \$stmt = \$db->prepare("DELETE FROM messages WHERE id = ? AND (sender_id = ? OR receiver_id = ?)");
            \$stmt->execute([\$msg_id, \$user['id'], \$user['id']]);
            \$this->redirect('/message?success=Message+deleted');
            exit;
        }

        if (\$_SERVER['REQUEST_METHOD'] === 'POST' && isset(\$_POST['send_message'])) {
PHP;

$c = str_replace($old, $new, $c);

// Also fix duplicates in sent box
// Wait, why are there duplicates?
// In the previous session, I added "parent CC" logic!
// Let's check how the parent CC logic was implemented.
file_put_contents($f, $c);
echo "Delete logic injected.";
