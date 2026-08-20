<?php
$viewsDir = __DIR__ . '/app/Views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        
        // Replace the long inline onclick with the clean function call
        $content = preg_replace('/onclick="if\(window\.innerWidth<=768\)[^"]*"/i', 'onclick="toggleSidebar()"', $content);
        
        // Add the script tag before </body> if not present
        if (strpos($content, '<script src="/cit_ums/js/sidebar.js') === false) {
            $content = str_ireplace('</body>', '<script src="/cit_ums/js/sidebar.js?v=' . time() . '"></script>' . "\n" . '</body>', $content);
        }
        
        file_put_contents($file->getRealPath(), $content);
    }
}
echo "Done wiring sidebar.js.";
