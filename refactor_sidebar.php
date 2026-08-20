<?php
$dashboardFile = 'C:/xampp/htdocs/cit_ums/app/Views/dashboard/index.php';
$dashContent = file_get_contents($dashboardFile);

// 1. Extract the sidebar from dashboard
$pattern = '/<div class="sidebar-overlay"[\s\S]*?<\/aside>/m';
if (preg_match($pattern, $dashContent, $matches)) {
    $sidebarHTML = $matches[0];
    
    // Add dynamic active class logic
    $sidebarHTML = "<?php \$uri = \$_SERVER['REQUEST_URI']; ?>\n" . $sidebarHTML;
    $sidebarHTML = preg_replace('/class="nav-link active"/', 'class="nav-link"', $sidebarHTML); // strip hardcoded active
    $sidebarHTML = preg_replace('/<a href="(\/cit_ums\/[^"]+)" class="nav-link"/', '<a href="$1" class="nav-link <?= strpos($uri, \'$1\') === 0 ? \'active\' : \'\' ?>" ', $sidebarHTML);
    // Note: the regex above is simplistic, a better way is to handle it via JS, but JS might flicker. Let's just strip 'active' and let a tiny JS script handle it, or just leave it stripped for now, it's cleaner than broken layout.
    
    // Actually, let's just strip the hardcoded 'active' and add a tiny JS script at the bottom of the sidebar to add 'active' based on window.location.pathname
    $sidebarHTML = preg_replace('/class="nav-link active"/', 'class="nav-link"', $sidebarHTML);
    $sidebarHTML .= <<<JS
\n<script>
    document.addEventListener("DOMContentLoaded", function() {
        const links = document.querySelectorAll('.sidebar-nav .nav-link');
        const currentPath = window.location.pathname;
        links.forEach(link => {
            if (link.getAttribute('href') === currentPath || (currentPath.startsWith(link.getAttribute('href')) && link.getAttribute('href') !== '/cit_ums/dashboard')) {
                link.classList.add('active');
            } else if (currentPath === '/cit_ums/dashboard' && link.getAttribute('href') === '/cit_ums/dashboard') {
                link.classList.add('active');
            }
        });
    });
</script>
JS;

    file_put_contents('C:/xampp/htdocs/cit_ums/app/Views/partials/sidebar.php', $sidebarHTML);
    
    // 2. Loop through all views and replace their sidebar with the include statement
    $viewsDir = new RecursiveDirectoryIterator('C:/xampp/htdocs/cit_ums/app/Views');
    $iterator = new RecursiveIteratorIterator($viewsDir);
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $path = $file->getPathname();
            // skip partials
            if (strpos($path, 'partials') !== false || strpos($path, 'auth') !== false || strpos($path, 'home') !== false) {
                continue; // don't touch partials, login, or homepage (they don't have the dashboard sidebar)
            }
            
            $content = file_get_contents($path);
            
            // Some views might not have an overlay, so we check just for aside
            // Or we check for both.
            $hasReplaced = false;
            
            if (preg_match('/<div class="sidebar-overlay"[\s\S]*?<\/aside>/m', $content)) {
                $content = preg_replace('/<div class="sidebar-overlay"[\s\S]*?<\/aside>/m', '<?php include \'../app/Views/partials/sidebar.php\'; ?>', $content);
                $hasReplaced = true;
            } else if (preg_match('/<aside class="sidebar"[\s\S]*?<\/aside>/m', $content)) {
                $content = preg_replace('/<aside class="sidebar"[\s\S]*?<\/aside>/m', '<?php include \'../app/Views/partials/sidebar.php\'; ?>', $content);
                $hasReplaced = true;
            }
            
            if ($hasReplaced) {
                file_put_contents($path, $content);
            }
        }
    }
    
    echo "Sidebar successfully extracted and injected globally!\n";
} else {
    echo "Could not find sidebar in dashboard/index.php\n";
}
