<?php
$f = 'C:/xampp/htdocs/cit_ums/public/css/style.css';
$c = file_get_contents($f);

// 1. Upgrade Root Variables for a softer, more modern look
$oldRoot = <<<CSS
:root {
    --cit-blue: #0f172a; /* Deeper premium blue */
    --cit-blue-light: #1e293b;
    --cit-orange: #f97316; /* Vibrant premium orange */
    --cit-orange-hover: #ea580c;
    --text-main: #334155;
    --text-muted: #64748b;
    --bg-main: #f8fafc;
    --bg-card: #ffffff;
    --border-color: #e2e8f0;
    --error-red: #ef4444;
    --success-green: #10b981;
}
CSS;

$newRoot = <<<CSS
:root {
    --cit-blue: #0f172a; 
    --cit-blue-light: #334155;
    --cit-orange: #f97316; 
    --cit-orange-hover: #ea580c;
    --text-main: #1e293b;
    --text-muted: #64748b;
    --bg-main: #f4f7f9;
    --bg-card: #ffffff;
    --border-color: #e2e8f0;
    --error-red: #ef4444;
    --success-green: #10b981;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
}
CSS;
$c = str_replace($oldRoot, $newRoot, $c);

// 2. Refine general layout, cards, and glassmorphism
$oldCard = <<<CSS
.login-card {
    border-top: 5px solid var(--cit-orange);
}
CSS;

$newCard = <<<CSS
.login-card {
    border-top: 5px solid var(--cit-orange);
    box-shadow: var(--shadow-lg);
    border-radius: 1rem;
    backdrop-filter: blur(10px);
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}
.stat-card::after {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--cit-orange), #fb923c);
    opacity: 0;
    transition: opacity 0.3s ease;
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    border-color: #cbd5e1;
}
.stat-card:hover::after {
    opacity: 1;
}

/* Sidebar upgrades */
.sidebar {
    background: var(--cit-blue) !important;
    border-right: 1px solid #1e293b;
    box-shadow: 4px 0 10px rgba(0,0,0,0.05);
}
.sidebar-link {
    border-radius: 0.5rem;
    margin: 0.25rem 1rem;
    transition: all 0.2s ease;
}
.sidebar-link:hover {
    background: rgba(255,255,255,0.1) !important;
    transform: translateX(4px);
}

/* React-style Headers */
.content-wrapper h2 {
    font-size: 1.75rem;
    font-weight: 700;
    letter-spacing: -0.025em;
    color: var(--text-main);
    margin-bottom: 1.5rem;
}
CSS;

$c = str_replace($oldCard, $newCard, $c);

// 3. More refined forms
$oldForm = <<<CSS
.form-input {
    appearance: none;
    background-color: #fff;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    padding: 0.6rem 1rem;
    font-size: 0.95rem;
    color: var(--text-main);
    transition: all 0.2s ease-in-out;
    box-shadow: 0 1px 2px rgba(0,0,0,0.02) inset;
}
.form-input:hover {
    border-color: #9ca3af;
}
.form-input:focus {
    outline: none;
    border-color: var(--cit-orange);
    box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.15);
    background-color: #fffcf8;
}
CSS;

$newForm = <<<CSS
.form-input {
    appearance: none;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    color: var(--text-main);
    transition: all 0.2s ease-in-out;
}
.form-input:hover {
    border-color: #cbd5e1;
    background-color: #fff;
}
.form-input:focus {
    outline: none;
    border-color: var(--cit-orange);
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
    background-color: #fff;
}
label {
    font-weight: 600;
    color: var(--text-main);
    font-size: 0.875rem;
}
CSS;

$c = str_replace($oldForm, $newForm, $c);

// 4. Refined tables
$oldTable = <<<CSS
.table-container {
    background: var(--bg-card);
    border-radius: 0.75rem;
    border: 1px solid var(--border-color);
    overflow: hidden;
}
CSS;

$newTable = <<<CSS
.table-container {
    background: var(--bg-card);
    border-radius: 1rem;
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
CSS;

$c = str_replace($oldTable, $newTable, $c);

file_put_contents($f, $c);
echo "React-style CSS injected.";
