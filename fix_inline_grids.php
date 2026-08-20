<?php
$f = 'C:/xampp/htdocs/cit_ums/public/css/style.css';
$c = file_get_contents($f);

$inlineGridFix = <<<CSS

/* ========================================================
   CRITICAL FIX FOR INLINE GRIDS ON MOBILE
   ======================================================== */
@media (max-width: 768px) {
    /* Force ANY grid with inline style grid-template-columns to become 1 column */
    .grid {
        grid-template-columns: 1fr !important;
    }
}
CSS;

if (strpos($c, 'CRITICAL FIX FOR INLINE GRIDS ON MOBILE') === false) {
    file_put_contents($f, $c . "\n" . $inlineGridFix);
}
echo "Inline grids overridden for mobile.";
