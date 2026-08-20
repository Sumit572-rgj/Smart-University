<?php
$viewsDir = __DIR__ . '/app/Views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        
        $changed = false;
        
        // Fix toggle logic and hidden class
        // Current: onclick="document.getElementById('notif-dropdown...').classList.toggle('hidden')"
        // New: onclick="let d = document.getElementById('notif-dropdown...'); d.style.display = d.style.display === 'none' ? 'block' : 'none';"
        // Current: class="hidden shadow-lg..." style="..."
        // New: class="shadow-lg..." style="display: none; ..."
        
        if (preg_match('/onclick="document\.getElementById\(\'([^\']+)\'\)\.classList\.toggle\(\'hidden\'\)"/', $content, $matches)) {
            $id = $matches[1];
            $newOnclick = 'onclick="let d = document.getElementById(\'' . $id . '\'); d.style.display = d.style.display === \'none\' ? \'block\' : \'none\';"';
            $content = str_replace($matches[0], $newOnclick, $content);
            $changed = true;
        }

        if (strpos($content, 'class="hidden shadow-lg border border-gray-200"') !== false) {
            $content = str_replace('class="hidden shadow-lg border border-gray-200" style="position: absolute;', 'class="shadow-lg border border-gray-200" style="display: none; position: absolute;', $content);
            $changed = true;
        }
        
        if ($changed) {
            file_put_contents($file->getRealPath(), $content);
            echo "Fixed Bell Toggle: " . $file->getFilename() . "\n";
        }
    }
}
echo "Done.";
