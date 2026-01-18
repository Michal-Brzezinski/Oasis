const toggle = document.getElementById('simulatorToggle');
let simulatorInterval = null;

// Start interval
function startSimulator() {
    if (simulatorInterval !== null) return;

    simulatorInterval = setInterval(() => {
        fetch('/api/simulate', { method: 'POST' });
    }, 5000); // symulacja co 5 sekund
}

// Stop interval
function stopSimulator() {
    if (simulatorInterval !== null) {
        clearInterval(simulatorInterval);
        simulatorInterval = null;
    }
}

// Auto-start po odświeżeniu
document.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('simulator_enabled') === '1') {
        toggle.checked = true;
        startSimulator();
    }
});

// Obsługa zmiany stanu
toggle.addEventListener('change', () => {
    if (toggle.checked) {
        localStorage.setItem('simulator_enabled', '1');
        startSimulator();
    } else {
        localStorage.setItem('simulator_enabled', '0');
        stopSimulator();
    }
});
