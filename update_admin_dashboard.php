<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/DashboardController.php';
$c = file_get_contents($f);

$pattern = '/\$data\[\'stats\'\] = \[\s*\[\'label\' => \'Total Students\'.*?\],\s*\[\'label\' => \'Total Faculty\'.*?\],\s*\[\'label\' => \'Pending Admin Outpasses\'.*?\],\s*\[\'label\' => \'Revenue Collected\'.*?\]\s*\];/s';

$replacement = <<<PHP
            \$maintDb = \$db->query("SHOW TABLES LIKE 'mod_roommaintenance'")->rowCount() > 0;
            \$maintCount = 0;
            if (\$maintDb) {
                \$maintCount = \$db->query("SELECT COUNT(*) FROM mod_roommaintenance WHERE status = 'Pending'")->fetchColumn();
            }

            \$data['stats'] = [
                ['label' => 'Total Students', 'value' => \$studentsCount, 'link' => '/cit_ums/student'],
                ['label' => 'Total Faculty', 'value' => \$facultyCount, 'link' => '/cit_ums/faculty'],
                ['label' => 'Pending Admin Outpasses', 'value' => \$outpassCount, 'link' => '/cit_ums/outpass'],
                ['label' => 'Pending Room Maint.', 'value' => \$maintCount, 'link' => '/cit_ums/roommaintenance']
            ];
PHP;

$c = preg_replace($pattern, $replacement, $c, 1);
file_put_contents($f, $c);
echo "Admin dashboard updated.";
