<?php
// 1. Inject Orbs via sidebar.js
$jsFile = 'C:/xampp/htdocs/cit_ums/public/js/sidebar.js';
$jsContent = file_get_contents($jsFile);

$orbsInjection = <<<JS

document.addEventListener("DOMContentLoaded", function() {
    if (!document.querySelector('.blur-orb')) {
        let orbsHtml = '<div class="blur-orb orb-1"></div><div class="blur-orb orb-2"></div><div class="blur-orb orb-3"></div>';
        document.body.insertAdjacentHTML('afterbegin', orbsHtml);
    }
});
JS;

if (strpos($jsContent, 'blur-orb') === false) {
    file_put_contents($jsFile, $jsContent . $orbsInjection);
}

// 2. Add Global Glassmorphism CSS to style.css
$cssFile = 'C:/xampp/htdocs/cit_ums/public/css/style.css';
$cssContent = file_get_contents($cssFile);

$glassCSS = <<<CSS

/* GLOBALLY INJECTED GLASSMORPHISM SETTINGS */
body {
    background-color: #f4f7f9;
    position: relative;
}

/* Animated Floating Background Orbs */
.blur-orb {
    position: fixed;
    border-radius: 50%;
    filter: blur(100px);
    z-index: -1;
    opacity: 0.5;
    pointer-events: none;
}
.orb-1 {
    width: 500px; height: 500px;
    background: #f97316; /* Vibrant Orange */
    top: -100px; left: -100px;
    animation: floatOrb 12s ease-in-out infinite;
}
.orb-2 {
    width: 700px; height: 700px;
    background: #3b82f6; /* Bright Blue */
    bottom: -200px; right: -150px;
    animation: floatOrb 18s ease-in-out infinite reverse;
}
.orb-3 {
    width: 400px; height: 400px;
    background: #10b981; /* Soft Green */
    top: 30%; left: 50%;
    animation: floatOrb 15s ease-in-out infinite;
}

@keyframes floatOrb {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(40px, 60px) scale(1.1); }
}

/* Glass UI Elements */
.bg-white, .stat-card, .table-container, .bg-card {
    background: rgba(255, 255, 255, 0.7) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    border: 1px solid rgba(255, 255, 255, 0.5) !important;
}

/* Make Sidebar Glass */
.sidebar {
    background: rgba(15, 23, 42, 0.85) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    border-right: 1px solid rgba(255, 255, 255, 0.1) !important;
}

/* Table specific overrides */
.table th {
    background: rgba(248, 250, 252, 0.5) !important;
    backdrop-filter: blur(10px);
}
.table tbody tr {
    background: transparent !important;
}
.table tbody tr:hover {
    background: rgba(255, 255, 255, 0.4) !important;
}

CSS;

if (strpos($cssContent, 'GLOBALLY INJECTED GLASSMORPHISM SETTINGS') === false) {
    file_put_contents($cssFile, $cssContent . $glassCSS);
}

// 3. To make sure it doesn't conflict with login.php, clean up the duplicate CSS in login.php
$loginFile = 'C:/xampp/htdocs/cit_ums/app/Views/auth/login.php';
if (file_exists($loginFile)) {
    $loginContent = file_get_contents($loginFile);
    // Remove the hardcoded orbs in login.php so JS takes over and doesn't double-render
    $loginContent = preg_replace('/<div class="blur-orb orb-\d"><\/div>/', '', $loginContent);
    // We can leave the CSS block in login.php as it will just be overridden or coexist fine, 
    // but the actual HTML orbs should be removed to prevent doubling.
    file_put_contents($loginFile, $loginContent);
}

echo "Global glassmorphism theme applied.";
