<?php

require_once 'src/controllers/AppController.php';
require_once 'src/repository/RegionRepository.php';

class RegionsController extends AppController
{
    private RegionRepository $regionRepository;

    public function __construct()
    {
        $this->regionRepository = new RegionRepository();
    }

    public function index(): void
    {
        $this->requireLogin();

        $userId = $this->getCurrentUserId();
        $regions = $this->regionRepository->getRegionsByOwner($userId);

        $this->render('dashboard/regions/index', [
            'regions' => $regions,
            'pageTitle' => 'Regiony'
        ]);
    }

    public function add(): void
    {
        $this->requireLogin();

        if ($this->isGet()) {
            $this->render('dashboard/regions/add', [
                'pageTitle' => 'Dodaj region'
            ]);
            return;
        }

        if ($this->isPost()) {
            if (!$this->validateCsrfToken()) {
                $this->addFlash('error', 'Nieprawidłowy token bezpieczeństwa.');
                $this->redirect('/dashboard/regions');
            }

            $name = trim($_POST['name']);

            // Walidacja
            if ($name === '') {
                $this->addFlash('error', 'Nazwa regionu nie może być pusta.');
                $this->render('dashboard/regions/add', [
                    'pageTitle' => 'Dodaj region'
                ]);
                return; // koniec funkcji, nie idziemy dalej
            }

            try {
                $this->regionRepository->createRegion($this->getCurrentUserId(), $name);
            } catch (\Exception $e) {
                // Obsługa błędu z repozytorium
                $this->addFlash('error', 'Nie udało się dodać regionu: ' . $e->getMessage());
                $this->render('dashboard/regions/add', [
                    'pageTitle' => 'Dodaj region'
                ]);
                return;
            }

            // Sukces
            $this->addFlash('success', 'Region został dodany.');
            $this->redirect('/dashboard/regions');
        }
    }


    public function edit(): void
    {
        $this->requireLogin();

        $id = (int)$_GET['id'];
        $region = $this->regionRepository->getRegionById($id);

        if (!$region) {
            die("Region not found");
        }

        if ($this->isGet()) {
            $this->render('dashboard/regions/edit', [
                'region' => $region,
                'pageTitle' => 'Edytuj region'
            ]);
            return;
        }

        if ($this->isPost()) {
            if (!$this->validateCsrfToken()) {
                $this->addFlash('error', 'Nieprawidłowy token bezpieczeństwa.');
                $this->redirect('/dashboard/regions');
            }

            $name = trim($_POST['name']);
            $this->regionRepository->updateRegion($id, $name);

            $this->redirect('/dashboard/regions');
        }
    }

    public function delete(): void
    {
        $this->requireLogin();

        $id = (int)$_GET['id'];
        $this->regionRepository->deleteRegion($id);

        $this->addFlash('success', 'Region został usunięty.');
        $this->redirect('/dashboard/regions');
    }
}
