<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title><?= $pageTitle ?? 'Oasis Dashboard' ?></title>
    <link rel="stylesheet" href="/public/styles/dashboard.css">
    <link rel="icon" type="image/png" href="public/img/oasis_favicon.png">
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
                <a href="/dashboard/cameras">Kamery</a>
                <a href="/dashboard/schedules">Harmonogramy</a>
                <a href="/dashboard/watering">Podlewanie</a>
                <a href="/dashboard/settings">Ustawienia</a>
                <a href="/logout" class="logout">Wyloguj</a>
            </nav>
        </aside>

        <main class="content">
            <header class="topbar">
                <h1><?= $pageTitle ?? "Dashboard" ?></h1>
                <div class="user-info">
                    <span><?= $_SESSION['full_name'] ?? 'Użytkownik' ?></span>
                </div>
            </header>

            <section class="page-content">
                <?php include $contentView; ?>
            </section>
        </main>

    </div>

</body>

</html>