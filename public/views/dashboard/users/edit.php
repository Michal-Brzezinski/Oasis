<h1>Edytuj użytkownika</h1>

<form method="POST" class="form-card">
    <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">

    <label>Imię i nazwisko:</label>
    <input type="text" name="full_name"
        value="<?= htmlspecialchars($user->getFullName()) ?>" required>

    <label>Nickname:</label>
    <input type="text" name="nickname"
        value="<?= htmlspecialchars($user->getNickname()) ?>" required>

    <label>Email:</label>
    <input type="email" name="email"
        value="<?= htmlspecialchars($user->getEmail()) ?>" required>

    <label>Rola:</label>
    <select name="role" required>
        <option value="OWNER" <?= $user->getRole() === 'OWNER' ? 'selected' : '' ?>>OWNER</option>
        <option value="ADMIN" <?= $user->getRole() === 'ADMIN' ? 'selected' : '' ?>>ADMIN</option>
    </select>

    <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
</form>