<h2>Podgląd kamery: <?= htmlspecialchars($camera->getName()) ?></h2>

<div class="camera-info">
    <p><strong>URL:</strong> <?= htmlspecialchars($camera->getStreamUrl()) ?></p>
    <p><strong>Status:</strong> <?= $camera->isActive() ? 'Aktywna' : 'Nieaktywna' ?></p>
</div>

<div class="camera-view">
    <video width="640" height="360" controls autoplay>
        <source src="<?= htmlspecialchars($camera->getStreamUrl()) ?>" type="video/mp4">
        Podgląd wideo nie jest obsługiwany.
    </video>
</div>