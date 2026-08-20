<?php
$viewsDir = __DIR__ . '/app/Views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        
        $newOnclick = "if(window.innerWidth<=768){document.querySelector('.sidebar').classList.toggle('open');document.querySelector('.sidebar-overlay').classList.toggle('open');}else{document.querySelector('.sidebar').classList.toggle('collapsed');}";
        
        $content = preg_replace('/onclick="document\.querySelector\(\'\.sidebar\'\)\.classList\.toggle\(\'open\'\);[^"]*"/i', 'onclick="' . $newOnclick . '"', $content);
        
        file_put_contents($file->getRealPath(), $content);
    }
}
echo "Done replacing onclick.";
