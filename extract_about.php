<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/home/index.php';
$c = file_get_contents($f);

// Find and extract the about section
$startStr = "<!-- About Us / Developer -->";
$endStr = "<!-- Footer -->";

$startPos = strpos($c, $startStr);
$endPos = strpos($c, $endStr, $startPos);

if ($startPos !== false && $endPos !== false) {
    // Cut it out
    $aboutSection = substr($c, $startPos, $endPos - $startPos);
    $c = substr_replace($c, "", $startPos, $endPos - $startPos);
    
    // Also change the link in nav
    $c = str_replace('<a href="#about">About</a>', '<a href="/cit_ums/about">About</a>', $c);
    
    file_put_contents($f, $c);
    
    // Now create the new about.php view
    $aboutView = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - CIT UMS</title>
    <link rel="stylesheet" href="/cit_ums/public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; font-family: 'Inter', sans-serif; background: #f8fafc; }
        .top-nav { display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 5%; background: white; border-bottom: 1px solid #e2e8f0; position: fixed; width: 100%; top: 0; z-index: 1000; box-sizing: border-box; }
        .nav-links a { margin: 0 1.5rem; text-decoration: none; color: #334155; font-weight: 600; transition: color 0.3s; }
        .nav-links a:hover { color: var(--cit-orange); }
        .about-container { margin-top: 100px; padding: 4rem 2rem; min-height: 80vh; }
    </style>
</head>
<body>

    <nav class="top-nav">
        <div style="font-weight: 800; font-size: 1.5rem; color: #0f172a; letter-spacing: -1px;">
            CIT<span>UMS</span>
        </div>
        <div class="nav-links">
            <a href="/cit_ums">Home</a>
            <a href="/cit_ums#features">Features</a>
            <a href="/cit_ums/about" style="color: var(--cit-orange);">About</a>
            <a href="/cit_ums#contact">Contact</a>
        </div>
        <a href="/cit_ums/auth/login" class="login-btn-top" style="background: var(--cit-orange); color: white; padding: 0.5rem 1.5rem; border-radius: 999px; text-decoration: none; font-weight: 600;">Login</a>
    </nav>

    <div class="about-container">
        $aboutSection
    </div>

</body>
</html>
HTML;

    file_put_contents('C:/xampp/htdocs/cit_ums/app/Views/home/about.php', $aboutView);
    echo "Extracted About section to separate page.\n";
} else {
    echo "Could not find About section in home/index.php\n";
}
