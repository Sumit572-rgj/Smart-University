<?php
$f = 'C:/xampp/htdocs/cit_ums/public/css/style.css';
$c = file_get_contents($f);

$mobileCSS = <<<CSS

/* ========================================================
   UNIVERSAL MOBILE COMPATIBILITY FIXES
   ======================================================== */
@media (max-width: 768px) {
    /* 1. Prevent Horizontal Scroll from Flexboxes */
    .flex {
        flex-wrap: wrap;
    }
    .flex-nowrap {
        flex-wrap: nowrap !important;
    }

    /* 2. Force Fixed-Width Columns to Stack */
    .w-1\/2, .w-1\/3, .w-1\/4, .w-3\/4 {
        width: 100% !important;
    }

    /* 3. Force Grid Layouts to Single Column */
    .grid-cols-2, .grid-cols-3, .grid-cols-4 {
        grid-template-columns: 1fr !important;
    }

    /* 4. Reduce Giant Paddings */
    .p-6, .p-8 {
        padding: 1.25rem !important;
    }
    .content-wrapper {
        padding: 1rem !important;
    }
    .stat-card {
        padding: 1.25rem !important;
    }

    /* 5. Fix Giant Text */
    h1, h2, .text-2xl, .text-3xl {
        font-size: 1.5rem !important;
    }

    /* 6. Fix Button Alignment (Make them full width on mobile) */
    form .btn, .table-container .btn {
        width: 100% !important;
        margin-bottom: 0.5rem !important;
    }
    .btn {
        text-align: center;
        display: block;
    }

    /* 7. Fix Pill Tabs Overflowing */
    .pill-tabs-container {
        flex-direction: column !important;
        width: 100% !important;
        border-radius: 1rem !important;
        padding: 1rem !important;
    }
    .pill-tab-btn {
        width: 100% !important;
        border-radius: 0.5rem !important;
        margin-bottom: 0.25rem !important;
    }

    /* 8. Make Tables Compact and Swipeable */
    .table th, .table td {
        padding: 0.75rem 0.5rem !important;
        font-size: 0.85rem !important;
    }
    .table-container {
        border-radius: 0.5rem !important;
        margin-bottom: 1rem !important;
        -webkit-overflow-scrolling: touch;
    }
    
    /* 9. Topbar adjustments */
    .topbar {
        padding: 0.75rem 1rem !important;
        flex-wrap: nowrap !important;
    }
    .user-menu {
        flex-wrap: nowrap !important;
    }
    .user-menu .btn {
        width: auto !important;
        margin-bottom: 0 !important;
        padding: 0.4rem 0.8rem !important;
        font-size: 0.85rem !important;
    }
}
CSS;

if (strpos($c, 'UNIVERSAL MOBILE COMPATIBILITY FIXES') === false) {
    file_put_contents($f, $c . "\n" . $mobileCSS);
}
echo "Universal mobile fixes applied.";
