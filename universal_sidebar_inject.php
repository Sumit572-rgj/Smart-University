<?php
$viewsDir = 'C:/xampp/htdocs/cit_ums/app/Views';
$iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
$count = 0;

foreach ($iter as $file) {
    if ($file->getExtension() === 'php') {
        $path = $file->getPathname();
        $c = file_get_contents($path);
        
        // Skip dashboard/index.php because it has role-based sidebar which we already patched
        if (strpos($path, 'dashboard\index.php') !== false || strpos($path, 'dashboard/index.php') !== false) {
            continue;
        }

        // Only inject if it has a sidebar and doesn't already have room maintenance
        if (strpos($c, '<nav class="sidebar-nav">') !== false && strpos($c, '/cit_ums/roommaintenance') === false) {
            $c = preg_replace(
                '/(<\/nav>)/s',
                "    <a href=\"/cit_ums/roommaintenance\" class=\"nav-link\">Room Maintenance</a>\n        $1",
                $c
            );
            file_put_contents($path, $c);
            $count++;
        }
    }
}
echo "Universal injection applied to $count view files.\n";
