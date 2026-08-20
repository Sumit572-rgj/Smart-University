<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/exam/results.php';
$c = file_get_contents($f);

// 1. Wrap the table in a responsive div
$c = str_replace('<table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem;">', '<div style="overflow-x: auto; width: 100%; margin-bottom: 2rem; border-radius: 0.5rem; border: 1px solid #e2e8f0;"><table style="width: 100%; min-width: 600px; border-collapse: collapse;">', $c);
$c = str_replace('</table>', '</table></div>', $c);

// 2. Add media queries for mobile scaling
$mediaQueries = <<<'HTML'
    <style> 
        @media print { 
            body { background: white !important; }
            .no-print, .chatbot-btn, .sidebar, .topbar { display: none !important; } 
        } 
        
        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .results-container {
                padding: 1rem !important;
            }
            .student-header h3 {
                font-size: 1.4rem !important;
            }
            .student-header div {
                font-size: 0.85rem !important;
                display: flex !important;
                flex-direction: column !important;
                gap: 0.5rem !important;
                border-radius: 0.5rem !important;
            }
            .student-header span {
                display: block !important;
            }
            /* Hide the pipe separator on mobile */
            .student-header .separator {
                display: none !important;
            }
        }
    </style>
HTML;

$c = preg_replace('/<style>[\s\S]*?<\/style>/', $mediaQueries, $c);

// 3. Add classes to the header to match the new media queries
$c = str_replace('<div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 800px; margin: 0 auto; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">', '<div class="results-container" style="background: white; border-radius: 1rem; padding: 2rem; max-width: 800px; margin: 0 auto; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">', $c);
$c = str_replace('<div style="text-align: center; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 2px dashed #e2e8f0;">', '<div class="student-header" style="text-align: center; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 2px dashed #e2e8f0;">', $c);

// Replace the pipe with a span so we can hide it
$c = str_replace('&nbsp;|&nbsp;', '<span class="separator" style="display:inline;">&nbsp;|&nbsp;</span>', $c);

file_put_contents($f, $c);
echo "Results page made mobile compatible.\n";
