<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/home/index.php';
$c = file_get_contents($f);

$target = <<<HTML
                <?php if (\$user['role'] === 'faculty'): ?>
                <a href="/cit_ums/outpass" class="nav-link">Outpass Approvals</a>
            <?php endif; ?>
HTML;

$c = str_replace($target, "", $c);
file_put_contents($f, $c);
echo "Removed from home.\n";
