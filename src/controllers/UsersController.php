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

        $this->render('dashboard/users/index', [
            'pageTitle' => 'Użytkownicy systemu'
        ]);
    }
}
