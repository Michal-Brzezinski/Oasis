<h2>Dodaj harmonogram</h2>

<form method="POST" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

    <label>Nazwa:</label>
    <input type="text" name="name" required>

    <label>Cron expression:</label>
    <input type="text" name="cron_expression" placeholder="np. */5 * * * *" required>

    <label>Ilość wody (L):</label>
    <input type="number" step="0.1" name="volume_liters" min="0" required>

    <label>Region:</label>
    <select name="region_id">
        <?php foreach ($regions as $region): ?>
            <option value="<?= $region->getId() ?>">
                <?= htmlspecialchars($region->getName()) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button class="btn-primary" type="submit">Dodaj</button>
</form>