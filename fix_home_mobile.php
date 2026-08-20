<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/home/index.php';
$c = file_get_contents($f);

// Add media queries to the homepage
$cssToAdd = <<<CSS

        /* Mobile Responsiveness for Homepage */
        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
                flex-direction: column;
                gap: 1rem;
            }
            .hero {
                padding: 1.5rem;
                margin-top: 1rem;
            }
            .hero h1 {
                font-size: 2.5rem;
            }
            .hero p {
                font-size: 1rem;
                margin-bottom: 2rem;
            }
            .floating-cards {
                flex-direction: column;
                align-items: center;
                gap: 1rem;
            }
            .feature-card {
                width: 100%;
                max-width: 320px;
            }
        }
CSS;

if (strpos($c, 'Mobile Responsiveness for Homepage') === false) {
    // Inject right before </style>
    $c = str_replace('</style>', $cssToAdd . "\n    </style>", $c);
    file_put_contents($f, $c);
}
echo "Homepage mobile CSS fixed.";
