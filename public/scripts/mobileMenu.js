// Mobile Menu Handler
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.getElementById('sidebar');
    const mobileOverlay = document.getElementById('mobileOverlay');

    if (!mobileMenuToggle || !sidebar || !mobileOverlay) {
        console.warn('Mobile menu elements not found');
        return;
    }

    // Toggle mobile menu
    mobileMenuToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleMobileMenu();
    });

    // Close menu when clicking overlay
    mobileOverlay.addEventListener('click', function() {
        closeMobileMenu();
    });

    // Close menu when clicking a link (on mobile)
    const sidebarLinks = sidebar.querySelectorAll('nav a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 1024) {
                closeMobileMenu();
            }
        });
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 1024) {
            closeMobileMenu();
        }
    });

    // Functions
    function toggleMobileMenu() {
        sidebar.classList.toggle('mobile-open');
        mobileMenuToggle.classList.toggle('active');
        mobileOverlay.classList.toggle('active');
        document.body.classList.toggle('menu-open');
    }

    function closeMobileMenu() {
        sidebar.classList.remove('mobile-open');
        mobileMenuToggle.classList.remove('active');
        mobileOverlay.classList.remove('active');
        document.body.classList.remove('menu-open');
    }
});