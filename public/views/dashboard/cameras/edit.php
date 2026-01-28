<h2>Edytuj kamerę</h2>

<form method="POST" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
    <label>Nazwa:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($camera->getName()) ?>" required>

    <label>URL strumienia:</label>
    <input type="text" name="stream_url" value="<?= htmlspecialchars($camera->getStreamUrl()) ?>" required>

    <label>Aktywna:</label>
    <input type="checkbox" name="is_active" <?= $camera->isActive() ? 'checked' : '' ?>>

    <button class="btn btn-primary" type="submit">Zapisz</button>
</form>