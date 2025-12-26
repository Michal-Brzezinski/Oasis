<div class="schedule-form-container">
    <div class="form-header">
        <button class="btn-back" onclick="window.location.href='/dashboard/schedules'">
            ← Powrót
        </button>
        <h1>Edytuj harmonogram</h1>
    </div>

    <form class="schedule-form" method="POST" action="/dashboard/schedules/edit?id=<?= $schedule['id'] ?>">
        <div class="form-card">
            <div class="form-group">
                <label for="name">Nazwa harmonogramu</label>
                <input type="text"
                    id="name"
                    name="name"
                    class="form-input"
                    value="<?= htmlspecialchars($schedule['name']) ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="zone_id">Strefa</label>
                <select id="zone_id" name="zone_id" class="form-select" required>
                    <?php foreach ($zones as $zone): ?>
                        <option value="<?= $zone['id'] ?>"
                            <?= $zone['id'] == $schedule['zone_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($zone['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="time">Godzina rozpoczęcia</label>
                    <input type="time"
                        id="time"
                        name="time"
                        class="form-input"
                        value="<?= htmlspecialchars($schedule['time']) ?>"
                        required>
                </div>

                <div class="form-group">
                    <label for="duration">Czas trwania (minuty)</label>
                    <input type="number"
                        id="duration"
                        name="duration"
                        class="form-input"
                        min="1"
                        max="120"
                        value="<?= $schedule['duration'] ?>"
                        required>
                </div>
            </div>

            <div class="form-group">
                <label>Dni tygodnia</label>
                <div class="days-selector">
                    <?php
                    $days = ['mon' => 'Pn', 'tue' => 'Wt', 'wed' => 'Śr', 'thu' => 'Cz', 'fri' => 'Pt', 'sat' => 'So', 'sun' => 'Nd'];
                    foreach ($days as $value => $label):
                        $checked = in_array($value, $schedule['days']) ? 'checked' : '';
                    ?>
                        <label class="day-checkbox">
                            <input type="checkbox" name="days[]" value="<?= $value ?>" <?= $checked ?>>
                            <span><?= $label ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Zapisz zmiany</button>
                <button type="button" class="btn-secondary" onclick="window.location.href='/dashboard/schedules'">
                    Anuluj
                </button>
            </div>
        </div>
    </form>
</div>