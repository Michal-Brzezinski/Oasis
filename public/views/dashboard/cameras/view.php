<?php

/** @var Camera $camera */ ?>

<h2>Podgląd kamery: <?= htmlspecialchars($camera->getName()) ?></h2>

<div class="camera-info">
    <p><strong>URL:</strong> <?= htmlspecialchars($camera->getStreamUrl()) ?></p>
    <p><strong>Status:</strong> <?= $camera->isActive() ? 'Aktywna' : 'Nieaktywna' ?></p>
</div>

<?php
// Jeśli kamera ma snapshotUrl → używamy auto-odświeżanego obrazka
$snapshot = $camera->getSnapshotUrl();
$stream = $camera->getStreamUrl();
?>

<?php if ($snapshot): ?>

    <div class="camera-preview">
        <img id="cameraSnapshot"
            src="<?= htmlspecialchars($snapshot) ?>?t=<?= time() ?>"
            alt="Podgląd kamery">
    </div>

    <script>
        const img = document.getElementById('cameraSnapshot');

        setInterval(() => {
            const url = '<?= htmlspecialchars($snapshot) ?>';
            img.src = url + '?t=' + Date.now();
        }, 5000);
    </script>

<?php else: ?>

    <div class="camera-view">
        <video width="640" height="360" controls autoplay>
            <source src="<?= htmlspecialchars($stream) ?>" type="video/mp4">
            Podgląd wideo nie jest obsługiwany.
        </video>
    </div>

<?php endif; ?>