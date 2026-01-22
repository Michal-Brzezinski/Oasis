<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Zaloguj się do Oasis - zarządzaj swoim inteligentnym ogrodem">
    <title>Zaloguj się do Oasis</title>
    <link rel="icon" type="image/png" href="public/img/oasis_favicon.png">
    <link rel="stylesheet" type="text/css" href="public/styles/main.css">
    <link rel="stylesheet" type="text/css" href="public/styles/auth.css">
</head>

<body>
    <main class="login-container">
        <div class="image" role="img" aria-label="Obraz przedstawiający ogród"></div>
        <div class="separator separator--white"></div>
        <div class="separator separator--blue"></div>

        <div class="form-wrapper">
            <form action="/login" method="POST" class="login-form-container">
                <input type="hidden" name="csrf_token" value="<?= $this->generateCsrfToken() ?>">
                <div class="form-header">
                    <h1 class="form-title">
                        Witaj w Oasis
                        <img src="/public/img/oasis-logo.png" alt="Logo Oasis" class="title-logo">
                    </h1>
                    <p class="form-subtitle">Zaloguj się, aby zarządzać swoim inteligentnym ogrodem</p>
                </div>

                <?php if (isset($message)): ?>
                    <div class="message" role="alert" aria-live="polite">
                        <?= htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <div class="form-fields">
                    <div class="field">
                        <label for="email">Adres email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            placeholder="Wprowadź swój adres email"
                            autocomplete="email"
                            required
                            aria-required="true">
                    </div>

                    <div class="field">
                        <label for="password">Hasło</label>
                        <div class="password-wrapper">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Wprowadź swoje hasło"
                                autocomplete="current-password"
                                required
                                aria-required="true">
                            <button
                                type="button"
                                class="password-toggle"
                                aria-label="Pokaż hasło"
                                onclick="togglePassword('password')">
                                <svg class="eye-icon eye-icon--closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                <svg class="eye-icon eye-icon--open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                    <line x1="1" y1="1" x2="23" y2="23"></line>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn--primary">Zaloguj się</button>

                <div class="separator-line">
                    <span>lub</span>
                </div>

                <a href="/register" class="btn btn--google">
                    <img src="/public/img/oasis-logo.png" alt="" width="20" height="20">
                    Utwórz nowe konto
                </a>

                <div class="form-footer">
                    <a href="/privacy-policy" class="register-link">Polityka prywatności</a>
                </div>
            </form>
        </div>
    </main>

    <script src="/public/scripts/togglePassword.js"></script>
</body>

</html>