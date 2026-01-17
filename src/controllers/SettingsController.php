<?php

require_once 'src/controllers/AppController.php';
require_once 'src/repository/UserRepository.php';

class SettingsController extends AppController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function index(): void
    {
        $this->requireLogin();

        $this->render('dashboard/settings/index', [
            'pageTitle' => 'Ustawienia'
        ]);
    }

    public function updateProfile(): void
    {
        $this->requireLogin();

        if ($this->isGet()) {
            $user = $this->userRepository->getUserById($this->getCurrentUserId());
            $this->render('dashboard/settings/profile', [
                'user' => $user,
                'pageTitle' => 'Profil użytkownika'
            ]);
            return;
        }

        if ($this->isPost()) {
            $nickname = trim($_POST['nickname']);
            $email = trim($_POST['email']);

            $this->userRepository->updateProfile($this->getCurrentUserId(), $nickname, $email);

            $this->redirect('/dashboard/settings');
        }
    }

    public function changePassword(): void
    {
        $this->requireLogin();

        if ($this->isGet()) {
            $this->render('dashboard/settings/password', [
                'pageTitle' => 'Zmiana hasła'
            ]);
            return;
        }

        if ($this->isPost()) {
            $old = $_POST['old_password'];
            $new = $_POST['new_password'];

            $this->userRepository->changePassword($this->getCurrentUserId(), $old, $new);

            $this->redirect('/dashboard/settings');
        }
    }

    public function updateNotifications(): void
    {
        $this->requireLogin();

        if ($this->isGet()) {
            $this->render('dashboard/settings/notifications', [
                'pageTitle' => 'Powiadomienia'
            ]);
            return;
        }

        if ($this->isPost()) {
            // TODO: zapisz ustawienia powiadomień
            $this->redirect('/dashboard/settings');
        }
    }

    public function calibrateSensor(): void
    {
        $this->requireLogin();

        $this->render('dashboard/settings/calibration', [
            'pageTitle' => 'Kalibracja czujników'
        ]);
    }

    public function addSensor(): void
    {
        $this->requireLogin();

        // TODO: logika dodawania czujnika
        $this->redirect('/dashboard/settings');
    }

    public function toggleDevice(): void
    {
        $this->requireLogin();

        // TODO: logika włączania/wyłączania urządzeń
        $this->redirect('/dashboard/settings');
    }

    public function changeLanguage(): void
    {
        $this->requireLogin();

        if ($this->isGet()) {
            $this->render('dashboard/settings/language', [
                'pageTitle' => 'Zmiana języka'
            ]);
            return;
        }

        if ($this->isPost()) {
            // TODO: zapisz język
            $this->redirect('/dashboard/settings');
        }
    }
}
