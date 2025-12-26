<?php
if (!isset($_SESSION)) {
    session_start();
}
// TODO: Sprawdź czy użytkownik jest zalogowany
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?> - Oasis</title>
    <link rel="icon" type="image/png" href="/public/img/oasis_favicon.png">
    <link rel="stylesheet" href="/public/styles/main.css">
    <link rel="stylesheet" href="/public/styles/dashboard.css">
</head>

<body>
    <div class="dashboard-wrapper">
        <!-- Top Navigation Bar -->
        <header class="top-nav">
            <div class="nav-left">
                <div class="logo-container">
                    <img src="/public/img/oasis-logo.png" alt="Oasis" class="logo-img">
                    <span class="logo-text">Oasis</span>
                </div>
            </div>

            <nav class="nav-center">
                <a href="/dashboard/panel" class="nav-link <?= ($activeTab ?? '') === 'panel' ? 'active' : '' ?>">
                    Strona Główna
                </a>
                <a href="/dashboard/schedules" class="nav-link <?= ($activeTab ?? '') === 'schedules' ? 'active' : '' ?>">
                    Podlewanie
                </a>
                <a href="/dashboard/cameras" class="nav-link <?= ($activeTab ?? '') === 'cameras' ? 'active' : '' ?>">
                    Kamery
                </a>
                <a href="/dashboard/settings" class="nav-link <?= ($activeTab ?? '') === 'settings' ? 'active' : '' ?>">
                    Ustawienia
                </a>
            </nav>

            <div class="nav-right">
                <a href="/logout" class="btn-logout">Logout</a>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="main-container">
            <!-- Flash Messages -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['message']) ?>
                    <?php unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Page Content -->
            <div class="page-content">
                <?php
                if (isset($contentView) && file_exists('public/views/' . $contentView . '.php')) {
                    include 'public/views/' . $contentView . '.php';
                } else {
                    echo '<p class="error-message">Widok nie został znaleziony.</p>';
                }
                ?>
            </div>
        </main>
    </div>

    <script src="/public/scripts/main.js"></script>
</body>

</html>