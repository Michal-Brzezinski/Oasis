<?php

/** @var Region[] $regions */
/** @var WateringAction[] $actions */
?>

<div class="module-header">
    <h2>Podlewanie</h2>

    <?php if ($selectedRegionId !== null): ?>
        <form method="POST" action="/dashboard/watering/start" style="display:inline;">
            <input type="hidden" name="region" value="<?= $selectedRegionId ?>">
            <button class="btn btn-primary">Podlej teraz</button>
        </form>
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
            <p><strong>Ilość wody:</strong>
                <?= $action->getVolumeLiters() !== null ? $action->getVolumeLiters() . ' L' : '—' ?>
            </p>

            <div class="card-actions">
                <?php if ($action->getStatus() === 'RUNNING'): ?>
                    <a class="btn btn-danger"
                        href="/dashboard/watering/stop?id=<?= $action->getId() ?>">
                        Zatrzymaj
                    </a>
                <?php endif; ?>

                <a class="btn btn-danger"
                    onclick="return confirm('Usunąć tę akcję?')"
                    href="/dashboard/watering/delete?id=<?= $action->getId() ?>">
                    Usuń
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</div>