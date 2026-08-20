<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/MessageController.php';
$c = file_get_contents($f);

$oldDirect = <<<PHP
            if (\$receiver_id) {
                \$stmt = \$db->prepare("INSERT INTO messages (sender_id, receiver_id, subject, body, attachment_path) VALUES (?, ?, ?, ?, ?)");
                \$stmt->execute([\$user['id'], \$receiver_id, \$subject, \$body, \$attachment]);
PHP;

$newDirect = <<<PHP
            if (\$receiver_id) {
                \$stmt = \$db->prepare("INSERT INTO messages (sender_id, receiver_id, subject, body, attachment_path) VALUES (?, ?, ?, ?, ?)");
                \$stmt->execute([\$user['id'], \$receiver_id, \$subject, \$body, \$attachment]);
                
                // If receiver is a student, automatically CC their parents!
                \$checkRole = \$db->prepare("SELECT role FROM users WHERE id = ?");
                \$checkRole->execute([\$receiver_id]);
                if (\$checkRole->fetchColumn() === 'student') {
                    // Find student id
                    \$studStmt = \$db->prepare("SELECT id FROM students WHERE user_id = ?");
                    \$studStmt->execute([\$receiver_id]);
                    \$stud_id = \$studStmt->fetchColumn();
                    if (\$stud_id) {
                        // Find parent user_ids
                        \$parentStmt = \$db->prepare("SELECT user_id FROM parent_profiles WHERE student_id = ?");
                        \$parentStmt->execute([\$stud_id]);
                        \$parents = \$parentStmt->fetchAll();
                        foreach (\$parents as \$p) {
                            \$stmt->execute([\$user['id'], \$p['user_id'], "[CC] \$subject", \$body, \$attachment]);
                        }
                    }
                }
PHP;

$c = str_replace($oldDirect, $newDirect, $c);
file_put_contents($f, $c);
echo "Parents are now CC'd on direct messages.";
