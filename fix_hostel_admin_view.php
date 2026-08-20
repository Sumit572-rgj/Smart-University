<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/hostel/admin.php';
$c = file_get_contents($f);

$pattern = '/<div class="mb-3 p-3 border rounded bg-gray-50">.*?<p class="text-sm text-gray-600"><\?= htmlspecialchars\(\$m\[\'description\'\]\) \?><\/p>\s*<\/div>/s';
$replacement = <<<HTML
                            <div class="mb-3 p-3 border rounded <?= \$m['status'] === 'resolved' ? 'bg-green-50' : 'bg-gray-50' ?>">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-bold text-sm">Room <?= htmlspecialchars(\$m['room_number']) ?></span>
                                    <div style="display:flex; align-items:center; gap:0.5rem;">
                                        <span class="text-xs uppercase px-2 py-1 rounded <?= \$m['status'] === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                            <?= htmlspecialchars(\$m['status']) ?>
                                        </span>
                                        <?php if(\$m['status'] !== 'resolved'): ?>
                                        <form action="/cit_ums/hostel" method="POST" style="margin:0;">
                                            <input type="hidden" name="action" value="resolve_maintenance">
                                            <input type="hidden" name="maintenance_id" value="<?= \$m['id'] ?>">
                                            <button type="submit" class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Resolve</button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <p class="text-xs font-semibold text-cit-orange mb-1"><?= ucfirst(htmlspecialchars(\$m['issue_type'] ?? '')) ?></p>
                                <p class="text-sm text-gray-600"><?= htmlspecialchars(\$m['description']) ?></p>
                            </div>
HTML;

$c = preg_replace($pattern, $replacement, $c);
file_put_contents($f, $c);
echo "Hostel Admin view updated.";
