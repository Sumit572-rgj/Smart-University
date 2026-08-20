<?php
$f = 'C:/xampp/htdocs/cit_ums/public/css/style.css';
$c = file_get_contents($f);

// Enhance stat cards
$oldCard = <<<CSS
.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
    border-color: #cbd5e1;
}
CSS;

$newCard = <<<CSS
.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}
.stat-card::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--cit-blue), var(--cit-orange));
    opacity: 0;
    transition: opacity 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.05);
    border-color: #cbd5e1;
}
.stat-card:hover::after {
    opacity: 1;
}
CSS;

$c = str_replace($oldCard, $newCard, $c);

file_put_contents($f, $c);
echo "CSS Cards Updated.";
