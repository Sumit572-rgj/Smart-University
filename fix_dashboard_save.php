<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/DashboardController.php';
$c = file_get_contents($f);

// Inject Hostel Attendance into Student Dashboard Stats
$pattern = '/\$data\[\'stats\'\] = \[\s*\[\'label\' => \'Total Outpasses\', \'value\' => \$outpassCount, \'link\' => \'\/cit_ums\/outpass\'\],\s*\[\'label\' => \'Pending Fees\', \'value\' => \'Rs\. \' \. number_format\(\(float\)\$feePending\), \'link\' => \'\/cit_ums\/fee\'\]\s*\];/';

$newStats = <<<'PHP'
            $haPercent = 'No Data';
            $haDb = $db->query("SHOW TABLES LIKE 'hostel_attendance'")->rowCount() > 0;
            if ($haDb && $student_id) {
                $totalDays = $db->query("SELECT COUNT(*) FROM hostel_attendance WHERE student_id = " . (int)$student_id . " AND status != 'Outpass'")->fetchColumn();
                $presentDays = $db->query("SELECT COUNT(*) FROM hostel_attendance WHERE student_id = " . (int)$student_id . " AND status = 'Present'")->fetchColumn();
                if ($totalDays > 0) {
                    $haPercent = round(($presentDays / $totalDays) * 100) . '%';
                }
            }

            $data['stats'] = [
                ['label' => 'Total Outpasses', 'value' => $outpassCount, 'link' => '/cit_ums/outpass'],
                ['label' => 'Pending Fees', 'value' => 'Rs. ' . number_format((float)$feePending), 'link' => '/cit_ums/fee'],
                ['label' => 'Hostel Attendance', 'value' => $haPercent, 'link' => '/cit_ums/hostelattendance']
            ];
PHP;

$c = preg_replace($pattern, $newStats, $c);
file_put_contents($f, $c);

// Fix HostelAttendanceController ON DUPLICATE KEY Syntax
$f2 = 'C:/xampp/htdocs/cit_ums/app/Controllers/HostelAttendanceController.php';
$c2 = file_get_contents($f2);

// Standard workaround for MySQL versions
$oldSave = <<<'PHP'
            $stmt = $db->prepare("
                INSERT INTO hostel_attendance (student_id, attendance_date, status, warden_id)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE status = VALUES(status), warden_id = VALUES(warden_id)
            ");
            
            foreach ($attendance_data as $student_id => $status) {
                $stmt->execute([$student_id, $date, $status, $user['id']]);
            }
PHP;

$newSave = <<<'PHP'
            // To ensure compatibility with older and newer MySQL without VALUES() deprecation warnings
            $stmtCheck = $db->prepare("SELECT id FROM hostel_attendance WHERE student_id = ? AND attendance_date = ?");
            $stmtUpdate = $db->prepare("UPDATE hostel_attendance SET status = ?, warden_id = ? WHERE id = ?");
            $stmtInsert = $db->prepare("INSERT INTO hostel_attendance (student_id, attendance_date, status, warden_id) VALUES (?, ?, ?, ?)");
            
            foreach ($attendance_data as $student_id => $status) {
                $stmtCheck->execute([$student_id, $date]);
                $existingId = $stmtCheck->fetchColumn();
                
                if ($existingId) {
                    $stmtUpdate->execute([$status, $user['id'], $existingId]);
                } else {
                    $stmtInsert->execute([$student_id, $date, $status, $user['id']]);
                }
            }
PHP;

$c2 = str_replace($oldSave, $newSave, $c2);
file_put_contents($f2, $c2);

echo "Dashboard updated and Warden save logic patched.\n";
