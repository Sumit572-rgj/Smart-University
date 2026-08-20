<?php
// 1. Controller
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/StudentController.php';
$c = file_get_contents($f);
$c = preg_replace('/if \(!\$user \|\| \$user\[\'role\'\] !== \'admin\'\) \{/', "if (!\$user || !in_array(\$user['role'], ['admin', 'faculty'])) {", $c);
$logic = <<<PHP
        \$facultyDept = null;
        if (\$user['role'] === 'faculty') {
            \$stmt = \$db->prepare("SELECT department FROM faculty WHERE user_id = ?");
            \$stmt->execute([\$user['id']]);
            \$facultyDept = \$stmt->fetchColumn();
        }
        \$data['facultyDept'] = \$facultyDept;
PHP;
$c = str_replace("\$studentModel = \$this->model('Student');", "\$studentModel = \$this->model('Student');\n" . $logic, $c);
$replacePost = <<<PHP
                    'department' => (\$user['role'] === 'faculty') ? \$facultyDept : filter_input(INPUT_POST, 'department', FILTER_SANITIZE_STRING),
                    'section' => filter_input(INPUT_POST, 'section', FILTER_SANITIZE_STRING),
PHP;
$c = preg_replace('/\'department\' => filter_input\(INPUT_POST, \'department\', FILTER_SANITIZE_STRING\),/', $replacePost, $c);
$fetchLogic = <<<PHP
        \$data['students'] = (\$user['role'] === 'faculty') ? \$studentModel->getStudentsByDepartment(\$facultyDept) : \$studentModel->getAllStudents();
PHP;
$c = preg_replace('/\$data\[\'students\'\] = \$studentModel->getAllStudents\(\);/', $fetchLogic, $c);
file_put_contents($f, $c);

// 2. Model
$f = 'C:/xampp/htdocs/cit_ums/app/Models/Student.php';
$c = file_get_contents($f);
$method = <<<PHP
    public function getStudentsByDepartment(\$dept) {
        \$stmt = \$this->db->prepare("SELECT s.*, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE s.department = ? ORDER BY s.created_at DESC");
        \$stmt->execute([\$dept]);
        return \$stmt->fetchAll();
    }
PHP;
if (strpos($c, 'getStudentsByDepartment') === false) {
    $c = str_replace("public function getAllStudents()", $method . "\n\n    public function getAllStudents()", $c);
}
$c = preg_replace('/INSERT INTO students \((.*?)\) \n\s*VALUES \((.*?)\)/', "INSERT INTO students ($1, section) \n                VALUES ($2, :section)", $c);
$c = preg_replace('/\'cgpa\' => \$data\[\'cgpa\'\]/', "'cgpa' => \$data['cgpa'],\n                'section' => \$data['section'] ?? null", $c);
file_put_contents($f, $c);

// 3. View
$f = 'C:/xampp/htdocs/cit_ums/app/Views/student/index.php';
$c = file_get_contents($f);
$deptField = <<<HTML
                    <?php if(\$user['role'] === 'faculty'): ?>
                        <input type="hidden" name="department" value="<?= htmlspecialchars(\$facultyDept) ?>">
                    <?php else: ?>
                    <div>
                        <label class="block text-sm mb-1">Department</label>
                        <input type="text" name="department" class="form-input w-full" placeholder="e.g., CSE" required>
                    </div>
                    <?php endif; ?>
                    <div>
                        <label class="block text-sm mb-1">Section</label>
                        <input type="text" name="section" class="form-input w-full" placeholder="e.g. A, B, CSE-1">
                    </div>
HTML;
$c = preg_replace('/<div>\s*<label class="block text-sm mb-1">Department<\/label>\s*<input type="text" name="department".*?<\/div>/s', $deptField, $c);
if (strpos($c, '<th>Section</th>') === false) {
    $c = str_replace('<th>Department</th>', '<th>Department</th><th>Section</th>', $c);
    $c = str_replace('<td><?= htmlspecialchars($student[\'department\']) ?></td>', '<td><?= htmlspecialchars($student[\'department\']) ?></td><td><?= htmlspecialchars($student[\'section\'] ?? \'-\') ?></td>', $c);
}
file_put_contents($f, $c);
echo "Student components updated.";
