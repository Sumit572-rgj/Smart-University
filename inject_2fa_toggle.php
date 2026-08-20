<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/dashboard/index.php';
$c = file_get_contents($f);

// Check if we already injected
if (strpos($c, '/cit_ums/auth/toggle2fa') === false) {
    // We will inject the toggle button right inside the main-content header
    $injection = <<<'HTML'
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2><?= htmlspecialchars($data['title']) ?></h2>
            <div style="background: white; padding: 0.5rem 1rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 1rem;">
                <span style="font-size: 0.9rem; font-weight: 600; color: #475569;">Two-Factor Auth (2FA)</span>
                <?php
                    $db = (new Model())->db;
                    $stmt = $db->prepare("SELECT two_factor_enabled FROM users WHERE id = ?");
                    $stmt->execute([$user['id']]);
                    $is2fa = $stmt->fetchColumn();
                ?>
                <a href="/cit_ums/auth/toggle2fa" style="background: <?= $is2fa ? '#10b981' : '#cbd5e1' ?>; width: 44px; height: 24px; border-radius: 999px; position: relative; display: inline-block; transition: background 0.3s;" title="Toggle 2FA">
                    <span style="position: absolute; top: 2px; <?= $is2fa ? 'right: 2px;' : 'left: 2px;' ?> width: 20px; height: 20px; background: white; border-radius: 50%; box-shadow: 0 1px 2px rgba(0,0,0,0.2); transition: all 0.3s;"></span>
                </a>
            </div>
        </div>
HTML;

    $c = preg_replace('/<h2><\?= htmlspecialchars\(\$data\[\'title\'\]\) \?><\/h2>/', $injection, $c);
    file_put_contents($f, $c);
    echo "Injected 2FA toggle into dashboard.\n";
} else {
    echo "Already injected.\n";
}
