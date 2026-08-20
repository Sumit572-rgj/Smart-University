function toggleSidebar() {
    var sidebar = document.querySelector('.sidebar');
    var overlay = document.querySelector('.sidebar-overlay');
    if (!sidebar) return;
    
    if (window.innerWidth <= 768) {
        sidebar.classList.toggle('open');
        if (overlay) overlay.classList.toggle('open');
    } else {
        sidebar.classList.toggle('collapsed');
    }
}