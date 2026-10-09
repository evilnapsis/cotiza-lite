<?php
namespace App\Controller;

use App\Service\CityService;
use ViewEngine;
use Req;

class CityController {

    private CityService $cityService;
    private string $baseFolder;

    public function __construct() {
        $this->cityService = new CityService();
        $this->baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    }

    public function index(): void {
        $cities = $this->cityService->getAll();
        ViewEngine::render('cities/index.html.twig', [
            'cities' => $cities
        ]);
    }

    public function new(): void {
        ViewEngine::render('cities/new.html.twig');
    }

    public function create(): void {
        $errors = Req::validate(['name' => 'required']);
        if (!empty($errors)) {
            ViewEngine::render('cities/new.html.twig', [
                'errors' => $errors,
                'old' => Req::post()
            ]);
            return;
        }

        $this->cityService->create(Req::post());
        $_SESSION['success'] = 'Ciudad agregada exitosamente';
        header('Location: ' . $this->baseFolder . '/cities');
        exit;
    }

    public function edit(array $vars): void {
        $id = (int)$vars['id'];
        $city = $this->cityService->getById($id);
        if (!$city) {
            header('Location: ' . $this->baseFolder . '/cities');
            exit;
        }
        ViewEngine::render('cities/edit.html.twig', ['city' => $city]);
    }

    public function update(array $vars): void {
        $id = (int)$vars['id'];
        $errors = Req::validate(['name' => 'required']);
        if (!empty($errors)) {
            $city = $this->cityService->getById($id);
            ViewEngine::render('cities/edit.html.twig', [
                'city' => $city,
                'errors' => $errors
            ]);
            return;
        }

        $this->cityService->update($id, Req::post());
        $_SESSION['updated'] = 'Ciudad actualizada exitosamente';
        header('Location: ' . $this->baseFolder . '/cities');
        exit;
    }

    public function delete(array $vars): void {
        $id = (int)$vars['id'];
        $this->cityService->delete($id);
        $_SESSION['deleted'] = 'Ciudad eliminada exitosamente';
        header('Location: ' . $this->baseFolder . '/cities');
        exit;
    }
}
