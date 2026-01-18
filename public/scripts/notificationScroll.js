document.addEventListener('DOMContentLoaded', () => {
    const bell = document.querySelector('.notifications .bell');
    const dropdown = document.querySelector('.notifications-dropdown');
    const badge = document.querySelector('.notifications .badge');

    let markedAsRead = false;

    bell.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('show');

        // Ukryj badge
        if (badge) {
            badge.style.display = 'none';
        }

        // Wyślij AJAX tylko raz
        if (!markedAsRead) {
            fetch('/dashboard/notifications/read', {
                method: 'POST'
            });
            markedAsRead = true;
        }
    });

    document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target) && !bell.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
});
