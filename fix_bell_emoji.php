<?php
$viewsDir = __DIR__ . '/app/Views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        
        $changed = false;
        if (strpos($content, 'dY""') !== false) {
            $content = str_replace('dY""', '&#128276;', $content);
            $changed = true;
        }
        // Also catch if the literal emoji somehow survived in some files and replace it
        if (strpos($content, '🔔') !== false) {
            $content = str_replace('🔔', '&#128276;', $content);
            $changed = true;
        }
        
        if ($changed) {
            file_put_contents($file->getRealPath(), $content);
        }
    }
}
echo "Done replacing mangled bell emojis.";
