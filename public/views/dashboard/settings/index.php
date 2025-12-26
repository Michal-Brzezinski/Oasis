<div class="settings-container">
    <div class="page-header">
        <h1>Ustawienia</h1>
        <p class="subtitle">Zarządzaj swoim kontem i ustawieniami aplikacji.</p>
    </div>

    <div class="settings-grid">
        <!-- Sekcja Profil -->
        <div class="settings-section">
            <div class="section-header">
                <div class="icon">👤</div>
                <h2>Profil</h2>
            </div>

            <form method="POST" action="/dashboard/settings/update-profile" class="settings-form">
                <div class="form-group">
                    <label for="email">Adres e-mail</label>
                    <input type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        value="<?= htmlspecialchars($user['email']) ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="new_password">Nowe hasło</label>
                    <input type="password"
                        id="new_password"
                        name="new_password"
                        class="form-input"
                        placeholder="Zostaw puste aby nie zmieniać">
                </div>

                <button type="submit" class="btn-save">Zapisz zmiany</button>
            </form>
        </div>

        <!-- Sekcja Powiadomienia -->
        <div class="settings-section">
            <div class="section-header">
                <div class="icon">🔔</div>
                <h2>Powiadomienia</h2>
            </div>

            <form method="POST" action="/dashboard/settings/update-notifications" class="settings-form">
                <div class="toggle-group">
                    <div class="toggle-item">
                        <div class="toggle-label">
                            <span>Powiadomienia push</span>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox"
                                name="push_enabled"
                                <?= $notifications['push'] ? 'checked' : '' ?>>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="toggle-item">
                        <div class="toggle-label">
                            <span>Powiadomienia e-mail</span>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox"
                                name="email_enabled"
                                <?= $notifications['email'] ? 'checked' : '' ?>>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-save">Zapisz zmiany</button>
            </form>
        </div>

        <!-- Sekcja Zarządzanie czujnikami -->
        <div class="settings-section full-width">
            <div class="section-header">
                <div class="icon">📡</div>
                <h2>Zarządzanie czujnikami</h2>
            </div>

            <div class="sensors-list">
                <?php foreach ($sensors as $sensor): ?>
                    <div class="sensor-item">
                        <div class="sensor-info">
                            <div class="sensor-name"><?= htmlspecialchars($sensor['name']) ?></div>
                            <div class="sensor-status"><?= htmlspecialchars($sensor['status']) ?></div>
                        </div>

                        <?php if ($sensor['can_calibrate']): ?>
                            <button class="btn-calibrate"
                                onclick="calibrateSensor(<?= $sensor['id'] ?>)">
                                Kalibruj
                            </button>
                        <?php else: ?>
                            <span class="text-muted">Skalibrowany</span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <button class="btn-add-sensor" onclick="addNewSensor()">
                    + Dodaj nowy czujnik
                </button>
            </div>
        </div>

        <!-- Sekcja Podłączone urządzenia -->
        <div class="settings-section full-width">
            <div class="section-header">
                <div class="icon">💻</div>
                <h2>Podłączone urządzenia</h2>
            </div>

            <div class="devices-list">
                <?php foreach ($devices as $device): ?>
                    <div class="device-item">
                        <div class="device-info">
                            <div class="device-name"><?= htmlspecialchars($device['name']) ?></div>
                            <div class="device-status">
                                <span class="status-dot <?= $device['is_active'] ? 'green' : 'red' ?>"></span>
                                <?= $device['is_active'] ? 'Aktywna' : 'Nieaktywna' ?>
                            </div>
                        </div>

                        <label class="toggle-switch">
                            <input type="checkbox"
                                data-device-id="<?= $device['id'] ?>"
                                <?= $device['is_active'] ? 'checked' : '' ?>
                                onchange="toggleDevice(<?= $device['id'] ?>)">
                            <span class="slider"></span>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sekcja Język i Region -->
        <div class="settings-section">
            <div class="section-header">
                <div class="icon">🌍</div>
                <h2>Język i Region</h2>
            </div>

            <form method="POST" action="/dashboard/settings/change-language" class="settings-form">
                <div class="form-group">
                    <label for="language">Język</label>
                    <select id="language" name="language" class="form-select">
                        <option value="Polski" selected>Polski</option>
                        <option value="English">English</option>
                        <option value="Deutsch">Deutsch</option>
                    </select>
                </div>

                <button type="submit" class="btn-save">Zapisz zmiany</button>
            </form>
        </div>

        <!-- Sekcja Prywatność -->
        <div class="settings-section">
            <div class="section-header">
                <div class="icon">🔒</div>
                <h2>Prywatność</h2>
            </div>

            <div class="privacy-links">
                <a href="#" class="privacy-link">Polityka prywatności</a>
                <a href="#" class="privacy-link">Zarządzaj danymi</a>
            </div>
        </div>
    </div>
</div>

<script src="/public/scripts/settings.js"></script>