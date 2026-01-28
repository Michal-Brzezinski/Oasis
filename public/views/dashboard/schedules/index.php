<?php

/** @var Region[] $regions */ ?>
<?php /** @var Schedule[] $schedules */ ?>

<div class="module-header">
    <h2>Harmonogramy podlewania</h2>
    <a class="btn btn-primary" href="/dashboard/schedules/add">Dodaj harmonogram</a>
</div>

<div class="filter-bar">
    <label>Region:</label>
    <select onchange="location.href='/dashboard/schedules?region=' + this.value">
        <?php foreach ($regions as $region): ?>
            <option value="<?= $region->getId() ?>"
                <?= $region->getId() == $selectedRegionId ? 'selected' : '' ?>>
                <?= htmlspecialchars($region->getName()) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="card-grid">
    <?php foreach ($schedules as $schedule): ?>
        <div class="card">
            <h3><?= htmlspecialchars($schedule->getName()) ?></h3>

            <p><strong>Cron:</strong> <?= htmlspecialchars($schedule->getCronExpression()) ?></p>
            <p><strong>Ilość wody:</strong> <?= $schedule->getVolumeLiters() ?> L</p>
            <p><strong>Status:</strong> <?= $schedule->isEnabled() ? 'Aktywny' : 'Wyłączony' ?></p>

            <div class="card-actions">
                <a class="btn btn-secondary" href="/dashboard/schedules/edit?id=<?= $schedule->getId() ?>">Edytuj</a>
                <a class="btn btn-danger"
                    onclick="return confirm('Usunąć harmonogram?')"
                    href="/dashboard/schedules/delete?id=<?= $schedule->getId() ?>">Usuń</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>