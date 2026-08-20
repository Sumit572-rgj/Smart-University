<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Models/Library.php';
$c = file_get_contents($f);

// Fix the PDO parameter issue
$old = <<<PHP
        if (\$query) {
            \$sql .= " WHERE title LIKE :query OR author LIKE :query OR subject LIKE :query OR isbn LIKE :query";
            \$stmt = \$this->db->prepare(\$sql);
            \$stmt->execute(['query' => "%\$query%"]);
            return \$stmt->fetchAll();
        }
PHP;

$new = <<<PHP
        if (\$query) {
            \$sql .= " WHERE title LIKE ? OR author LIKE ? OR subject LIKE ? OR isbn LIKE ?";
            \$stmt = \$this->db->prepare(\$sql);
            \$val = "%\$query%";
            \$stmt->execute([\$val, \$val, \$val, \$val]);
            return \$stmt->fetchAll();
        }
PHP;

$c = str_replace($old, $new, $c);
file_put_contents($f, $c);
echo "Library.php search parameter fixed.";
