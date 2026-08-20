<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/GateController.php';
$c = file_get_contents($f);

$oldCode = <<<PHP
                if (\$outpass) {
                    if (\$outpass['status'] === 'approved') {
                        // Mark as checked out
                        \$update = \$db->prepare("UPDATE outpass SET status = 'checked_out' WHERE id = ?");
                        \$update->execute([\$outpass_id]);
                        \$data['success'] = "CHECKED OUT: {\$outpass['first_name']} {\$outpass['last_name']} ({\$outpass['enrollment_no']}) has left the campus.";
                    } else if (\$outpass['status'] === 'checked_out') {
                        // Mark as checked in
                        \$update = \$db->prepare("UPDATE outpass SET status = 'checked_in' WHERE id = ?");
                        \$update->execute([\$outpass_id]);
                        \$data['success'] = "CHECKED IN: {\$outpass['first_name']} {\$outpass['last_name']} ({\$outpass['enrollment_no']}) has returned to the campus.";
                    } else if (\$outpass['status'] === 'checked_in') {
                        \$data['error'] = "Error: Outpass #\$outpass_id has already been used and checked in.";
                    } else {
                        \$data['error'] = "Error: Outpass #\$outpass_id is currently '{\$outpass['status']}' and not approved for exit.";
                    }
                } else {
PHP;

$newCode = <<<PHP
                if (\$outpass) {
                    \$data['scanned_student'] = \$outpass;
                    if (\$outpass['status'] === 'approved') {
                        // Mark as checked out
                        \$update = \$db->prepare("UPDATE outpass SET status = 'checked_out' WHERE id = ?");
                        \$update->execute([\$outpass_id]);
                        \$data['success'] = "CHECKED OUT: {\$outpass['first_name']} {\$outpass['last_name']} ({\$outpass['enrollment_no']}) has left the campus.";
                        \$data['scanned_student']['current_action'] = 'CHECKED OUT';
                        \$data['scanned_student']['action_color'] = '#f59e0b'; // orange
                    } else if (\$outpass['status'] === 'checked_out') {
                        // Mark as checked in
                        \$update = \$db->prepare("UPDATE outpass SET status = 'checked_in' WHERE id = ?");
                        \$update->execute([\$outpass_id]);
                        \$data['success'] = "CHECKED IN: {\$outpass['first_name']} {\$outpass['last_name']} ({\$outpass['enrollment_no']}) has returned to the campus.";
                        \$data['scanned_student']['current_action'] = 'CHECKED IN';
                        \$data['scanned_student']['action_color'] = '#10b981'; // green
                    } else if (\$outpass['status'] === 'checked_in') {
                        \$data['error'] = "Error: Outpass #\$outpass_id has already been used and checked in.";
                    } else {
                        \$data['error'] = "Error: Outpass #\$outpass_id is currently '{\$outpass['status']}' and not approved for exit.";
                    }
                } else {
PHP;

$c = str_replace($oldCode, $newCode, $c);
file_put_contents($f, $c);
echo "GateController updated to pass scanned_student.";
