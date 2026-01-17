<h2>Dodaj czujnik</h2>

<form method="POST" class="form-card">
    <label>Nazwa:</label>
    <input type="text" name="name" required>

    <label>Typ:</label>
    <input type="text" name="type" required>

    <label>Region:</label>
    <select name="region_id">
        <?php foreach ($regions as $region): ?>
            <option value="<?= $region->getId() ?>">
                <?= htmlspecialchars($region->getName()) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit" class="btn-primary">Dodaj</button>
</form>