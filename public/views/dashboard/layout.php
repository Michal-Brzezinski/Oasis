<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Oasis Dashboard' ?></title>
    <link rel="stylesheet" href="/public/styles/dashboard.css">
    <link rel="icon" type="image/png" href="/public/img/oasis_favicon.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="dashboard-container">

        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobileOverlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="logo">
                <img src="/public/img/oasis_logo2.png" alt="Oasis Logo">
                <h2>Oasis</h2>
            </div>

            <nav>
                <a href="/dashboard/panel">
                    <span>Panel</span>
                </a>
                <a href="/dashboard/sensors">
                    <span>Czujniki</span>
                </a>
                <a href="/dashboard/schedules">
                    <span>Harmonogramy</span>
                </a>
                <a href="/dashboard/cameras">
                    <span>Kamery</span>
                </a>
                <a href="/dashboard/watering">
                    <span>Podlewanie</span>
                </a>
                <a href="/dashboard/regions">
                    <span>Regiony</span>
                </a>
                <a href="/dashboard/settings">
                    <span>Ustawienia</span>
                </a>

                <?php if (($_SESSION['user_role'] ?? '') === 'ADMIN'): ?>
                    <a href="/dashboard/admin/users"><span>Użytkownicy</span></a>
                <?php endif; ?>

                <a href="/logout" class="logout">
                    <span>Wyloguj</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="content">
            <?php
            // Pobranie powiadomień użytkownika
            $notifications = $this->getCurrentUserNotifications();
            $unreadCount = count($notifications);
            ?>

            <!-- Topbar -->
            <header class="topbar">

                <!-- Logo i tytuł -->
                <div class="topbar-left">
                    <!-- Hamburger Menu (integrated into topbar) -->
                    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <img src="/public/img/oasis_logo2.png" alt="Oasis Logo" class="topbar-logo">
                    <h1><?= $pageTitle ?? "Dashboard" ?></h1>
                </div>

                <!-- Prawy panel -->
                <div class="topbar-right">
                    <!-- Toggle symulatora -->
                    <div class="simulator-toggle">
                        <span>Symulator</span>
                        <label class="switch">
                            <input type="checkbox" id="simulatorToggle">
                            <span class="slider"></span>
                        </label>
                    </div>

                    <!-- Informacje o użytkowniku -->
                    <a class="user-info" href="/dashboard/settings/update-profile">
                        <div class="user-avatar">
                            <?= strtoupper(substr($_SESSION['full_name'] ?? 'U', 0, 1)) ?>
                        </div>
                        <span class="user-name"><?= $_SESSION['full_name'] ?? 'Użytkownik' ?></span>
                    </a>

                    <!-- Powiadomienia -->
                    <div class="notifications">
                        <button class="bell" id="notificationBell" aria-label="Notifications">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M12 2C9.24 2 7 4.24 7 7V10.29C7 11.07 6.73 11.83 6.24 12.41L4.29 14.71C3.11 16.11 4.01 18.25 5.82 18.25H18.18C19.99 18.25 20.89 16.11 19.71 14.71L17.76 12.41C17.27 11.83 17 11.07 17 10.29V7C17 4.24 14.76 2 12 2Z"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M10 20C10.46 20.63 11.19 21 12 21C12.81 21 13.54 20.63 14 20"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <?php if ($unreadCount > 0): ?>
                                <span class="badge"><?= $unreadCount ?></span>
                            <?php endif; ?>
                        </button>

                        <!-- Dropdown powiadomień -->
                        <div class="notifications-dropdown" id="notificationsDropdown">
                            <div class="notifications-header">
                                <h4>Powiadomienia</h4>
                                <?php if ($unreadCount > 0): ?>
                                    <span class="mark-all-read">Oznacz jako przeczytane</span>
                                <?php endif; ?>
                            </div>

                            <?php if ($unreadCount === 0): ?>
                                <div class="notification-item empty">
                                    <p>Brak powiadomień</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($notifications as $n): ?>
                                    <div class="notification-item unread">
                                        <p><?= htmlspecialchars($n->getMessage()) ?></p>
                                        <small><?= $n->getCreatedAt() ?></small>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <section class="page-content">
                <!-- Flash Messages -->
                <?php $flashes = $this->getFlashes() ?? []; ?>
                <?php if (!empty($flashes)): ?>
                    <div class="flash-container">
                        <?php foreach ($flashes as $flash): ?>
                            <div class="flash flash-<?= htmlspecialchars($flash['type']) ?>">
                                <?= htmlspecialchars($flash['message']) ?>
                                <button class="flash-close" onclick="this.parentElement.remove()">×</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php include $contentView; ?>
            </section>
        </main>

    </div>

    <!-- Scripts - Order matters! -->
    <script src="/public/scripts/toggle.js"></script>
    <script src="/public/scripts/mobileMenu.js"></script>
    <script src="/public/scripts/notifications.js"></script>
    <script src="/public/scripts/flashMessages.js"></script>

</body>

</html>