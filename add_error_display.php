<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/student/profile.php';
$c = file_get_contents($f);

$oldAlert = <<<HTML
            <?php if (!empty(\$success)): ?>
                <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;margin-bottom:1rem;font-weight:bold;"><?= htmlspecialchars(\$success) ?></div>
            <?php endif; ?>
HTML;

$newAlert = <<<HTML
            <?php if (!empty(\$success)): ?>
                <div class="alert" style="background:#d1fae5;color:#065f46;border-color:#a7f3d0;margin-bottom:1rem;font-weight:bold;"><?= htmlspecialchars(\$success) ?></div>
            <?php endif; ?>
            <?php if (!empty(\$error)): ?>
                <div class="alert alert-error" style="background:#fee2e2;color:#991b1b;border-color:#fecaca;margin-bottom:1rem;font-weight:bold;"><?= htmlspecialchars(\$error) ?></div>
            <?php endif; ?>
HTML;

$c = str_replace($oldAlert, $newAlert, $c);
file_put_contents($f, $c);
echo "Added error alert to profile view.";
