<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/MessageController.php';
$c = file_get_contents($f);
$c = str_replace('body, attachment', 'body, attachment_path', $c);
$c = str_replace('$body, $attachment', '$body, $attachment', $c); // Already correct in the execute array, but let's check
// The execute uses $attachment variable.

// Forum post has no attachment column in schema.
$c = str_replace(
    'INSERT INTO forum_posts (forum_id, user_id, content, attachment) VALUES (?, ?, ?, ?)',
    'INSERT INTO forum_posts (forum_id, user_id, content) VALUES (?, ?, ?)',
    $c
);
$c = str_replace(
    '$stmt->execute([$forum_id, $user[\'id\'], $content, $attachment]);',
    '$stmt->execute([$forum_id, $user[\'id\'], $content]);',
    $c
);

file_put_contents($f, $c);

// Also fix the View file that reads 'attachment'
$vf = 'C:/xampp/htdocs/cit_ums/app/Views/message/index.php';
$vc = file_get_contents($vf);
$vc = str_replace('$m[\'attachment\']', '$m[\'attachment_path\']', $vc);
file_put_contents($vf, $vc);

echo "Messaging fixed again.";
