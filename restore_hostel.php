<?php
// 1. Hostel Controller
$f = 'C:/xampp/htdocs/cit_ums/app/Controllers/HostelController.php';
$c = file_get_contents($f);

$actionHandler = <<<PHP
        if (\$user['role'] === 'student' && \$_SERVER['REQUEST_METHOD'] == 'POST') {
            \$action = \$_POST['action'] ?? '';
            if (\$action === 'report_maintenance') {
                \$room_id = \$_POST['room_id'];
                \$desc = \$_POST['description'];
                try {
                    \$stmt = \$db->prepare("INSERT INTO hostel_maintenance (room_id, reported_by, description) VALUES (?, ?, ?)");
                    \$stmt->execute([\$room_id, \$user['id'], \$desc]);
                    \$data['success'] = "Maintenance issue reported successfully.";
                } catch (Exception \$e) {
                    \$data['error'] = "Failed to report maintenance issue.";
                }
            }
        }
PHP;
$c = str_replace("if (in_array(\$user['role'], ['admin', 'warden'])) {", $actionHandler . "\n\n        if (in_array(\$user['role'], ['admin', 'warden'])) {", $c);

$oldFetch = <<<PHP
            \$stmt = \$db->prepare("SELECT hr.room_number, hr.block_name, ha.allocated_date 
                                  FROM hostel_allocations ha 
                                  JOIN hostel_rooms hr ON ha.room_id = hr.id 
                                  WHERE ha.student_id = ? AND ha.status = 'active'");
            \$stmt->execute([\$student_id]);
            \$data['allocation'] = \$stmt->fetch();
PHP;
$newFetch = <<<PHP
            \$stmt = \$db->prepare("SELECT hr.id as room_id, hr.room_number, hr.block_name, ha.allocated_date 
                                  FROM hostel_allocations ha 
                                  JOIN hostel_rooms hr ON ha.room_id = hr.id 
                                  WHERE ha.student_id = ? AND ha.status = 'active'");
            \$stmt->execute([\$student_id]);
            \$data['allocation'] = \$stmt->fetch();
            
            // Also fetch maintenance records for this student
            \$data['maintenance'] = \$db->prepare("SELECT m.*, hr.room_number FROM hostel_maintenance m JOIN hostel_rooms hr ON m.room_id = hr.id WHERE m.reported_by = ? ORDER BY m.reported_date DESC");
            \$data['maintenance']->execute([\$user['id']]);
            \$data['maintenance'] = \$data['maintenance']->fetchAll();
PHP;
$c = str_replace($oldFetch, $newFetch, $c);
file_put_contents($f, $c);

// 2. Hostel Student View
$f = 'C:/xampp/htdocs/cit_ums/app/Views/hostel/student.php';
$c = file_get_contents($f);

$injection = <<<HTML
                <div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 text-center max-w-xl mx-auto">
                    <svg style="width:80px;height:80px;margin:0 auto 1rem;color:#3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <h2 class="text-3xl font-bold mb-2"><?= htmlspecialchars(\$allocation['room_number']) ?></h2>
                    <p class="text-xl text-gray-500 mb-6"><?= htmlspecialchars(\$allocation['block_name']) ?></p>
                    <div class="text-sm text-gray-400">Allocated on: <?= date('d M Y', strtotime(\$allocation['allocated_date'])) ?></div>
                </div>

                <!-- NEW: Maintenance Generation Form for Student -->
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 max-w-xl mx-auto mt-6">
                    <h3 class="text-lg font-bold mb-4">Report Maintenance Issue</h3>
                    <form action="/cit_ums/hostel" method="POST">
                        <input type="hidden" name="action" value="report_maintenance">
                        <input type="hidden" name="room_id" value="<?= htmlspecialchars(\$allocation['room_id'] ?? '') ?>">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Issue Description</label>
                            <textarea name="description" class="form-input w-full" rows="3" required placeholder="Describe the maintenance issue..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">Generate Maintenance Record</button>
                    </form>
                </div>
                
                <!-- NEW: Maintenance History for Student -->
                <?php if(!empty(\$maintenance)): ?>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 max-w-xl mx-auto mt-6">
                    <h3 class="text-lg font-bold mb-4">My Maintenance Requests</h3>
                    <div class="space-y-4">
                        <?php foreach(\$maintenance as \$m): ?>
                        <div class="p-4 border rounded <?= \$m['status'] === 'resolved' ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200' ?>">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs font-bold uppercase <?= \$m['status'] === 'resolved' ? 'text-green-700' : 'text-yellow-700' ?>"><?= htmlspecialchars(\$m['status']) ?></span>
                                <span class="text-xs text-gray-500"><?= date('d M Y', strtotime(\$m['reported_date'])) ?></span>
                            </div>
                            <p class="text-sm text-gray-700"><?= htmlspecialchars(\$m['description']) ?></p>
                            <?php if(\$m['resolved_date']): ?>
                            <div class="text-xs text-gray-500 mt-2">Resolved on: <?= date('d M Y', strtotime(\$m['resolved_date'])) ?></div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
HTML;
$c = preg_replace('/<div class="bg-white p-8 rounded-lg shadow-sm border border-gray-200 text-center max-w-xl mx-auto">\s*<svg.*?<\/svg>\s*<h2.*?<\/h2>\s*<p.*?<\/p>\s*<div.*?<\/div>\s*<\/div>/s', $injection, $c);
file_put_contents($f, $c);
echo "Hostel fixes restored.";
