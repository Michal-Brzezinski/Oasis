<?php

require_once 'src/controllers/AppController.php';
require_once 'src/repository/UserRepository.php';

class UsersController extends AppController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function index(): void
    {
        $this->requireLogin();

        $users = $this->userRepository->getUsers();

        $this->render('dashboard/users/index', [
            'users' => $users,
            'pageTitle' => 'Użytkownicy systemu'
        ]);
    }

    public function editUser(): void
    {
        $this->requireLogin();

        // Tylko admin może edytować użytkowników
        if (($_SESSION['user_role'] ?? '') !== 'ADMIN') {
            http_response_code(403);
            echo "Brak dostępu";
            return;
        }

        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo "Missing user ID";
            return;
        }

        $userId = (int)$_GET['id'];
        $user = $this->userRepository->getUserById($userId);

        if (!$user) {
            $this->addFlash('error', 'Użytkownik nie istnieje.');
            $this->redirect('/dashboard/admin/users');
        }

        // GET → wyświetlenie formularza
        if ($this->isGet()) {
            $this->render('dashboard/users/edit', [
                'user' => $user,
                'pageTitle' => 'Edytuj użytkownika'
            ]);
            return;
        }

        // POST → zapis zmian
        if ($this->isPost()) {

            if (!$this->validateCsrfToken()) {
                $this->addFlash('error', 'Nieprawidłowy token bezpieczeństwa.');
                $this->redirect('/dashboard/admin/users');
            }

            // Pobranie danych z formularza
            $fullName = trim($_POST['full_name']);
            $nickname = trim($_POST['nickname']);
            $email = trim($_POST['email']);
            $role = $_POST['role'];
            $isActive = isset($_POST['is_active']);

            // Aktualizacja użytkownika
            $this->userRepository->updateProfile(
                $userId,
                $fullName,
                $nickname,
                $email,
                $role,
                $isActive
            );

            $this->addFlash('success', 'Dane użytkownika zostały zaktualizowane.');
            $this->redirect('/dashboard/admin/users');
        }
    }

    public function deleteUser(): void
    {
        $this->requireLogin();

        if ($_SESSION['user_role'] !== 'ADMIN') {
            http_response_code(403);
            $this->render('403', ['message' => 'Brak dostępu']);
        }

        $id = (int)($_GET['id'] ?? 0);

        if ($id > 0) {
            $this->userRepository->deleteUser($id);
            $this->addFlash('success', 'Użytkownik został usunięty.');
        }

        header("Location: /dashboard/admin/users");
        exit();
    }
}
