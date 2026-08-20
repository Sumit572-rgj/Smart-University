<?php
$dir = new RecursiveDirectoryIterator('app/Views');
$iterator = new RecursiveIteratorIterator($dir);
$count = 0;
foreach ($iterator as $file) {
    if ($file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        
        // This regex catches everything from the $notifDb declaration all the way down to the closing div of the bell dropdown
        // Because the HTML structure is somewhat complex, we'll look for the start of the PHP block
        // and delete up to the specific "Mark all as read" button's closing form and div.
        
        $pattern = '/\s*<\?php\s*if \(\!isset\(\$db\)\).*?Mark all as read<\/button>\s*<\/form>\s*<\/div>\s*<\/div>\s*<\/div>/is';
        
        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, '', $content);
            file_put_contents($file->getRealPath(), $content);
            $count++;
        }
    }
}
echo "Completely removed bell from $count files.";
