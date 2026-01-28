<h2>Dodaj kamerę</h2>

<form method="POST" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
    <label>Nazwa:</label>
    <input type="text" name="name" required>

    <label>URL strumienia (RTSP/HTTP):</label>
    <input type="text" name="stream_url" required>

    <label>Region:</label>
    <select name="region_id">
        <?php foreach ($regions as $region): ?>
            <option value="<?= $region->getId() ?>">
                <?= htmlspecialchars($region->getName()) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button class="btn btn-primary" type="submit">Dodaj</button>
</form>