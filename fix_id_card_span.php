<?php
$f = 'C:/xampp/htdocs/cit_ums/public/css/style.css';
$c = file_get_contents($f);

// Update style.css to force child divs of digital-id-details to span 1
$cssPatch = <<<CSS

/* ========================================================
   CRITICAL FIX FOR DIGITAL ID CARD SPANS ON MOBILE
   ======================================================== */
@media (max-width: 768px) {
    .digital-id-details {
        grid-template-columns: 1fr !important;
        text-align: center !important;
    }
    .digital-id-details > div {
        grid-column: span 1 !important;
    }
    
    /* Ensure the success alert text wraps properly */
    .alert {
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
}
CSS;

if (strpos($c, 'CRITICAL FIX FOR DIGITAL ID CARD SPANS ON MOBILE') === false) {
    file_put_contents($f, $c . "\n" . $cssPatch);
}
echo "Digital ID card spans fixed.";
