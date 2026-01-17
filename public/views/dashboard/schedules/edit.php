<h2>Edytuj harmonogram</h2>

<form method="POST" class="form-card">
    <label>Nazwa:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($schedule->getName()) ?>" required>

    <label>Cron expression:</label>
    <input type="text" name="cron_expression" value="<?= htmlspecialchars($schedule->getCronExpression()) ?>" required>

    <label>Ilość wody (L):</label>
    <input type="number" step="0.1" name="volume_liters" value="<?= $schedule->getVolumeLiters() ?>" required>

    <label>Aktywny:</label>
    <input type="checkbox" name="is_enabled" <?= $schedule->isEnabled() ? 'checked' : '' ?>>
    <button type="submit" class="btn-primary">Zapisz</button>
</form>