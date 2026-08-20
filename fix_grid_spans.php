<?php
$f = 'C:/xampp/htdocs/cit_ums/public/css/style.css';
$c = file_get_contents($f);

$spanFix = <<<CSS

/* ========================================================
   CRITICAL FIX FOR GRID SPANS ON MOBILE
   ======================================================== */
@media (max-width: 768px) {
    /* Prevent elements from spanning multiple columns when the grid is forced to 1 column */
    .grid > div, .grid > form, .grid > * {
        grid-column: span 1 !important;
    }
}
CSS;

if (strpos($c, 'CRITICAL FIX FOR GRID SPANS ON MOBILE') === false) {
    file_put_contents($f, $c . "\n" . $spanFix);
}
echo "Grid span mobile fix applied.";
