<?php
$f = 'C:/xampp/htdocs/cit_ums/public/css/style.css';
$c = file_get_contents($f);

$tableMobileFix = <<<CSS

/* ========================================================
   CRITICAL TABLE & OVERFLOW FIXES FOR MOBILE
   ======================================================== */
body, html {
    max-width: 100vw;
    overflow-x: hidden;
}

.dashboard-layout {
    max-width: 100vw;
    overflow-x: hidden;
}

.main-content {
    max-width: 100vw;
    overflow-x: hidden;
}

/* Force Table Containers to always scroll, never hide content */
.table-container {
    width: 100%;
    max-width: 100%;
    overflow-x: auto !important;
    overflow-y: hidden !important;
    display: block;
    -webkit-overflow-scrolling: touch;
}

/* Prevent tables from squishing text and making it unreadable */
.table th, .table td {
    white-space: nowrap !important;
}

/* Make sure the main content wrapper doesn't push off screen */
.content-wrapper {
    width: 100%;
    max-width: 100vw;
    box-sizing: border-box;
}

CSS;

if (strpos($c, 'CRITICAL TABLE & OVERFLOW FIXES FOR MOBILE') === false) {
    file_put_contents($f, $c . "\n" . $tableMobileFix);
}
echo "Table constraints and mobile overflow fixes applied.";
