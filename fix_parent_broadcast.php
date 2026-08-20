<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/MessageController.php';
$c = file_get_contents($f);

$oldLogic = <<<PHP
                if (\$group === 'STUDENTS') \$query .= " WHERE role = 'student'";
                else if (\$group === 'FACULTY') \$query .= " WHERE role = 'faculty'";
                else if (\$group === 'PARENTS') \$query .= " WHERE role = 'parent'";
PHP;

$newLogic = <<<PHP
                if (\$group === 'STUDENTS') \$query .= " WHERE role IN ('student', 'parent')";
                else if (\$group === 'FACULTY') \$query .= " WHERE role = 'faculty'";
                else if (\$group === 'PARENTS') \$query .= " WHERE role = 'parent'";
PHP;

$c = str_replace($oldLogic, $newLogic, $c);
file_put_contents($f, $c);
echo "Parents are now copied on all Student broadcasts.";
