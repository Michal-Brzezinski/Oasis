<h2>Profil użytkownika</h2>

<form method="POST" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

    <label>Nickname:</label>
    <input type="text" name="nickname" value="<?= htmlspecialchars($user->getNickname()) ?>" required>

    <label>Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" required>

    <button class="btn-primary" type="submit">Zapisz zmiany</button>
</form>