<?php
$viewsDir = __DIR__ . '/app/Views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        
        // Let's just replace whatever is inside the menu-toggle button
        $content = preg_replace('/(<button class="menu-toggle"[^>]*>).*?(<\/button>)/i', '$1&#9776;$2', $content);
        
        file_put_contents($file->getRealPath(), $content);
    }
}
echo "Done replacing hamburger icons.";
