<h2>Edytuj region</h2>

<form method="POST" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
    <label>Nazwa regionu:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($region->getName()) ?>" required>

    <button class="btn btn-primary" type="submit">Zapisz</button>
</form>