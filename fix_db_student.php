<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/StudentController.php';
$c = file_get_contents($f);

$oldLogic = <<<PHP
        \$studentModel = \$this->model('Student');
        \$facultyDept = null;
        if (\$user['role'] === 'faculty') {
            \$stmt = \$db->prepare("SELECT department FROM faculty WHERE user_id = ?");
PHP;

$newLogic = <<<PHP
        \$studentModel = \$this->model('Student');
        \$db = (new Model())->db;
        \$facultyDept = null;
        if (\$user['role'] === 'faculty') {
            \$stmt = \$db->prepare("SELECT department FROM faculty WHERE user_id = ?");
PHP;

$c = str_replace($oldLogic, $newLogic, $c);
file_put_contents($f, $c);
echo "Fixed missing db declaration in StudentController.";
