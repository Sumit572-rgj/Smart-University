<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/dashboard/index.php';
$c = file_get_contents($f);

$oldRole = '<span class="mr-2 text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">Role: <?= ucfirst($user[\'role\']) ?></span>';

$newToggle = <<<'HTML'
                <!-- 2FA Toggle -->
                <?php
                    $db = (new Model())->db;
                    $stmt = $db->prepare("SELECT two_factor_enabled FROM users WHERE id = ?");
                    $stmt->execute([$user['id']]);
                    $is2fa = $stmt->fetchColumn();
                ?>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-right: 1rem; background: #f8fafc; padding: 0.25rem 0.75rem; border-radius: 999px; border: 1px solid #e2e8f0;">
                    <span style="font-size: 0.75rem; font-weight: 600; color: #475569;">2FA</span>
                    <a href="/cit_ums/auth/toggle2fa" style="background: <?= $is2fa ? '#10b981' : '#cbd5e1' ?>; width: 36px; height: 20px; border-radius: 999px; position: relative; display: inline-block; transition: background 0.3s;" title="Toggle 2FA">
                        <span style="position: absolute; top: 2px; <?= $is2fa ? 'right: 2px;' : 'left: 2px;' ?> width: 16px; height: 16px; background: white; border-radius: 50%; box-shadow: 0 1px 2px rgba(0,0,0,0.2); transition: all 0.3s;"></span>
                    </a>
                </div>
                <span class="mr-2 text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">Role: <?= ucfirst($user['role']) ?></span>
HTML;

$c = str_replace($oldRole, $newToggle, $c);
file_put_contents($f, $c);
echo "Injected 2FA toggle into header.\n";
