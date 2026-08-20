<?php
$viewsDir = __DIR__ . '/app/Views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        $changed = false;

        // Add overlay if not present
        if (strpos($content, '<div class="sidebar-overlay"') === false) {
            $content = preg_replace(
                '/(<div class="dashboard-layout"[^>]*>)/i',
                "$1\n    <div class=\"sidebar-overlay\" onclick=\"document.querySelector('.sidebar').classList.remove('open'); this.classList.remove('open');\"></div>",
                $content
            );
            $changed = true;
        }

        // Add toggle button if not present
        if (strpos($content, '<button class="menu-toggle"') === false) {
            $content = preg_replace(
                '/(<header[^>]*topbar[^>]*>)/i',
                "$1\n            <div style=\"display:flex; align-items:center;\">\n                <button class=\"menu-toggle\" onclick=\"document.querySelector('.sidebar').classList.toggle('open'); document.querySelector('.sidebar-overlay').classList.toggle('open');\">☰</button>",
                $content
            );
            // Close the div wrapping the menu-toggle and welcome string
            // We just wrap the menu toggle and let the flexbox handle it alongside the welcome text
            $content = preg_replace(
                '/(<div class="welcome[^>]*>.*?<\/div>)/is',
                "$1\n            </div>",
                $content
            );
            $changed = true;
        }

        if ($changed) {
            file_put_contents($file->getRealPath(), $content);
            echo "Injected sidebar toggle to " . $file->getFilename() . "\n";
        }
    }
}
echo "Done.";
