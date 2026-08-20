<?php
$viewsDir = __DIR__ . '/app/Views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        $changed = false;

        // Skip dashboard/index.php because it's already handled with role-specific logic
        if (strpos($file->getRealPath(), 'dashboard\\index.php') !== false || strpos($file->getRealPath(), 'dashboard/index.php') !== false) {
            continue;
        }

        if (preg_match('/(<nav class="sidebar-nav".*?>.*?)(<\/nav>)/s', $content, $matches)) {
            $navBlock = $matches[1];
            if (strpos($navBlock, '/cit_ums/course') === false) {
                $newNavBlock = $navBlock . '            <a href="/cit_ums/course" class="nav-link">Course Management</a>' . "\n        ";
                $content = str_replace($matches[0], $newNavBlock . '</nav>', $content);
                $changed = true;
            }
        }

        if ($changed) {
            file_put_contents($file->getRealPath(), $content);
            echo "Added generic Course link to " . $file->getFilename() . "\n";
        }
    }
}
echo "Done.";
