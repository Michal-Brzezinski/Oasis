<div class="camera-form-container">
    <div class="form-header">
        <button class="btn-back" onclick="window.location.href='/dashboard/cameras/manage'">
            ← Powrót
        </button>
        <h1>Dodaj kamerę</h1>
    </div>

    <form class="camera-form" method="POST" action="/dashboard/cameras/add">
        <div class="form-card">
            <div class="form-group">
                <label for="name">Nazwa kamery</label>
                <input type="text"
                    id="name"
                    name="name"
                    class="form-input"
                    placeholder="np. Kamera - Grządka #1"
                    required>
            </div>

            <div class="form-group">
                <label for="location">Lokalizacja</label>
                <input type="text"
                    id="location"
                    name="location"
                    class="form-input"
                    placeholder="np. Ogród przedni"
                    required>
            </div>

            <div class="form-group">
                <label for="stream_url">URL streamu</label>
                <input type="url"
                    id="stream_url"
                    name="stream_url"
                    class="form-input"
                    placeholder="rtsp://192.168.1.100:554/stream"
                    required>
                <small class="form-hint">Podaj adres RTSP lub HTTP streamu kamery</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="username">Nazwa użytkownika (opcjonalnie)</label>
                    <input type="text"
                        id="username"
                        name="username"
                        class="form-input">
                </div>

                <div class="form-group">
                    <label for="password">Hasło (opcjonalnie)</label>
                    <input type="password"
                        id="password"
                        name="password"
                        class="form-input">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Dodaj kamerę</button>
                <button type="button" class="btn-secondary" onclick="window.location.href='/dashboard/cameras/manage'">
                    Anuluj
                </button>
            </div>
        </div>
    </form>
</div>