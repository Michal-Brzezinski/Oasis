<h1>Edytuj czujnik</h1>

<form method="POST" class="form-card">
    <label>Nazwa:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($sensor->getName()) ?>" required>

    <label>Typ:</label>
    <input type="text" name="type" value="<?= htmlspecialchars($sensor->getType()) ?>" required>

    <label>Aktywny:</label>
    <input type="checkbox" name="is_active" <?= $sensor->isActive() ? 'checked' : '' ?>>

    <button type="submit" class="btn-primary">Zapisz</button>
</form>