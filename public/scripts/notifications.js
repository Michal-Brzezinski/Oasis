// Notifications Handler
document.addEventListener('DOMContentLoaded', function() {
    const notificationBell = document.getElementById('notificationBell');
    const notificationsDropdown = document.getElementById('notificationsDropdown');
    const badge = document.querySelector('.notifications .badge');
    const markAllReadBtn = document.querySelector('.mark-all-read');
    let markedAsRead = false;

    if (!notificationBell || !notificationsDropdown) {
        console.warn('Notification elements not found');
        return;
    }

    // Toggle notifications dropdown
    notificationBell.addEventListener('click', function(e) {
        e.stopPropagation();
        
        // Toggle dropdown
        notificationsDropdown.classList.toggle('show');
        
        // Ukryj badge z animacją
        if (badge && notificationsDropdown.classList.contains('show')) {
            badge.style.opacity = '0';
            badge.style.transform = 'scale(0.5)';
            setTimeout(() => {
                badge.style.display = 'none';
            }, 200);
        }
        
        // Wyślij AJAX tylko raz przy pierwszym otwarciu
        if (!markedAsRead && notificationsDropdown.classList.contains('show')) {
            markNotificationsAsRead();
        }
    });

    // Close notifications when clicking outside
    document.addEventListener('click', function(e) {
        if (!notificationsDropdown.contains(e.target) && 
            !notificationBell.contains(e.target)) {
            notificationsDropdown.classList.remove('show');
        }
    });

    // Mark all as read button
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            markNotificationsAsRead(true);
        });
    }

    // Function to mark notifications as read
    function markNotificationsAsRead(showFeedback = false) {
        fetch('/dashboard/notifications/read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (response.ok) {
                markedAsRead = true;
                
                // Remove unread styling from all notifications
                const notificationItems = document.querySelectorAll('.notification-item.unread');
                notificationItems.forEach(item => {
                    item.classList.remove('unread');
                });
                
                // Hide badge
                if (badge) {
                    badge.style.opacity = '0';
                    badge.style.transform = 'scale(0.5)';
                    setTimeout(() => {
                        badge.style.display = 'none';
                    }, 200);
                }
                
                // Hide the "mark all as read" button
                if (markAllReadBtn) {
                    markAllReadBtn.style.opacity = '0';
                    setTimeout(() => {
                        markAllReadBtn.style.display = 'none';
                    }, 200);
                }
                
                if (showFeedback) {
                    console.log('All notifications marked as read');
                }
            }
        })
        .catch(error => {
            console.error('Error marking notifications as read:', error);
        });
    }
});