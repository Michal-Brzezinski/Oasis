<div class="panel-container">
    <div class="page-header">
        <h1>Dashboard</h1>
    </div>

    <div class="sensors-grid">
        <!-- Karta Temperatura -->
        <div class="sensor-card temperature-card" data-sensor-type="temperature">
            <div class="sensor-header">
                <span class="sensor-label">Temperatura</span>
                <span class="sensor-icon">🌡️</span>
            </div>

            <div class="sensor-value">
                <?= $sensorsData['temperature']['value'] ?><span class="unit"><?= $sensorsData['temperature']['unit'] ?></span>
            </div>

            <div class="sensor-chart">
                <canvas id="temperatureChart"></canvas>
            </div>

            <button class="btn-details" onclick="viewSensorDetails(1, 'temperature')">
                Zobacz więcej
            </button>
        </div>

        <!-- Karta Wilgotność -->
        <div class="sensor-card humidity-card" data-sensor-type="humidity">
            <div class="sensor-header">
                <span class="sensor-label">Wilgotność</span>
                <span class="sensor-icon">💧</span>
            </div>

            <div class="sensor-value">
                <?= $sensorsData['humidity']['value'] ?><span class="unit"><?= $sensorsData['humidity']['unit'] ?></span>
            </div>

            <div class="sensor-chart">
                <canvas id="humidityChart"></canvas>
            </div>

            <button class="btn-details" onclick="viewSensorDetails(2, 'humidity')">
                Zobacz więcej
            </button>
        </div>

        <!-- Karta Ciśnienie -->
        <div class="sensor-card pressure-card" data-sensor-type="pressure">
            <div class="sensor-header">
                <span class="sensor-label">Ciśnienie</span>
                <span class="sensor-icon">🌪️</span>
            </div>

            <div class="sensor-value">
                <?= $sensorsData['pressure']['value'] ?><span class="unit"><?= $sensorsData['pressure']['unit'] ?></span>
            </div>

            <div class="sensor-chart">
                <canvas id="pressureChart"></canvas>
            </div>

            <button class="btn-details" onclick="viewSensorDetails(3, 'pressure')">
                Zobacz więcej
            </button>
        </div>
    </div>
</div>

<script>
    // Przekaż dane z PHP do JavaScript
    const sensorsData = <?= json_encode($sensorsData) ?>;
</script>
<script src="/public/scripts/panel.js"></script>