/**
 * Skrypt dla strony szczegółów czujnika
 * Wyświetla historyczne dane i statystyki
 */

let historicalChart = null;
let updateInterval = null;

document.addEventListener('DOMContentLoaded', function() {
    initializeHistoricalChart();
    updateCurrentValue();
    calculateStatistics();
    startAutoUpdate();
});

/**
 * Inicjalizuje wykres historyczny
 */
function initializeHistoricalChart() {
    if (typeof historicalData === 'undefined') {
        console.error('Brak danych historycznych');
        return;
    }
    
    const canvas = document.getElementById('historicalChart');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const values = historicalData.map(d => d.value);
    const labels = historicalData.map(d => d.time);
    
    drawHistoricalChart(ctx, values, labels);
}

/**
 * Rysuje wykres historyczny z etykietami
 */
function drawHistoricalChart(ctx, values, labels) {
    const canvas = ctx.canvas;
    const width = canvas.width;
    const height = canvas.height;
    const padding = 40;
    
    ctx.clearRect(0, 0, width, height);
    
    const max = Math.max(...values);
    const min = Math.min(...values);
    const range = max - min || 1;
    
    const scaleX = (width - 2 * padding) / (values.length - 1);
    const scaleY = (height - 2 * padding) / range;
    
    // Rysuj siatkę
    ctx.strokeStyle = '#e0e0e0';
    ctx.lineWidth = 1;
    
    for (let i = 0; i <= 5; i++) {
        const y = padding + (i * (height - 2 * padding) / 5);
        ctx.beginPath();
        ctx.moveTo(padding, y);
        ctx.lineTo(width - padding, y);
        ctx.stroke();
        
        // Etykiety wartości
        const value = max - (i * range / 5);
        ctx.fillStyle = '#666';
        ctx.font = '10px Arial';
        ctx.textAlign = 'right';
        ctx.fillText(value.toFixed(1), padding - 5, y + 3);
    }
    
    // Rysuj wykres
    ctx.beginPath();
    values.forEach((value, index) => {
        const x = padding + index * scaleX;
        const y = height - padding - (value - min) * scaleY;
        
        if (index === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
    });
    
    ctx.strokeStyle = '#1e88e5';
    ctx.lineWidth = 2;
    ctx.stroke();
    
    // Rysuj punkty
    ctx.fillStyle = '#1e88e5';
    values.forEach((value, index) => {
        const x = padding + index * scaleX;
        const y = height - padding - (value - min) * scaleY;
        
        ctx.beginPath();
        ctx.arc(x, y, 3, 0, 2 * Math.PI);
        ctx.fill();
    });
    
    // Etykiety czasu (co 4 godziny)
    ctx.fillStyle = '#666';
    ctx.font = '10px Arial';
    ctx.textAlign = 'center';
    
    labels.forEach((label, index) => {
        if (index % 4 === 0) {
            const x = padding + index * scaleX;
            ctx.fillText(label, x, height - padding + 15);
        }
    });
}

/**
 * Aktualizuje aktualną wartość
 */
async function updateCurrentValue() {
    if (typeof sensorId === 'undefined') return;
    
    try {
        const data = await getJSON(`/dashboard/sensors/data?id=${sensorId}`);
        
        if (data && !data.error) {
            document.getElementById('currentValue').textContent = data[sensorType] || '--';
            document.getElementById('lastUpdate').textContent = 
                `Ostatnia aktualizacja: ${formatDate(data.timestamp)}`;
        }
    } catch (error) {
        console.error('Błąd pobierania danych:', error);
    }
}

/**
 * Oblicza statystyki
 */
function calculateStatistics() {
    if (typeof historicalData === 'undefined' || historicalData.length === 0) return;
    
    const values = historicalData.map(d => d.value);
    
    const min = Math.min(...values);
    const max = Math.max(...values);
    const avg = values.reduce((a, b) => a + b, 0) / values.length;
    
    document.getElementById('minValue').textContent = min.toFixed(1);
    document.getElementById('avgValue').textContent = avg.toFixed(1);
    document.getElementById('maxValue').textContent = max.toFixed(1);
}

/**
 * Rozpoczyna automatyczną aktualizację
 */
function startAutoUpdate() {
    updateInterval = setInterval(() => {
        updateCurrentValue();
    }, 30000);
}

window.addEventListener('beforeunload', function() {
    if (updateInterval) {
        clearInterval(updateInterval);
    }
});