<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/MessageController.php';
$c = file_get_contents($f);

$oldLogic = <<<PHP
        \$db = (new Model())->db;

        if (\$_SERVER['REQUEST_METHOD'] === 'POST' && isset(\$_POST['send_message'])) {
PHP;

$newLogic = <<<PHP
        \$db = (new Model())->db;

        if (\$_SERVER['REQUEST_METHOD'] === 'POST' && isset(\$_POST['delete_message'])) {
            \$msg_id = \$_POST['delete_message'];
            // Make sure the user owns the message (either sender or receiver)
            \$stmt = \$db->prepare("DELETE FROM messages WHERE id = ? AND (sender_id = ? OR receiver_id = ?)");
            \$stmt->execute([\$msg_id, \$user['id'], \$user['id']]);
            \$this->redirect('/message?success=Message+deleted');
            exit;
        }

        if (\$_SERVER['REQUEST_METHOD'] === 'POST' && isset(\$_POST['send_message'])) {
PHP;

$c = str_replace($oldLogic, $newLogic, $c);
file_put_contents($f, $c);
echo "Delete logic added.";
