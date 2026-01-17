<?php

/** @var Region[] $regions */ ?>
<?php /** @var WateringAction[] $actions */ ?>

<div class="module-header">
    <h2>Podlewanie</h2>

    <?php if ($selectedRegionId !== null): ?>
        <a class="btn-primary" href="/dashboard/watering/start?region=<?= $selectedRegionId ?>">
            Podlej teraz
        </a>
    <?php endif; ?>
</div>

<div class="filter-bar">
    <label>Region:</label>
    <select onchange="location.href='/dashboard/watering?region=' + this.value">
        <?php foreach ($regions as $region): ?>
            <option value="<?= $region->getId() ?>"
                <?= $region->getId() == $selectedRegionId ? 'selected' : '' ?>>
                <?= htmlspecialchars($region->getName()) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="card-grid">
    <?php foreach ($actions as $action): ?>
        <div class="card">
            <h3>Akcja #<?= $action->getId() ?></h3>

            <p><strong>Start:</strong> <?= $action->getStartedAt() ?></p>
            <p><strong>Koniec:</strong> <?= $action->getStoppedAt() ?? '—' ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($action->getStatus()) ?></p>
            <p><strong>Ilość wody:</strong> <?= $action->getVolumeLiters() ? $action->getVolumeLiters() . ' L' : '—' ?></p>

            <?php if ($action->getStatus() === 'RUNNING'): ?>
                <div class="card-actions">
                    <a class="btn-danger"
                        href="/dashboard/watering/fail?id=<?= $action->getId() ?>">
                        Zatrzymaj
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>