<?php

/** @var Region[] $regions */ ?>
<?php /** @var Sensor[] $sensors */ ?>

<div class="module-header">
    <h2>Czujniki</h2>
    <a class="btn-primary" href="/dashboard/sensors/add">Dodaj czujnik</a>
</div>

<div class="filter-bar">
    <label>Region:</label>
    <select onchange="location.href='/dashboard/sensors?region=' + this.value">
        <?php foreach ($regions as $region): ?>
            <option value="<?= $region->getId() ?>"
                <?= $region->getId() == $selectedRegionId ? 'selected' : '' ?>>
                <?= htmlspecialchars($region->getName()) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="card-grid">
    <?php foreach ($sensors as $sensor): ?>
        <div class="card">
            <h3><?= htmlspecialchars($sensor->getName()) ?></h3>
            <p>Typ: <?= htmlspecialchars($sensor->getType()) ?></p>
            <p>Status: <?= $sensor->isActive() ? 'Aktywny' : 'Nieaktywny' ?></p>

            <div class="card-actions">
                <a class="btn-secondary" href="/dashboard/sensors/edit?id=<?= $sensor->getId() ?>">Edytuj</a>
                <a class="btn-danger"
                    onclick="return confirm('Usunąć czujnik?')"
                    href="/dashboard/sensors/delete?id=<?= $sensor->getId() ?>">Usuń</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>