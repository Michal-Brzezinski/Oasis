<?php

/** @var Region[] $regions */ ?>
<?php /** @var Sensor[] $sensors */ ?>

<h1>Panel – czujniki</h1>

<form method="GET" action="/dashboard/panel" class="filter-bar">
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

<ul>
    <?php foreach ($sensors as $sensor): ?>
        <li>
            <a href="/dashboard/panel/sensorDetails?id=<?= $sensor->getId() ?>">
                <?= htmlspecialchars($sensor->getName()) ?> (<?= $sensor->getType() ?>)
            </a>
        </li>
    <?php endforeach; ?>
</ul>