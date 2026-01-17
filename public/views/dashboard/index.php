<h2>Witaj w Oasis!</h2>

<p>Wybierz moduł z menu po lewej stronie.</p>

<h3>Twoje regiony:</h3>
<ul>
    <?php foreach ($regions as $region): ?>
        <li><?= htmlspecialchars($region->getName()) ?></li>
    <?php endforeach; ?>
</ul>