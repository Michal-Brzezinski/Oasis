<div class="watering-container">
    <div class="page-header">
        <h1>Sterowanie podlewaniem</h1>
    </div>

    <div class="watering-grid">
        <!-- Sekcja podlewania ręcznego -->
        <div class="manual-watering-section">
            <div class="section-card">
                <h2>Podlewanie ręczne</h2>

                <div class="form-group">
                    <label for="zoneSelect">Wybierz strefę</label>
                    <select id="zoneSelect" class="form-select">
                        <?php foreach ($zones as $zone): ?>
                            <option value="<?= $zone['id'] ?>">
                                <?= htmlspecialchars($zone['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="control-buttons">
                    <button class="btn-start" id="startWateringBtn" onclick="startManualWatering()">
                        <span class="icon">💧</span>
                        Start
                    </button>
                    <button class="btn-stop" id="stopWateringBtn" onclick="stopManualWatering()" disabled>
                        <span class="icon">⏹</span>
                        Stop
                    </button>
                </div>

                <div class="status-display" id="manualStatus">
                    <div class="status-label">Status</div>
                    <div class="status-value" id="statusText">
                        Obecnie podlewana: <span class="zone-name">Brak</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sekcja harmonogramów -->
        <div class="schedules-section">
            <div class="section-header">
                <h2>Harmonogramy podlewania</h2>
                <button class="btn-add" onclick="window.location.href='/dashboard/schedules/add'">
                    + Dodaj
                </button>
            </div>

            <div class="schedules-list">
                <?php if (empty($schedules)): ?>
                    <div class="empty-state">
                        <p>Brak harmonogramów</p>
                        <p class="text-muted">Dodaj pierwszy harmonogram aby zautomatyzować podlewanie</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($schedules as $schedule): ?>
                        <div class="schedule-card" data-schedule-id="<?= $schedule['id'] ?>">
                            <div class="schedule-header">
                                <h3><?= htmlspecialchars($schedule['name']) ?></h3>
                                <label class="toggle-switch">
                                    <input type="checkbox"
                                        class="schedule-toggle"
                                        data-schedule-id="<?= $schedule['id'] ?>"
                                        <?= $schedule['active'] ? 'checked' : '' ?>
                                        onchange="toggleSchedule(<?= $schedule['id'] ?>)">
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <div class="schedule-details">
                                <p class="schedule-info">
                                    <?= htmlspecialchars($schedule['frequency']) ?>,
                                    <?= htmlspecialchars($schedule['time']) ?>,
                                    <?= $schedule['duration'] ?> min,
                                    <?= htmlspecialchars($schedule['zone']) ?>
                                </p>
                            </div>

                            <div class="schedule-actions">
                                <button class="btn-icon" onclick="editSchedule(<?= $schedule['id'] ?>)" title="Edytuj">
                                    ✏️
                                </button>
                                <button class="btn-icon" onclick="deleteSchedule(<?= $schedule['id'] ?>)" title="Usuń">
                                    🗑️
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    const manualStatus = <?= json_encode($manualStatus) ?>;
</script>
<script src="/public/scripts/watering.js"></script>