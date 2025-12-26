<div class="camera-form-container">
    <div class="form-header">
        <button class="btn-back" onclick="window.location.href='/dashboard/cameras/manage'">
            ← Powrót
        </button>
        <h1>Edytuj kamerę</h1>
    </div>

    <form class="camera-form" method="POST" action="/dashboard/cameras/edit?id=<?= $camera['id'] ?>">
        <div class="form-card">
            <div class="form-group">
                <label for="name">Nazwa kamery</label>
                <input type="text"
                    id="name"
                    name="name"
                    class="form-input"
                    value="<?= htmlspecialchars($camera['name']) ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="location">Lokalizacja</label>
                <input type="text"
                    id="location"
                    name="location"
                    class="form-input"
                    value="<?= htmlspecialchars($camera['location']) ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="stream_url">URL streamu</label>
                <input type="url"
                    id="stream_url"
                    name="stream_url"
                    class="form-input"
                    value="<?= htmlspecialchars($camera['stream_url']) ?>"
                    required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="username">Nazwa użytkownika</label>
                    <input type="text"
                        id="username"
                        name="username"
                        class="form-input">
                </div>

                <div class="form-group">
                    <label for="password">Hasło</label>
                    <input type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="Zostaw puste aby nie zmieniać">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Zapisz zmiany</button>
                <button type="button" class="btn-secondary" onclick="window.location.href='/dashboard/cameras/manage'">
                    Anuluj
                </button>
            </div>
        </div>
    </form>
</div>