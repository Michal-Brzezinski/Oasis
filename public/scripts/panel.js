/**
 * Skrypt dla strony głównej dashboardu (panel)
 * Obsługuje wykresy czujników i aktualizacje na żywo
 */

// Zmienne globalne
let charts = {};
let updateInterval = null;

// Inicjalizacja po załadowaniu DOM
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    startAutoUpdate();
});

/**
 * Inicjalizuje wykresy dla wszystkich czujników
 */
function initializeCharts() {
    // Sprawdź czy mamy dane z PHP
    if (typeof sensorsData === 'undefined') {
        console.error('Brak danych czujników');
        return;
    }
    
    // Temperatura
    if (document.getElementById('temperatureChart')) {
        charts.temperature = createLineChart(
            'temperatureChart',
            sensorsData.temperature.chartData,
            'rgba(30, 136, 229, 0.8)',
            'rgba(30, 136, 229, 0.2)'
        );
    }
    
    // Wilgotność
    if (document.getElementById('humidityChart')) {
        charts.humidity = createLineChart(
            'humidityChart',
            sensorsData.humidity.chartData,
            'rgba(67, 160, 71, 0.8)',
            'rgba(67, 160, 71, 0.2)'
        );
    }
    
    // Ciśnienie
    if (document.getElementById('pressureChart')) {
        charts.pressure = createLineChart(
            'pressureChart',
            sensorsData.pressure.chartData,
            'rgba(21, 101, 192, 0.8)',
            'rgba(21, 101, 192, 0.2)'
        );
    }
}

/**
 * Tworzy wykres liniowy
 */
function createLineChart(canvasId, data, lineColor, fillColor) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return null;
    
    const ctx = canvas.getContext('2d');
    
    // Prosty wykres bez bibliotek - rysujemy SVG-like path
    drawSimpleLineChart(ctx, data, lineColor, fillColor);
    
    return { canvas, data, lineColor, fillColor };
}

/**
 * Rysuje prosty wykres liniowy na canvas
 */
function drawSimpleLineChart(ctx, data, lineColor, fillColor) {
    const canvas = ctx.canvas;
    const width = canvas.width;
    const height = canvas.height;
    const padding = 10;
    
    // Wyczyść canvas
    ctx.clearRect(0, 0, width, height);
    
    // Oblicz skalę
    const max = Math.max(...data);
    const min = Math.min(...data);
    const range = max - min || 1;
    
    const scaleX = (width - 2 * padding) / (data.length - 1);
    const scaleY = (height - 2 * padding) / range;
    
    // Rysuj wypełnienie
    ctx.beginPath();
    ctx.moveTo(padding, height - padding);
    
    data.forEach((value, index) => {
        const x = padding + index * scaleX;
        const y = height - padding - (value - min) * scaleY;
        
        if (index === 0) {
            ctx.lineTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
    });
    
    ctx.lineTo(width - padding, height - padding);
    ctx.closePath();
    ctx.fillStyle = fillColor;
    ctx.fill();
    
    // Rysuj linię
    ctx.beginPath();
    data.forEach((value, index) => {
        const x = padding + index * scaleX;
        const y = height - padding - (value - min) * scaleY;
        
        if (index === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
    });
    
    ctx.strokeStyle = lineColor;
    ctx.lineWidth = 2;
    ctx.stroke();
}

/**
 * Aktualizuje wykres nowymi danymi
 */
function updateChart(chartName, newData) {
    const chart = charts[chartName];
    if (!chart) return;
    
    chart.data = newData;
    const ctx = chart.canvas.getContext('2d');
    drawSimpleLineChart(ctx, newData, chart.lineColor, chart.fillColor);
}

/**
 * Rozpoczyna automatyczną aktualizację danych
 */
function startAutoUpdate() {
    // Aktualizuj co 30 sekund
    updateInterval = setInterval(() => {
        updateSensorData();
    }, 30000);
}

/**
 * Pobiera aktualne dane z czujników
 */
async function updateSensorData() {
    try {
        // Pobierz dane dla temperatury
        const tempData = await getJSON('/dashboard/panel/getSensorData?id=1');
        if (tempData && !tempData.error) {
            updateSensorValue('temperature', tempData.temperature, '°C');
        }
        
        // Pobierz dane dla wilgotności
        const humData = await getJSON('/dashboard/panel/getSensorData?id=2');
        if (humData && !humData.error) {
            updateSensorValue('humidity', humData.humidity, '%');
        }
        
        // Pobierz dane dla ciśnienia
        const pressData = await getJSON('/dashboard/panel/getSensorData?id=3');
        if (pressData && !pressData.error) {
            updateSensorValue('pressure', pressData.pressure, 'hPa');
        }
    } catch (error) {
        console.error('Błąd aktualizacji danych:', error);
    }
}

/**
 * Aktualizuje wartość czujnika na karcie
 */
function updateSensorValue(sensorType, value, unit) {
    const card = document.querySelector(`.sensor-card[data-sensor-type="${sensorType}"]`);
    if (!card) return;
    
    const valueElement = card.querySelector('.sensor-value');
    if (valueElement) {
        valueElement.innerHTML = `${value}<span class="unit">${unit}</span>`;
    }
}

/**
 * Przejdź do szczegółów czujnika
 */
function viewSensorDetails(sensorId, sensorType) {
    window.location.href = `/dashboard/panel/sensorDetails?id=${sensorId}&type=${sensorType}`;
}

// Zatrzymaj aktualizacje gdy użytkownik opuszcza stronę
window.addEventListener('beforeunload', function() {
    if (updateInterval) {
        clearInterval(updateInterval);
    }
});