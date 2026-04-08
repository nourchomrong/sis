document.addEventListener("DOMContentLoaded", function () {

    const toggleBtn = document.getElementById('mobile-toggle');
    const sidebar = document.getElementById('logo-sidebar');
    const sidebarText = document.querySelectorAll('.sidebar-text');
    const mainContent = document.getElementById('main-content');

function updateSidebar() {
    const width = window.innerWidth;

    if (width >= 1024) {
        // Desktop
        mainContent.style.marginLeft = '16rem';
    } else {
        // Mobile
        mainContent.style.marginLeft = '0';
    }
}

    updateSidebar();
    window.addEventListener('resize', updateSidebar);

});