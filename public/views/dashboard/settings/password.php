<h2>Zmiana hasła</h2>

<form method="POST" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

    <label>Stare hasło:</label>
    <input type="password" name="old_password" required>

    <label>Nowe hasło:</label>
    <input type="password" name="new_password" required>

    <button class="btn btn-primary" type="submit">Zmień hasło</button>
</form>