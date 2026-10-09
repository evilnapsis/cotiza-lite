<?php
namespace App\Controller;

use App\Service\PersonService;
use App\Service\CityService;
use App\Service\CreditService;
use ViewEngine;
use Req;

class PersonController {

    private PersonService $personService;
    private CityService $cityService;
    private CreditService $creditService;
    private string $baseFolder;

    public function __construct() {
        $this->personService = new PersonService();
        $this->cityService = new CityService();
        $this->creditService = new CreditService();
        $this->baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    }

    public function index(): void {
        $clients = $this->personService->getAll();
        ViewEngine::render('persons/index.html.twig', [
            'clients' => $clients
        ]);
    }

    public function new(): void {
        $cities = $this->cityService->getAll();
        $credits = $this->creditService->getAll();

        ViewEngine::render('persons/new.html.twig', [
            'cities' => $cities,
            'credits' => $credits
        ]);
    }

    public function create(): void {
        $errors = Req::validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        if (!empty($errors)) {
            $cities = $this->cityService->getAll();
            $credits = $this->creditService->getAll();
            ViewEngine::render('persons/new.html.twig', [
                'errors' => $errors,
                'old' => Req::post(),
                'cities' => $cities,
                'credits' => $credits
            ]);
            return;
        }

        $this->personService->create(Req::post());
        $_SESSION['success'] = 'Cliente registrado exitosamente';
        header('Location: ' . $this->baseFolder . '/clients');
        exit;
    }

    public function edit(array $vars): void {
        $id = (int)$vars['id'];
        $client = $this->personService->getById($id);
        if (!$client) {
            $_SESSION['error'] = 'Cliente no encontrado';
            header('Location: ' . $this->baseFolder . '/clients');
            exit;
        }

        $cities = $this->cityService->getAll();
        $credits = $this->creditService->getAll();

        ViewEngine::render('persons/edit.html.twig', [
            'client' => $client,
            'cities' => $cities,
            'credits' => $credits
        ]);
    }

    public function update(array $vars): void {
        $id = (int)$vars['id'];
        $errors = Req::validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        if (!empty($errors)) {
            $client = $this->personService->getById($id);
            $cities = $this->cityService->getAll();
            $credits = $this->creditService->getAll();
            ViewEngine::render('persons/edit.html.twig', [
                'client' => $client,
                'errors' => $errors,
                'old' => Req::post(),
                'cities' => $cities,
                'credits' => $credits
            ]);
            return;
        }

        $this->personService->update($id, Req::post());
        $_SESSION['updated'] = 'Cliente actualizado correctamente';
        header('Location: ' . $this->baseFolder . '/clients');
        exit;
    }

    public function delete(array $vars): void {
        $id = (int)$vars['id'];
        $this->personService->delete($id);
        $_SESSION['deleted'] = 'Cliente eliminado correctamente';
        header('Location: ' . $this->baseFolder . '/clients');
        exit;
    }
}
