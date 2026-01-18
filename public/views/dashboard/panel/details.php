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

                if (!data.readings || data.readings.length === 0) {
                    document.getElementById('live-data').innerHTML = `
                        <p>Brak danych pomiarowych.</p>
                    `;
                    return;
                }

                const last = data.readings[0];

                document.getElementById('live-data').innerHTML = `
                    <p>Wartość: ${last.value} %</p>
                    <p>Czas: ${last.created_at}</p>
                `;
            });
    }

    loadData();
    setInterval(loadData, 5000);
</script>