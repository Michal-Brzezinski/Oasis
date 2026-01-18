<div class="module-header">
    <h2>Regiony</h2>
    <a class="btn-primary" href="/dashboard/regions/add">Dodaj region</a>
</div>

<div class="card-grid">
    <?php foreach ($regions as $region): ?>
        <div class="card">
            <h3><?= htmlspecialchars($region->getName()) ?></h3>

            <div class="card-actions">
                <a class="btn-secondary" href="/dashboard/regions/edit?id=<?= $region->getId() ?>">Edytuj</a>
                <a class="btn-danger"
                   onclick="return confirm('Usunąć region?')"
                   href="/dashboard/regions/delete?id=<?= $region->getId() ?>">Usuń</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>
