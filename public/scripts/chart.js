document.addEventListener('DOMContentLoaded', () => {
    const chartCanvas = document.getElementById('sensorChart');
    if (!chartCanvas) return;

    const container = document.getElementById('sensorChartContainer');
    const sensorId = container?.dataset.sensorId;

    if (!sensorId) return;

    let chart = null;

    async function loadSensorData() {
        const response = await fetch(`/dashboard/panel/getSensorData?id=${sensorId}`);
        const data = await response.json();

        if (!data.readings) return;

        const labels = data.readings.map(r => r.created_at).reverse();
        const values = data.readings.map(r => r.value).reverse();

        if (!chart) {
            chart = new Chart(chartCanvas, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Wilgotność (%)',
                        data: values,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.3
                    }]
                }
            });
        } else {
            chart.data.labels = labels;
            chart.data.datasets[0].data = values;
            chart.update();
        }
    }

    // Pierwsze ładowanie
    loadSensorData();

    // Auto-odświeżanie co 5 sekund
    setInterval(loadSensorData, 5000);
});
