<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/dashboard/index.php';
$c = file_get_contents($f);

if (strpos($c, 'Finance & Fees') === false) {
    $c = str_replace(
        'Security Guard Management</a>',
        'Security Guard Management</a>' . "\n                  " . '<a href="/cit_ums/fee" class="nav-link"><span style="margin-right:8px; font-size:1.1rem;">💸</span>Finance & Fees</a>',
        $c
    );
    file_put_contents($f, $c);
    echo "Injected into Dashboard.\n";
} else {
    echo "Already injected.\n";
}
