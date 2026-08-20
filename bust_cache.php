<?php
$viewsDir = __DIR__ . '/app/Views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        
        $content = preg_replace('/href="\/cit_ums\/css\/style\.css(\?[^"]*)?"/i', 'href="/cit_ums/css/style.css?v=' . time() . '"', $content);
        
        file_put_contents($file->getRealPath(), $content);
    }
}
echo "Done appending cache buster to CSS.";
