<?php

/** @var Region[] $regions */ ?>
<?php /** @var Camera[] $cameras */ ?>

<div class="module-header">
    <h2>Kamery</h2>
    <a class="btn btn-primary" href="/dashboard/cameras/add">Dodaj kamerę</a>
</div>

<div class="filter-bar">
    <label>Region:</label>
    <select onchange="location.href='/dashboard/cameras?region=' + this.value">
        <?php foreach ($regions as $region): ?>
            <option value="<?= $region->getId() ?>"
                <?= $region->getId() == $selectedRegionId ? 'selected' : '' ?>>
                <?= htmlspecialchars($region->getName()) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="card-grid">
    <?php foreach ($cameras as $camera): ?>
        <div class="card">
            <h3><?= htmlspecialchars($camera->getName()) ?></h3>
            <p><strong>URL:</strong> <?= htmlspecialchars($camera->getStreamUrl()) ?></p>
            <p><strong>Status:</strong> <?= $camera->isActive() ? 'Aktywna' : 'Nieaktywna' ?></p>

            <div class="card-actions">
                <a class="btn btn-secondary" href="/dashboard/cameras/view?id=<?= $camera->getId() ?>">Podgląd</a>
                <a class="btn btn-secondary" href="/dashboard/cameras/edit?id=<?= $camera->getId() ?>">Edytuj</a>
                <a class="btn btn-danger"
                    onclick="return confirm('Usunąć kamerę?')"
                    href="/dashboard/cameras/delete?id=<?= $camera->getId() ?>">Usuń</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>