<?php
$dir = new RecursiveDirectoryIterator('C:/xampp/htdocs/cit_ums/app/Views');
$iterator = new RecursiveIteratorIterator($dir);

$count = 0;
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        
        // Check if viewport tag is missing
        if (strpos($content, '<head>') !== false && strpos($content, 'name="viewport"') === false) {
            // Inject the viewport tag right after <head>
            $content = str_replace('<head>', "<head>\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">", $content);
            file_put_contents($file->getPathname(), $content);
            $count++;
        }
    }
}

echo "Viewport meta tag injected into $count files.";
