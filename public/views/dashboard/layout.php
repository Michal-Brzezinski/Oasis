<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title><?= $pageTitle ?? 'Oasis Dashboard' ?></title>
    <link rel="stylesheet" href="/public/styles/dashboard.css">
    <link rel="icon" type="image/png" href="public/img/oasis_favicon.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="dashboard-container">

        <aside class="sidebar">
            <div class="logo">
                <img src="/public/img/oasis_logo2.png" alt="Oasis Logo">
                <h2>Oasis</h2>
            </div>

            <nav>
                <a href="/dashboard/panel">Panel</a>
                <a href="/dashboard/sensors">Czujniki</a>
                <a href="/dashboard/schedules">Harmonogramy</a>
                <a href="/dashboard/cameras">Kamery</a>
                <a href="/dashboard/watering">Podlewanie</a>
                <a href="/dashboard/regions">Regiony</a>
                <a href="/dashboard/settings">Ustawienia</a>
                <a href="/logout" class="logout">Wyloguj</a>
            </nav>
        </aside>

        <main class="content">
            <?php
            // Pobranie powiadomień użytkownika
            $notifications = $this->getCurrentUserNotifications();
            $unreadCount = count($notifications);
            ?>

            <header class="topbar">
                <h1><?= $pageTitle ?? "Dashboard" ?></h1>

                <!-- Toggle symulatora -->
                <div class="simulator-toggle">
                    <span>Symulator</span>
                    <label class="switch">
                        <input type="checkbox" id="simulatorToggle">
                        <span class="slider"></span>
                    </label>
                </div>

                <!-- Informacje o użytkowniku + powiadomienia -->
                <div class="user-info">
                    <span><strong><?= $_SESSION['full_name'] ?? 'Użytkownik' ?></strong></span>

                    <div class="notifications">
                        <span class="bell">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                <path d="M12 2C9.24 2 7 4.24 7 7V10.29C7 11.07 6.73 11.83 6.24 12.41L4.29 14.71C3.11 16.11 4.01 18.25 5.82 18.25H18.18C19.99 18.25 20.89 16.11 19.71 14.71L17.76 12.41C17.27 11.83 17 11.07 17 10.29V7C17 4.24 14.76 2 12 2Z"
                                    stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M10 20C10.46 20.63 11.19 21 12 21C12.81 21 13.54 20.63 14 20"
                                    stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <?php if ($unreadCount > 0): ?>
                            <span class="badge"><?= $unreadCount ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <!-- Dropdown powiadomień -->
            <div class="notifications-dropdown <?= $unreadCount === 0 ? 'empty' : '' ?>">
                <?php if ($unreadCount === 0): ?>
                    <div class="notification-item empty">
                        <p>Brak powiadomień</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($notifications as $n): ?>
                        <div class="notification-item">
                            <p><?= htmlspecialchars($n->getMessage()) ?></p>
                            <small><?= $n->getCreatedAt() ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>



            <section class="page-content">
                <?php $flashes = $this->getFlashes() ?? []; ?>
                <?php if (!empty($flashes)): ?>
                    <div class="flash-container">
                        <?php foreach ($flashes as $flash): ?>
                            <div class="flash flash-<?= htmlspecialchars($flash['type']) ?>">
                                <?= htmlspecialchars($flash['message']) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php include $contentView; ?>
            </section>
        </main>

    </div>
    <script src="/public/scripts/toggle.js"></script>
    <script src="/public/scripts/notificationScroll.js"></script>

</body>

</html>