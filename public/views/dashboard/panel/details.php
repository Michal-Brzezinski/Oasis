<div class="sensor-details-container">
    <div class="details-header">
        <button class="btn-back" onclick="window.location.href='/dashboard/panel'">
            ← Powrót
        </button>
        <h1><?= htmlspecialchars($sensor['name']) ?></h1>
    </div>

    <div class="details-content">
        <div class="current-value-card">
            <h2>Aktualna wartość</h2>
            <div class="value-display">
                <span class="value" id="currentValue">--</span>
                <span class="unit"><?= $sensorType === 'temperature' ? '°C' : ($sensorType === 'humidity' ? '%' : 'hPa') ?></span>
            </div>
            <p class="last-update" id="lastUpdate">Ostatnia aktualizacja: --</p>
        </div>

        <div class="historical-chart-card">
            <h2>Historia odczytów (24h)</h2>
            <div class="chart-container">
                <canvas id="historicalChart"></canvas>
            </div>
        </div>

        <div class="statistics-card">
            <h2>Statystyki</h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-label">Minimum</span>
                    <span class="stat-value" id="minValue">--</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Średnia</span>
                    <span class="stat-value" id="avgValue">--</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Maximum</span>
                    <span class="stat-value" id="maxValue">--</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const sensorId = <?= $sensor['id'] ?>;
    const sensorType = '<?= htmlspecialchars($sensorType) ?>';
    const historicalData = <?= json_encode($historicalData) ?>;
</script>
<script src="/public/scripts/sensor-details.js"></script>