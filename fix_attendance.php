<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/AttendanceController.php';
$c = file_get_contents($f);

$oldLogic = <<<PHP
                if (\$cCode && \$date && !empty(\$attendance_data)) {
                    \$insertStmt = \$db->prepare("INSERT INTO attendance (student_id, course_code, date, status) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE status = ?");
                    foreach (\$attendance_data as \$student_id => \$status) {
                        \$insertStmt->execute([\$student_id, \$cCode, \$date, \$status, \$status]);
                    }
                    \$this->redirect('/attendance?success=Attendance+saved');
                }
PHP;

$newLogic = <<<PHP
                if (\$cCode && \$date && !empty(\$attendance_data)) {
                    \$checkStmt = \$db->prepare("SELECT id FROM attendance WHERE student_id = ? AND course_code = ? AND date = ?");
                    \$updateStmt = \$db->prepare("UPDATE attendance SET status = ? WHERE id = ?");
                    \$insertStmt = \$db->prepare("INSERT INTO attendance (student_id, course_code, date, status) VALUES (?, ?, ?, ?)");
                    
                    foreach (\$attendance_data as \$student_id => \$status) {
                        \$checkStmt->execute([\$student_id, \$cCode, \$date]);
                        \$existing = \$checkStmt->fetchColumn();
                        
                        if (\$existing) {
                            \$updateStmt->execute([\$status, \$existing]);
                        } else {
                            \$insertStmt->execute([\$student_id, \$cCode, \$date, \$status]);
                        }
                    }
                    \$this->redirect('/attendance?success=Attendance+saved');
                }
PHP;

$c = str_replace($oldLogic, $newLogic, $c);
file_put_contents($f, $c);
echo "AttendanceController fixed.";
