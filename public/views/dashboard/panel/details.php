<?php

/** @var Sensor $sensor */ ?>

<h1>Szczegóły czujnika</h1>

<p>Nazwa: <?= htmlspecialchars($sensor->getName()) ?></p>
<p>Typ: <?= htmlspecialchars($sensor->getType()) ?></p>
<p>Region ID: <?= $sensor->getRegionId() ?></p>

<div id="live-data">
    <p>Ładowanie danych...</p>
</div>

<script>
    function loadData() {
        fetch('/dashboard/panel/getSensorData?id=<?= $sensor->getId() ?>')
            .then(r => r.json())
            .then(data => {
                document.getElementById('live-data').innerHTML = `
                    <p>Wartość: ${data.value} ${data.unit}</p>
                    <p>Czas: ${data.timestamp}</p>
                `;
            });
    }

    loadData();
    setInterval(loadData, 5000);
</script>