<?php
$directory = new RecursiveDirectoryIterator('C:/xampp/htdocs/cit_ums/app/Views');
$iterator = new RecursiveIteratorIterator($directory);
$count = 0;

foreach ($iterator as $info) {
    if ($info->getExtension() === 'php') {
        $file = $info->getPathname();
        $content = file_get_contents($file);
        
        // If it has <head> but no viewport
        if (stripos($content, '<head>') !== false && stripos($content, 'viewport') === false) {
            $content = str_ireplace('<head>', "<head>\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">", $content);
            file_put_contents($file, $content);
            $count++;
        }
    }
}

echo "Added viewport to $count files.\n";

// Now add hamburger menu to sidebar.php
$sidebarFile = 'C:/xampp/htdocs/cit_ums/app/Views/partials/sidebar.php';
$sb = file_get_contents($sidebarFile);

if (!strpos($sb, 'mobile-menu-btn')) {
    $hamburger = <<<'HTML'
<!-- Mobile Hamburger Button -->
<button id="mobile-menu-btn" style="position: fixed; top: 1rem; left: 1rem; z-index: 1000; background: var(--cit-blue); color: white; border: none; padding: 0.5rem; border-radius: 0.25rem; cursor: pointer; display: none;">
    <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
</button>

<!-- Mobile Overlay -->
<div id="mobile-overlay" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 90; display: none;"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('mobile-menu-btn');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('mobile-overlay');
    
    // Check if we are on mobile
    if (window.innerWidth <= 768) {
        btn.style.display = 'block';
    }
    window.addEventListener('resize', () => {
        btn.style.display = window.innerWidth <= 768 ? 'block' : 'none';
    });

    btn.addEventListener('click', () => {
        sidebar.style.left = '0px';
        overlay.style.display = 'block';
    });

    overlay.addEventListener('click', () => {
        sidebar.style.left = '-260px';
        overlay.style.display = 'none';
    });
});
</script>
HTML;

    $sb = $hamburger . "\n" . $sb;
    file_put_contents($sidebarFile, $sb);
    echo "Added mobile hamburger menu to sidebar.\n";
}
