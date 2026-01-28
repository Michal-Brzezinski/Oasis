<h2>Zmiana języka</h2>

<form method="POST" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
    <label>Wybierz język:</label>
    <select name="language">
        <option value="pl">Polski</option>
        <option value="en">English</option>
    </select>

    <button class="btn btn-primary" type="submit">Zapisz</button>
</form>