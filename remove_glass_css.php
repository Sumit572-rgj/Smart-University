<?php
$f = 'C:/xampp/htdocs/cit_ums/public/css/style.css';
$c = file_get_contents($f);

// Remove the global glassmorphism settings
$c = preg_replace('/\/\* GLOBALLY INJECTED GLASSMORPHISM SETTINGS \*\/.*?$/s', '', $c);

// Also let's redefine some clean, professional solid colors just in case the previous CSS stripped something out.
$cleanCss = <<<CSS

/* Clean Professional Theme */
body {
    background-color: #f8fafc;
}

.bg-white, .stat-card, .table-container, .bg-card {
    background: #ffffff !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    border: 1px solid #e2e8f0 !important;
}

.sidebar {
    background: var(--cit-blue) !important;
    backdrop-filter: none !important;
    -webkit-backdrop-filter: none !important;
    border-right: none !important;
}

.table th {
    background: #f8fafc !important;
    backdrop-filter: none !important;
}
.table tbody tr {
    background: #ffffff !important;
}
.table tbody tr:hover {
    background: #f1f5f9 !important;
}
CSS;

file_put_contents($f, trim($c) . "\n\n" . $cleanCss);
echo "Glass CSS removed and replaced with clean solid enterprise theme.";
