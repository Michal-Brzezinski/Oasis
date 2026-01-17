<?php

/** @var Region[] $regions */ ?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Oasis – Dashboard</title>
    <link rel="stylesheet" href="/public/styles/dashboard.css">
</head>

<body>
    <header>
        <h1>Oasis – Dashboard</h1>
    </header>

    <main>
        <section>
            <h2>Twoje regiony</h2>
            <ul>
                <?php foreach ($regions as $region): ?>
                    <li>
                        <?= htmlspecialchars($region->getName(), ENT_QUOTES, 'UTF-8') ?>
                        <?php if ($region->getDescription()): ?>
                            – <?= htmlspecialchars($region->getDescription(), ENT_QUOTES, 'UTF-8') ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </main>
</body>

</html>