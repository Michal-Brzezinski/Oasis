<div class="schedule-form-container">
    <div class="form-header">
        <button class="btn-back" onclick="window.location.href='/dashboard/schedules'">
            ← Powrót
        </button>
        <h1>Nowy harmonogram</h1>
    </div>

    <form class="schedule-form" method="POST" action="/dashboard/schedules/add">
        <div class="form-card">
            <div class="form-group">
                <label for="name">Nazwa harmonogramu</label>
                <input type="text"
                    id="name"
                    name="name"
                    class="form-input"
                    placeholder="np. Poranne podlewanie trawnika"
                    required>
            </div>

            <div class="form-group">
                <label for="zone_id">Strefa</label>
                <select id="zone_id" name="zone_id" class="form-select" required>
                    <option value="">Wybierz strefę</option>
                    <?php foreach ($zones as $zone): ?>
                        <option value="<?= $zone['id'] ?>">
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
                        placeholder="15"
                        required>
                </div>
            </div>

            <div class="form-group">
                <label>Dni tygodnia</label>
                <div class="days-selector">
                    <label class="day-checkbox">
                        <input type="checkbox" name="days[]" value="mon">
                        <span>Pn</span>
                    </label>
                    <label class="day-checkbox">
                        <input type="checkbox" name="days[]" value="tue">
                        <span>Wt</span>
                    </label>
                    <label class="day-checkbox">
                        <input type="checkbox" name="days[]" value="wed">
                        <span>Śr</span>
                    </label>
                    <label class="day-checkbox">
                        <input type="checkbox" name="days[]" value="thu">
                        <span>Cz</span>
                    </label>
                    <label class="day-checkbox">
                        <input type="checkbox" name="days[]" value="fri">
                        <span>Pt</span>
                    </label>
                    <label class="day-checkbox">
                        <input type="checkbox" name="days[]" value="sat">
                        <span>So</span>
                    </label>
                    <label class="day-checkbox">
                        <input type="checkbox" name="days[]" value="sun">
                        <span>Nd</span>
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Dodaj harmonogram</button>
                <button type="button" class="btn-secondary" onclick="window.location.href='/dashboard/schedules'">
                    Anuluj
                </button>
            </div>
        </div>
    </form>
</div>