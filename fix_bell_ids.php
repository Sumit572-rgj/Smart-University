<?php
$viewsDir = __DIR__ . '/app/Views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        
        $changed = false;
        
        // The issue is uniqid() was used twice in the injection, creating a mismatch between the button and the dropdown.
        // Let's replace uniqid() with a static 'bell-dropdown' ID.
        
        // Match the onclick handler that uses uniqid()
        $content = preg_replace('/onclick="document\.getElementById\(\'notif-dropdown-<\?= uniqid\(\) \?>\'\)\.classList\.toggle\(\'hidden\'\)"/', 'onclick="let d = document.getElementById(\'bell-dropdown\'); d.style.display = d.style.display === \'none\' ? \'block\' : \'none\';"', $content);
        
        $content = preg_replace('/onclick="let d = document\.getElementById\(\'notif-dropdown-<\?= uniqid\(\) \?>\'\); d\.style\.display = d\.style\.display === \'none\' \? \'block\' : \'none\';"/', 'onclick="let d = document.getElementById(\'bell-dropdown\'); d.style.display = d.style.display === \'none\' ? \'block\' : \'none\';"', $content);

        // Also fix the older onclick that just used 'notif-dropdown'
        $content = preg_replace('/onclick="let d = document\.getElementById\(\'notif-dropdown\'\); d\.style\.display = d\.style\.display === \'none\' \? \'block\' : \'none\';"/', 'onclick="let d = document.getElementById(\'bell-dropdown\'); d.style.display = d.style.display === \'none\' ? \'block\' : \'none\';"', $content);

        // Fix the dropdown div ID
        $content = preg_replace('/id="notif-dropdown-<\?= uniqid\(\) \?>"/', 'id="bell-dropdown"', $content);
        $content = preg_replace('/id="notif-dropdown"/', 'id="bell-dropdown"', $content);
        
        // Ensure display: none is there
        $content = str_replace('class="hidden shadow-lg border border-gray-200"', 'class="shadow-lg border border-gray-200" style="display: none; position: absolute;', $content);
        
        file_put_contents($file->getRealPath(), $content);
        echo "Fixed bell in " . $file->getFilename() . "\n";
    }
}
echo "Done.";
