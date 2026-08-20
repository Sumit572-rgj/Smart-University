<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/roommaintenance/index.php';
$c = file_get_contents($f);

// 1. Remove Status from 'Add New Entry' for everyone, since it defaults to Pending now
$c = preg_replace(
    '/<div class="mb-3"><label class="block text-sm font-medium mb-1">Status<\/label><select name="status" class="form-input w-full"><option>Pending<\/option><option>Active<\/option><option>Resolved<\/option><option>Completed<\/option><\/select><\/div>/s',
    '',
    $c
);

// 2. Hide 'Add New Entry' entirely if user is NOT a student
$patternForm = '/<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200" style="height: fit-content;">.*?<\/form>\s*<\/div>/s';
$replacementForm = <<<HTML
                <?php if (\$user['role'] === 'student'): ?>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200" style="height: fit-content;">
                    <h3 class="text-lg font-bold mb-4">Report an Issue</h3>
                    <form action="/cit_ums/roommaintenance" method="POST">
                        <input type="hidden" name="add_data" value="1">
                        <div class="mb-3"><label class="block text-sm font-medium mb-1">Room No</label><input type="text"  name="room_no" class="form-input w-full" required></div>
                        <div class="mb-3"><label class="block text-sm font-medium mb-1">Issue Description</label><textarea name="issue" class="form-input w-full" rows="3" required></textarea></div>
                        <button type="submit" class="btn btn-primary w-full mt-4">Report Issue</button>
                    </form>
                </div>
                <?php endif; ?>
HTML;

$c = preg_replace($patternForm, $replacementForm, $c);

// 3. Make the Table 100% width if Warden (by overriding grid)
$c = preg_replace(
    '/<div class="grid" style="grid-template-columns: 1fr 2fr; gap: 1.5rem;">/s',
    '<div class="grid" style="<?= $user[\'role\'] === \'student\' ? \'grid-template-columns: 1fr 2fr;\' : \'grid-template-columns: 1fr;\' ?> gap: 1.5rem;">',
    $c
);

// 4. Update the Table loop to show the Warden the update form, and colors for status
$patternTable = '/<tr>\s*<td><strong><\?= htmlspecialchars\(\$r\[\'username\'\]\) \?><\/strong><\/td>.*?<\/tr>/s';
$replacementTable = <<<HTML
                                <tr>
                                    <td><strong><?= htmlspecialchars(\$r['username']) ?></strong></td>
                                    <td><?= htmlspecialchars(\$r['room_no']) ?></td>
                                    <td><?= htmlspecialchars(\$r['issue']) ?></td>
                                    <td>
                                        <?php if (\$user['role'] === 'warden' || \$user['role'] === 'admin'): ?>
                                            <form action="/cit_ums/roommaintenance" method="POST" style="display:flex; gap:0.5rem; align-items:center;">
                                                <input type="hidden" name="update_status" value="1">
                                                <input type="hidden" name="issue_id" value="<?= \$r['id'] ?>">
                                                <select name="status" class="form-input" style="padding:0.25rem 0.5rem; font-size:0.875rem;" onchange="this.form.submit()">
                                                    <option value="Pending" <?= \$r['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                                    <option value="In Progress" <?= \$r['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                                    <option value="Resolved" <?= \$r['status'] == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                                                </select>
                                            </form>
                                        <?php else: ?>
                                            <?php 
                                                \$color = '#f59e0b'; // Pending
                                                if (\$r['status'] === 'In Progress') \$color = '#3b82f6';
                                                if (\$r['status'] === 'Resolved') \$color = '#10b981';
                                            ?>
                                            <span style="background: <?= \$color ?>; color: white; padding: 0.2rem 0.5rem; border-radius: 9999px; font-size: 0.75rem; font-weight: bold; white-space: nowrap;">
                                                <?= htmlspecialchars(\$r['status']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-xs text-gray-500"><?= htmlspecialchars(date('M d, Y h:i A', strtotime(\$r['created_at']))) ?></td>
                                    <td>
                                        <?php if (\$user['role'] === 'student' || \$user['role'] === 'admin'): ?>
                                            <a href="/cit_ums/roommaintenance?delete=<?= \$r['id'] ?>" class="text-red-500 hover:underline text-sm font-bold" onclick="return confirm('Delete this record?');">Delete</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
HTML;

$c = preg_replace($patternTable, $replacementTable, $c);

file_put_contents($f, $c);
echo "View updated.";
