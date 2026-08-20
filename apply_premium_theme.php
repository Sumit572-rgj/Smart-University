<?php
$f = 'C:/xampp/htdocs/cit_ums/public/css/style.css';
$c = file_get_contents($f);

// Remove the previously appended solid enterprise block if it exists
$c = preg_replace('/\/\* Clean Professional Theme \*\/.*?$/s', '', $c);

// Also remove any existing !important overrides to avoid conflicts
$c = preg_replace('/\/\* GLOBALLY INJECTED GLASSMORPHISM SETTINGS \*\/.*?$/s', '', $c);

$premiumCSS = <<<CSS

/* ========================================================
   PREMIUM MODERN FINTECH & SAAS THEME (NO GLASS)
   ======================================================== */

body {
    background-color: #f3f4f6 !important;
    font-family: 'Inter', system-ui, -apple-system, sans-serif !important;
    color: #1e293b !important;
}

/* Sidebar Light Theme Overrides */
.sidebar {
    background: #ffffff !important;
    border-right: 1px solid #e5e7eb !important;
    box-shadow: none !important;
    color: #334155 !important;
}
.sidebar-header {
    background: #ffffff !important;
    color: #0f172a !important;
    border-bottom: 1px solid #f1f5f9 !important;
}
.nav-link {
    color: #64748b !important;
    border-left: 3px solid transparent !important;
    margin: 0.25rem 0.75rem !important;
    border-radius: 0.5rem !important;
}
.nav-link:hover {
    background-color: #f8fafc !important;
    color: #0f172a !important;
    border-left-color: transparent !important;
}
.nav-link.active {
    background-color: #fff7ed !important;
    color: #ea580c !important;
    border-left: none !important;
    font-weight: 600 !important;
    box-shadow: 0 1px 2px rgba(234, 88, 12, 0.05) !important;
}

/* Header Light Theme */
.topbar {
    background: #ffffff !important;
    border-bottom: 1px solid #e5e7eb !important;
    box-shadow: none !important;
}

/* Beautiful Floating Cards */
.bg-white, .stat-card, .table-container, .bg-card, .login-card {
    background: #ffffff !important;
    border: 1px solid #f1f5f9 !important;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.03), 0 8px 10px -6px rgba(0, 0, 0, 0.01) !important;
    border-radius: 1rem !important;
    transition: transform 0.2s ease, box-shadow 0.2s ease !important;
}

.stat-card:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02) !important;
}

/* Strip top border from old login card */
.login-card {
    border-top: none !important;
}

/* Beautiful Clean Tables */
.table th {
    background: #f8fafc !important;
    color: #475569 !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    font-size: 0.75rem !important;
    letter-spacing: 0.05em !important;
    border-bottom: 1px solid #e2e8f0 !important;
    padding: 1rem !important;
}
.table td {
    border-bottom: 1px solid #f1f5f9 !important;
    color: #334155 !important;
}
.table tbody tr {
    background: #ffffff !important;
    transition: background 0.2s ease !important;
}
.table tbody tr:hover {
    background: #f8fafc !important;
}

/* Pill Buttons */
.btn {
    border-radius: 9999px !important;
    font-weight: 600 !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
}
.btn-primary {
    background: #f97316 !important;
    color: #fff !important;
}
.btn-primary:hover {
    background: #ea580c !important;
    box-shadow: 0 4px 6px -1px rgba(234, 88, 12, 0.3) !important;
}

/* Beautiful Inputs */
.form-input {
    background-color: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 0.75rem !important;
    box-shadow: 0 1px 2px rgba(0,0,0,0.02) inset !important;
}
.form-input:focus {
    background-color: #ffffff !important;
    border-color: #f97316 !important;
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15) !important;
}

CSS;

file_put_contents($f, trim($c) . "\n\n" . $premiumCSS);
echo "Premium modern fintech theme applied.";
