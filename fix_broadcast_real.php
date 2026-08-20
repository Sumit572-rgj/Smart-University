<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/MessageController.php';
$c = file_get_contents($f);

$oldParse = '$receiver_username = trim($_POST[\'receiver_username\']);';

$newParse = <<<PHP
            \$broadcast_group = \$_POST['broadcast_group'] ?? '';
            \$receiver_username = trim(\$_POST['receiver_username'] ?? '');
            
            if (!empty(\$broadcast_group)) {
                \$receiver_username = \$broadcast_group; // Treat as broadcast
            } else {
                // If they typed "all", "student", or "faculty" into the specific username text box, handle it smartly!
                \$lower_username = strtolower(\$receiver_username);
                if (\$lower_username === 'all') {
                    \$receiver_username = '@GROUP:ALL';
                } else if (\$lower_username === 'student' || \$lower_username === 'students') {
                    \$receiver_username = '@GROUP:STUDENTS';
                } else if (\$lower_username === 'faculty') {
                    \$receiver_username = '@GROUP:FACULTY';
                }
            }
PHP;

$c = str_replace($oldParse, $newParse, $c);
file_put_contents($f, $c);
echo "MessageController broadcast fixed for real.";
