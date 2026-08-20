<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/StudentController.php';
$c = file_get_contents($f);

$pattern = '/\$stmt = \$db->prepare\("SELECT id FROM users WHERE username = \? OR email = \?"\);\s*\$stmt->execute\(\[\$parent_username, \$parent_email\]\);\s*if \(\$stmt->fetch\(\)\) {/s';

$replacement = <<<PHP
                \$checkParent = \$db->prepare("SELECT id FROM parent_profiles WHERE student_id = ?");
                \$checkParent->execute([\$student_id]);
                
                \$stmt = \$db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
                \$stmt->execute([\$parent_username, \$parent_email]);
                
                if (\$checkParent->fetch()) {
                    \$data['error'] = 'This student is already linked to a parent account. Only one parent is allowed per student.';
                } else if (\$stmt->fetch()) {
PHP;

$c = preg_replace($pattern, $replacement, $c, 1);
file_put_contents($f, $c);
echo "Parent linking restricted.";
