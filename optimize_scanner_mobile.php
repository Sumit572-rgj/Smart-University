<?php
$f = 'C:/xampp/htdocs/cit_ums/public/css/style.css';
$c = file_get_contents($f);

$scannerMobileCss = <<<CSS

/* ========================================================
   CRITICAL MOBILE FIXES FOR SECURITY SCANNER
   ======================================================== */
@media (max-width: 768px) {
    /* Make the camera feed container responsive */
    #reader {
        width: 100% !important;
        border: none !important;
    }
    #reader video {
        width: 100% !important;
        height: auto !important;
        object-fit: cover;
    }
    
    /* Make the Digital ID Card Details stack on mobile */
    .digital-id-details {
        grid-template-columns: 1fr !important;
        text-align: center !important;
    }
    
    /* Make the manual ID input stack on mobile */
    .scanner-manual-input {
        flex-direction: column !important;
    }
    .scanner-manual-input button {
        width: 100% !important;
    }
    
    /* Reduce padding for the camera prompt */
    #camera-prompt {
        padding: 1rem !important;
    }
    
    /* Ensure the scanner section has full width */
    .scanner-container {
        padding: 1rem !important;
    }
}
CSS;

if (strpos($c, 'CRITICAL MOBILE FIXES FOR SECURITY SCANNER') === false) {
    file_put_contents($f, $c . "\n" . $scannerMobileCss);
}

// Now update the Gate view to use these classes
$gateFile = 'C:/xampp/htdocs/cit_ums/app/Views/gate/index.php';
$gateHtml = file_get_contents($gateFile);

// 1. Add class to digital ID details grid
$oldGrid = '<div style="background: #f8fafc; border-radius: 0.5rem; padding: 1rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; text-align: left;">';
$newGrid = '<div class="digital-id-details" style="background: #f8fafc; border-radius: 0.5rem; padding: 1rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; text-align: left;">';
$gateHtml = str_replace($oldGrid, $newGrid, $gateHtml);

// 2. Add class to manual input flex
$oldInput = '<div style="display:flex;gap:0.5rem;">';
$newInput = '<div class="scanner-manual-input" style="display:flex;gap:0.5rem;">';
$gateHtml = str_replace($oldInput, $newInput, $gateHtml);

// 3. Add class to scanner container
$oldContainer = '<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">';
$newContainer = '<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 scanner-container">';
$gateHtml = str_replace($oldContainer, $newContainer, $gateHtml);

file_put_contents($gateFile, $gateHtml);

echo "Gate scanner mobile optimization applied.";
