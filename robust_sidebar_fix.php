<?php
$viewsDir = 'C:/xampp/htdocs/cit_ums/app/Views';
$iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($iter as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $c = file_get_contents($file->getPathname());
        
        $changed = false;
        
        // 1. Security Guard
        if (strpos($c, 'Security Guard Management') === false) {
            $c = preg_replace(
                '/(<a href="\/cit_ums\/warden" class="nav-link(?: active)?">Warden Management<\/a>)/i',
                "$1\n                <a href=\"/cit_ums/securityguard\" class=\"nav-link\">Security Guard Management</a>",
                $c
            );
            $changed = true;
        }
        
        // 2. Student Management for Faculty
        if (strpos($c, '<div class="text-xs text-gray-500 font-bold uppercase mt-4 mb-2 px-4">Faculty Features</div>') !== false && strpos($c, 'Student Management', strpos($c, 'Faculty Features')) === false) {
            $c = preg_replace(
                '/(<div class="text-xs text-gray-500 font-bold uppercase mt-4 mb-2 px-4">Faculty Features<\/div>)/i',
                "$1\n                <a href=\"/cit_ums/student\" class=\"nav-link\">Student Management</a>",
                $c
            );
            $changed = true;
        }
        
        if ($changed) {
            file_put_contents($file->getPathname(), $c);
            echo "Updated " . $file->getFilename() . "\n";
        }
    }
}
echo "Done replacing sidebars.";
