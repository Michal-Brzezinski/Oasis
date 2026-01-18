<?php

/** @var Region[] $regions */ ?>
<?php /** @var Sensor[] $sensors */ ?>

<h1>Panel - czujniki</h1>

<form method="GET" action="/dashboard/panel" class="filter-bar">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
    <label>Wybierz region:</label>
    <select name="region" onchange="this.form.submit()">
        <?php foreach ($regions as $region): ?>
            <option value="<?= $region->getId() ?>"
                <?= ($region->getId() == $selectedRegionId) ? 'selected' : '' ?>>
                <?= htmlspecialchars($region->getName()) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<h2>Czujniki</h2>

<?php if (empty($sensors)): ?>
    <p>Brak czujników w tym regionie.</p>
<?php else: ?>

    <ul class="sensor-list">
        <?php foreach ($sensors as $sensor): ?>
            <li>
                <button class="sensor-btn"
                    data-sensor-id="<?= $sensor->getId() ?>">
                    <?= htmlspecialchars($sensor->getName()) ?>
                    (<?= htmlspecialchars($sensor->getType()) ?>)
                </button>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Wykres czujnika</h2>

    <div id="sensorChartContainer" data-sensor-id="<?= $sensors[0]->getId() ?>">
        <canvas id="sensorChart"></canvas>
    </div>

<?php endif; ?>

<script src="/public/scripts/chart.js"></script>
<script src="/public/scripts/sensorButton.js"></script>