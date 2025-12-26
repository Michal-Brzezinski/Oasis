<div class="cameras-container">
    <div class="page-header">
        <h1>Podgląd na żywo</h1>
        <p class="subtitle">Monitoruj swój ogród w czasie rzeczywistym</p>
    </div>

    <!-- Główna kamera -->
    <?php if ($mainCamera): ?>
        <div class="main-camera-section">
            <div class="camera-viewer">
                <div class="camera-label">
                    <span class="status-dot <?= $mainCamera['status_color'] ?>"></span>
                    Kamera - <?= htmlspecialchars($mainCamera['name']) ?>
                </div>

                <div class="camera-stream">
                    <img src="<?= htmlspecialchars($mainCamera['stream_url']) ?>"
                        alt="<?= htmlspecialchars($mainCamera['name']) ?>"
                        class="stream-image"
                        id="mainCameraStream">
                    <div class="stream-overlay">
                        <button class="play-btn" onclick="toggleStream(<?= $mainCamera['id'] ?>)">▶</button>
                    </div>
                </div>

                <div class="camera-controls">
                    <button class="control-btn" onclick="zoomIn(<?= $mainCamera['id'] ?>)" title="Przybliż">
                        🔍+
                    </button>
                    <button class="control-btn" onclick="zoomOut(<?= $mainCamera['id'] ?>)" title="Oddal">
                        🔍-
                    </button>
                    <button class="control-btn" onclick="toggleRecording(<?= $mainCamera['id'] ?>)" title="Nagraj">
                        🔴
                    </button>
                    <button class="control-btn" onclick="takeSnapshot(<?= $mainCamera['id'] ?>)" title="Zrób zdjęcie">
                        📷
                    </button>
                    <button class="control-btn" onclick="toggleFullscreen()" title="Pełny ekran">
                        ⛶
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Lista innych kamer -->
    <div class="cameras-section">
        <h2>Inne kamery</h2>
        <div class="cameras-grid">
            <?php foreach ($cameras as $camera): ?>
                <?php if ($mainCamera && $camera['id'] === $mainCamera['id']) continue; ?>

                <div class="camera-thumbnail"
                    onclick="switchCamera(<?= $camera['id'] ?>)"
                    data-camera-id="<?= $camera['id'] ?>">
                    <span class="status-dot <?= $camera['status_color'] ?>"></span>

                    <img src="<?= htmlspecialchars($camera['stream_url']) ?>"
                        alt="<?= htmlspecialchars($camera['name']) ?>"
                        class="thumbnail-image">

                    <div class="camera-info">
                        <div class="camera-name"><?= htmlspecialchars($camera['name']) ?></div>
                        <div class="camera-location"><?= htmlspecialchars($camera['location']) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    const cameras = <?= json_encode($cameras) ?>;
    const mainCameraId = <?= $mainCamera ? $mainCamera['id'] : 'null' ?>;
</script>
<script src="/public/scripts/cameras.js"></script>