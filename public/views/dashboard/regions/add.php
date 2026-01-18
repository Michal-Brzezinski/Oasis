<h2>Dodaj region</h2>

<form method="POST" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
    <label>Nazwa regionu:</label>
    <input type="text" name="name" required>

    <button class="btn-primary" type="submit">Dodaj</button>
</form>