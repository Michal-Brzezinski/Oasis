<div class="cameras-manage-container">
    <div class="page-header">
        <h1>Zarządzanie kamerami</h1>
        <button class="btn-add" onclick="window.location.href='/dashboard/cameras/add'">
            + Dodaj kamerę
        </button>
    </div>

    <div class="cameras-list">
        <?php if (empty($cameras)): ?>
            <div class="empty-state">
                <p>Brak kamer</p>
                <p class="text-muted">Dodaj pierwszą kamerę aby rozpocząć monitoring</p>
            </div>
        <?php else: ?>
            <?php foreach ($cameras as $camera): ?>
                <div class="camera-manage-card">
                    <div class="camera-preview">
                        <img src="<?= htmlspecialchars($camera['stream_url']) ?>" alt="">
                    </div>

                    <div class="camera-details">
                        <h3><?= htmlspecialchars($camera['name']) ?></h3>
                        <p class="location"><?= htmlspecialchars($camera['location']) ?></p>
                        <p class="status">
                            Status:
                            <span class="status-badge <?= $camera['is_online'] ? 'online' : 'offline' ?>">
                                <?= $camera['is_online'] ? 'Online' : 'Offline' ?>
                            </span>
                        </p>
                    </div>

                    <div class="camera-actions">
                        <button class="btn-icon" onclick="window.location.href='/dashboard/cameras/edit?id=<?= $camera['id'] ?>'" title="Edytuj">
                            ✏️
                        </button>
                        <button class="btn-icon" onclick="deleteCamera(<?= $camera['id'] ?>)" title="Usuń">
                            🗑️
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script src="/public/scripts/cameras-manage.js"></script>