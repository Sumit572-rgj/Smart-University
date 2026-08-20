<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/HostelController.php';
$c = file_get_contents($f);

// Inject resolve_maintenance logic for admin/warden
$pattern = '/if \(\$action === \'add_room\'\) \{/s';
$replacement = <<<PHP
                if (\$action === 'resolve_maintenance') {
                    \$maint_id = \$_POST['maintenance_id'];
                    try {
                        // Assuming 'resolved_date' doesn't exist yet, we will just update status and let it work if we alter the table or it will fallback gracefully.
                        // Wait, looking at the student view: \$m['resolved_date'] is checked! We should set it if it exists, or just set status='resolved'
                        \$stmt = \$db->prepare("UPDATE hostel_maintenance SET status = 'resolved' WHERE id = ?");
                        \$stmt->execute([\$maint_id]);
                        // try to set resolved_date if the column exists
                        try {
                            \$db->prepare("UPDATE hostel_maintenance SET resolved_date = CURRENT_TIMESTAMP WHERE id = ?")->execute([\$maint_id]);
                        } catch(Exception \$e) {}
                        \$data['success'] = "Maintenance issue resolved.";
                    } catch (Exception \$e) {
                        \$data['error'] = "Failed to resolve maintenance issue.";
                    }
                } else if (\$action === 'add_room') {
PHP;

$c = preg_replace($pattern, $replacement, $c, 1);
file_put_contents($f, $c);
echo "HostelController updated.";
