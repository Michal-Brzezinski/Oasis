// sensorButton.js

document.addEventListener('DOMContentLoaded', function () {
    // znajdź wszystkie przyciski sensorów
    const buttons = document.querySelectorAll('.sensor-btn');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const sensorId = btn.dataset.sensorId;
            // przekierowanie do szczegółów
            window.location.href = `/dashboard/panel/sensorDetails?id=${sensorId}`;
        });
    });
});