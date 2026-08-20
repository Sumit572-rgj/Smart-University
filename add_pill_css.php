<?php
$f = 'C:/xampp/htdocs/cit_ums/public/css/style.css';
$c = file_get_contents($f);

$newCss = <<<CSS

/* Pill Tabs Component */
.pill-tab-btn {
    padding: 0.6rem 1.75rem;
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.95rem;
    color: #475569;
    border: none;
    background: transparent;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
}
.pill-tab-btn:hover {
    color: #0f172a;
    background: rgba(255,255,255,0.5);
}
.pill-tab-btn.modern-tab-active {
    background: #ffffff;
    color: var(--cit-orange);
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
    transform: scale(1.02);
}

CSS;

$c .= $newCss;

file_put_contents($f, $c);
echo "Added pill tab css.";
