<?php
// 1. Remove from DashboardController
$f1 = 'C:/xampp/htdocs/cit_ums/app/Controllers/DashboardController.php';
$c1 = file_get_contents($f1);

$pattern1 = '/\$maintDb = \$db->query\("SHOW TABLES LIKE \'mod_roommaintenance\'"\)->rowCount\(\) > 0;.*?\$data\[\'stats\'\] = \[\s*\[\'label\' => \'Total Students\'.*?\],\s*\[\'label\' => \'Total Faculty\'.*?\],\s*\[\'label\' => \'Pending Admin Outpasses\'.*?\],\s*\[\'label\' => \'Pending Room Maint\.\'.*?\]\s*\];/s';

$replacement1 = <<<PHP
            \$data['stats'] = [
                ['label' => 'Total Students', 'value' => \$studentsCount, 'link' => '/cit_ums/student'],
                ['label' => 'Total Faculty', 'value' => \$facultyCount, 'link' => '/cit_ums/faculty'],
                ['label' => 'Pending Admin Outpasses', 'value' => \$outpassCount, 'link' => '/cit_ums/outpass'],
                ['label' => 'Revenue Collected', 'value' => ',1' . number_format(\$feeCollected), 'link' => '#']
            ];
PHP;

$c1 = preg_replace($pattern1, $replacement1, $c1, 1);
file_put_contents($f1, $c1);

// 2. Remove from Admin section of dashboard/index.php sidebar
$f2 = 'C:/xampp/htdocs/cit_ums/app/Views/dashboard/index.php';
$c2 = file_get_contents($f2);

$c2 = str_replace('<a href="/cit_ums/roommaintenance" class="nav-link"><!-- Admin Room Maint -->Room Maintenance</a>', '', $c2);

file_put_contents($f2, $c2);

echo "Removed from Admin.";
