<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/MessageController.php';
$c = file_get_contents($f);

$c = preg_replace('/(\$query = "SELECT id FROM users";\s+if \(\$group === \'STUDENTS\'\) \$query \.= " WHERE role = \'student\'";\s+else if \(\$group === \'FACULTY\'\) \$query \.= " WHERE role = \'faculty\'";)/', "$1\n                else if (\$group === 'PARENTS') \$query .= \" WHERE role = 'parent'\";", $c);

file_put_contents($f, $c);
echo "Actually fixed!";
