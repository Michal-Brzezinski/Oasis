<?php

require_once 'AppController.php';
require_once 'DashboardController.php';
require_once __DIR__ . "/../repository/UserRepository.php";

class SecurityController extends AppController
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login()
    {
        // Dekorator, który definiuje, jakie metody HTTP są dostępne
        if (!$this->allowMethods(['GET', 'POST'])) {
            http_response_code(405); // Method Not Allowed
            return $this->render('405', ['message' => 'Method not allowed']);
        }

        if ($this->isGet()) {
            return $this->render("login");
        }

        // Pobranie i sanityzacja danych z formularza
        $email = trim($_POST["email"] ?? '');
        $password = $_POST["password"] ?? '';

        // Walidacja pustych pól
        if (empty($email) || empty($password)) {
            return $this->render('login', ['message' => 'Wypełnij wszystkie pola']);
        }

        // Walidacja formatu email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->render('login', ['message' => 'Nieprawidłowy format adresu email']);
        }

        // Pobranie danych użytkownika z bazy danych
        $user = $this->userRepository->getUserByEmail($email);

        if (!$user) {
            return $this->render('login', ['message' => 'Nie znaleziono użytkownika o podanym adresie email']);
        }

        // Weryfikacja hasła
        if (!password_verify($password, $user['password'])) {
            return $this->render('login', ['message' => 'Nieprawidłowe hasło']);
        }

        // TODO: Stworzenie sesji użytkownika
        // session_start();
        // $_SESSION['user_id'] = $user['id'];
        // $_SESSION['user_email'] = $user['email'];

        // Przekierowanie do dashboardu
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/dashboard");
        exit();
    }

    public function register()
    {
        // Dekorator, który definiuje, jakie metody HTTP są dostępne
        if (!$this->allowMethods(['GET', 'POST'])) {
            http_response_code(405);
            return $this->render('405', ['message' => 'Method not allowed']);
        }

        if ($this->isGet()) {
            return $this->render("register");
        }

        // Pobranie i sanityzacja danych z formularza
        $email = trim($_POST["email"] ?? '');
        $password1 = $_POST["password1"] ?? '';
        $password2 = $_POST["password2"] ?? '';
        $firstname = trim($_POST["firstname"] ?? '');
        $lastname = trim($_POST["lastname"] ?? '');

        // Walidacja pustych pól
        if (empty($email) || empty($password1) || empty($password2) || empty($firstname) || empty($lastname)) {
            return $this->render('register', ['message' => 'Wypełnij wszystkie pola']);
        }

        // Walidacja formatu email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->render('register', ['message' => 'Nieprawidłowy format adresu email']);
        }

        // Walidacja długości imienia i nazwiska
        if (strlen($firstname) < 2 || strlen($firstname) > 50) {
            return $this->render('register', ['message' => 'Imię musi mieć od 2 do 50 znaków']);
        }

        if (strlen($lastname) < 2 || strlen($lastname) > 50) {
            return $this->render('register', ['message' => 'Nazwisko musi mieć od 2 do 50 znaków']);
        }

        // Walidacja długości hasła
        if (strlen($password1) < 8) {
            return $this->render('register', ['message' => 'Hasło musi mieć minimum 8 znaków']);
        }

        // Sprawdzenie czy hasła są identyczne
        if ($password1 !== $password2) {
            return $this->render('register', ['message' => 'Hasła muszą być identyczne']);
        }

        // Sprawdzenie czy użytkownik z tym emailem już istnieje
        if ($this->userRepository->getUserByEmail($email) !== false) {
            return $this->render('register', ['message' => 'Ten adres email jest już w użyciu. Spróbuj się zalogować.']);
        }

        // Dodatkowa walidacja bezpieczeństwa hasła (opcjonalna)
        if (!$this->isPasswordStrong($password1)) {
            return $this->render('register', [
                'message' => 'Hasło jest zbyt słabe. Użyj kombinacji małych i dużych liter, cyfr oraz znaków specjalnych.'
            ]);
        }

        // Hashowanie hasła
        $hashedPassword = password_hash($password1, PASSWORD_BCRYPT);

        // Próba utworzenia użytkownika w bazie danych
        try {
            $this->userRepository->createUser(
                $email,
                $hashedPassword,
                $firstname,
                $lastname
            );

            // Sukces - przekierowanie do strony logowania z komunikatem
            return $this->render("login", [
                "message" => "Konto zostało utworzone pomyślnie! Możesz się teraz zalogować."
            ]);
        } catch (Exception $e) {
            // Błąd podczas tworzenia konta
            return $this->render('register', [
                'message' => 'Wystąpił błąd podczas tworzenia konta. Spróbuj ponownie.'
            ]);
        }
    }

    /**
     * Sprawdza siłę hasła
     * Wymaga: minimum 8 znaków, małe i duże litery, cyfry, znaki specjalne
     * 
     * @param string $password
     * @return bool
     */
    private function isPasswordStrong($password)
    {
        // Minimum 8 znaków
        if (strlen($password) < 8) {
            return false;
        }

        // Sprawdzenie czy zawiera małe litery
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }

        // Sprawdzenie czy zawiera duże litery
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        // Sprawdzenie czy zawiera cyfry
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        // Sprawdzenie czy zawiera znaki specjalne
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            return false;
        }

        return true;
    }

    /**
     * Sanityzacja inputu użytkownika
     * 
     * @param string $data
     * @return string
     */
    private function sanitizeInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function logout()
    {
        // Zezwalamy tylko na metodę GET
        if (!$this->allowMethods(['GET'])) {
            http_response_code(405);
            return $this->render('405', ['message' => 'Method not allowed']);
        }

        // Start sesji jeśli jeszcze nie została rozpoczęta
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Usunięcie wszystkich danych sesji
        $_SESSION = [];

        // Zniszczenie ciasteczka sesji (jeśli istnieje)
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Zniszczenie sesji
        session_destroy();

        // Przekierowanie na stronę logowania
        $url = "http://$_SERVER[HTTP_HOST]";
        header("Location: {$url}/login");
        exit();
    }
}
