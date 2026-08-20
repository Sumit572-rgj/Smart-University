<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/MessageController.php';
$c = file_get_contents($f);

$insertion = <<<PHP

        if (\$_SERVER['REQUEST_METHOD'] === 'POST' && isset(\$_POST['delete_message'])) {
            \$msg_id = \$_POST['delete_message'];
            \$stmt = \$db->prepare("DELETE FROM messages WHERE id = ? AND (sender_id = ? OR receiver_id = ?)");
            \$stmt->execute([\$msg_id, \$user['id'], \$user['id']]);
            \$this->redirect('/message?success=Message+deleted');
            exit;
        }

PHP;

// Find the string to insert before
$search = "if (\$_SERVER['REQUEST_METHOD'] === 'POST' && isset(\$_POST['send_message'])) {";

if (strpos($c, $search) !== false) {
    $c = str_replace($search, $insertion . "        " . $search, $c);
    file_put_contents($f, $c);
    echo "INJECTED.";
} else {
    echo "FAILED TO FIND SEARCH STRING.";
}
