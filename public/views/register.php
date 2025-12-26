<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Zarejestruj się w Oasis - zarządzaj swoim inteligentnym ogrodem">
    <title>Zarejestruj się do Oasis</title>
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
            <form action="/register" method="POST" class="login-form-container">
                <div class="form-header">
                    <h1 class="form-title">
                        Dołącz do Oasis
                        <img src="/public/img/oasis-logo.png" alt="Logo Oasis" class="title-logo">
                    </h1>
                    <p class="form-subtitle">Stwórz konto i zacznij zarządzać swoim inteligentnym ogrodem</p>
                </div>

                <?php if(isset($message)): ?>
                    <div class="message" role="alert" aria-live="polite">
                        <?= htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                
                <div class="form-fields">
                    <div class="form-row">
                        <div class="field field--half">
                            <label for="firstname">Imię</label>
                            <input 
                                id="firstname" 
                                name="firstname" 
                                type="text" 
                                placeholder="Wprowadź swoje imię" 
                                autocomplete="given-name"
                                required
                                aria-required="true"
                                value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>">
                        </div>
                        
                        <div class="field field--half">
                            <label for="lastname">Nazwisko</label>
                            <input 
                                id="lastname" 
                                name="lastname" 
                                type="text" 
                                placeholder="Wprowadź swoje nazwisko" 
                                autocomplete="family-name"
                                required
                                aria-required="true"
                                value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>">
                        </div>
                    </div>
                    
                    <div class="field">
                        <label for="email">Adres email</label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            placeholder="Wprowadź swój adres email" 
                            autocomplete="email"
                            required
                            aria-required="true"
                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                    
                    <div class="field">
                        <label for="password1">Hasło</label>
                        <div class="password-wrapper">
                            <input 
                                id="password1" 
                                name="password1" 
                                type="password" 
                                placeholder="Wprowadź hasło (min. 8 znaków)" 
                                autocomplete="new-password"
                                required
                                aria-required="true"
                                aria-describedby="password-requirements">
                            <button 
                                type="button" 
                                class="password-toggle" 
                                aria-label="Pokaż hasło"
                                onclick="togglePassword('password1')">
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
                        <small id="password-requirements" class="field-hint">Hasło musi mieć minimum 8 znaków</small>
                    </div>
                    
                    <div class="field">
                        <label for="password2">Powtórz hasło</label>
                        <div class="password-wrapper">
                            <input 
                                id="password2" 
                                name="password2" 
                                type="password" 
                                placeholder="Wprowadź hasło ponownie" 
                                autocomplete="new-password"
                                required
                                aria-required="true">
                            <button 
                                type="button" 
                                class="password-toggle" 
                                aria-label="Pokaż hasło"
                                onclick="togglePassword('password2')">
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

                <button type="submit" class="btn btn--primary">Zarejestruj się</button>
                
                <div class="separator-line">
                    <span>lub</span>
                </div>
                
                <button type="button" class="btn btn--google">
                    <img src="/public/img/google-logo.png" alt="" width="20" height="20">
                    Zarejestruj się przez Google
                </button>

                <div class="form-footer">
                    <a href="/login" class="register-link">Masz już konto? Zaloguj się</a>
                </div>
            </form>
        </div>
    </main>

    <script src="/public/scripts/togglePassword.js"></script>
</body>
</html>