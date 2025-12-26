/**
 * Główny skrypt aplikacji Oasis
 * Zawiera funkcje pomocnicze używane w całej aplikacji
 */

// Globalne zmienne
const API_BASE_URL = '';

// Inicjalizacja aplikacji
document.addEventListener('DOMContentLoaded', function() {
    console.log('Oasis Dashboard initialized');
    
    // Automatyczne ukrywanie alertów po 5 sekundach
    hideAlertsAfterDelay();
    
    // Inicjalizuj tooltips (jeśli używasz)
    initTooltips();
});

/**
 * Ukrywa alerty po określonym czasie
 */
function hideAlertsAfterDelay(delay = 5000) {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, delay);
    });
}

/**
 * Inicjalizuje tooltips
 */
function initTooltips() {
    const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
    
    tooltipTriggers.forEach(trigger => {
        trigger.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.getAttribute('data-tooltip');
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';
            tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';
        });
        
        trigger.addEventListener('mouseleave', function() {
            const tooltip = document.querySelector('.tooltip');
            if (tooltip) {
                tooltip.remove();
            }
        });
    });
}

/**
 * Wysyła request POST z JSON
 */
async function postJSON(url, data) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        showNotification('Wystąpił błąd podczas komunikacji z serwerem', 'error');
        throw error;
    }
}

/**
 * Wysyła request GET
 */
async function getJSON(url) {
    try {
        const response = await fetch(url);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        showNotification('Wystąpił błąd podczas pobierania danych', 'error');
        throw error;
    }
}

/**
 * Pokazuje powiadomienie
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.textContent = message;
    
    const container = document.querySelector('.main-container');
    if (container) {
        container.insertBefore(notification, container.firstChild);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
}

/**
 * Potwierdź akcję
 */
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

/**
 * Format daty
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('pl-PL', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Format czasu względnego (np. "2 godziny temu")
 */
function timeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    
    const intervals = {
        rok: 31536000,
        miesiąc: 2592000,
        tydzień: 604800,
        dzień: 86400,
        godzina: 3600,
        minuta: 60
    };
    
    for (const [name, value] of Object.entries(intervals)) {
        const interval = Math.floor(seconds / value);
        if (interval >= 1) {
            return `${interval} ${name}${interval > 1 ? (name === 'miesiąc' ? 'e' : name === 'rok' ? 'i' : 'y') : ''} temu`;
        }
    }
    
    return 'przed chwilą';
}