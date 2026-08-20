<?php
$viewsDir = 'C:/xampp/htdocs/cit_ums/app/Views';
$iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
$count = 0;

foreach ($iter as $file) {
    if ($file->getExtension() === 'php') {
        $path = $file->getPathname();
        $c = file_get_contents($path);
        
        $modified = false;
        
        // Inject into Student section
        if (strpos($c, '<!-- Room Maintenance Link -->') === false) {
            $studentPattern = '/(<a href="\/cit_ums\/outpass" class="nav-link">Outpass Requests<\/a>)/s';
            if (preg_match($studentPattern, $c)) {
                $c = preg_replace($studentPattern, "$1\n                <a href=\"/cit_ums/roommaintenance\" class=\"nav-link\"><!-- Room Maintenance Link -->Room Maintenance</a>", $c);
                $modified = true;
            }
            
            // Inject into Warden section
            $wardenPattern = '/(<a href="\/cit_ums\/outpass" class="nav-link">Outpass Approvals<\/a>)/s';
            if (preg_match($wardenPattern, $c)) {
                $c = preg_replace($wardenPattern, "$1\n                <a href=\"/cit_ums/roommaintenance\" class=\"nav-link\"><!-- Room Maintenance Link -->Room Maintenance Tracking</a>", $c);
                $modified = true;
            }
        }
        
        if ($modified) {
            file_put_contents($path, $c);
            $count++;
        }
    }
}
echo "Injected Room Maintenance into sidebar of $count view files.\n";
