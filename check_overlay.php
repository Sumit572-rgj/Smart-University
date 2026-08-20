<?php
$viewsDir = __DIR__ . '/app/Views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        if (strpos($content, 'sidebar') !== false && strpos($content, 'sidebar-overlay') === false) {
            echo "Missing overlay: " . $file->getRealPath() . "\n";
        }
    }
}
echo "Check done.\n";
