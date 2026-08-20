<?php
$f = 'C:/xampp/htdocs/cit_ums/public/css/style.css';
$c = file_get_contents($f);

// Better button styling and variants
$oldBtn = <<<CSS
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    text-align: center;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.65rem 1.25rem;
    font-size: 0.95rem;
    border-radius: 0.5rem;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.08);
}
.btn:active {
    transform: translateY(0);
}
.btn-primary {
    color: #fff;
    background: linear-gradient(135deg, var(--cit-blue) 0%, var(--cit-blue-light) 100%);
    border: none;
}
.btn-primary:hover {
    background: linear-gradient(135deg, var(--cit-blue-light) 0%, var(--cit-blue) 100%);
}
CSS;

$newBtn = <<<CSS
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    text-align: center;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.6rem 1.4rem;
    font-size: 0.9rem;
    border-radius: 0.5rem;
    transition: all 0.2s ease-in-out;
    cursor: pointer;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    letter-spacing: 0.02em;
}
.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.btn:active {
    transform: translateY(0);
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}
.btn-primary {
    color: #fff;
    background: linear-gradient(135deg, var(--cit-blue) 0%, var(--cit-blue-light) 100%);
    border: 1px solid var(--cit-blue-light);
}
.btn-primary:hover {
    background: linear-gradient(135deg, #283548 0%, var(--cit-blue) 100%);
    border-color: #283548;
}
.btn-secondary {
    color: var(--cit-blue);
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
}
.btn-secondary:hover {
    background: #e2e8f0;
    color: var(--cit-blue-light);
}
.btn-danger {
    color: #fff;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border: 1px solid #dc2626;
}
.btn-danger:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
}
.btn-success {
    color: #fff;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: 1px solid #059669;
}
.btn-success:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
}
.btn-outline {
    color: var(--cit-blue);
    background: transparent;
    border: 2px solid var(--cit-blue);
}
.btn-outline:hover {
    background: var(--cit-blue);
    color: #fff;
}
CSS;

$c = str_replace($oldBtn, $newBtn, $c);

// Refine forms slightly
$oldForm = <<<CSS
.form-input {
    appearance: none;
    background-color: #fff;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    padding: 0.65rem 1rem;
    font-size: 0.95rem;
    color: var(--text-main);
    transition: all 0.2s ease-in-out;
}
.form-input:focus {
    outline: none;
    border-color: var(--cit-orange);
    box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.15);
}
CSS;

$newForm = <<<CSS
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

$c = str_replace($oldForm, $newForm, $c);

// Refine table hover
$oldTableHover = <<<CSS
.table tbody tr:hover {
    background-color: #f1f5f9;
}
CSS;

$newTableHover = <<<CSS
.table tbody tr:hover {
    background-color: #f8fafc;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    transform: scale(1.001);
}
CSS;

$c = str_replace($oldTableHover, $newTableHover, $c);

file_put_contents($f, $c);
echo "CSS Updated.";
