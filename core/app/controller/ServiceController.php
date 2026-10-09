<?php
namespace App\Controller;

use App\Service\ServiceService;
use App\Service\CategoryService;
use ViewEngine;
use Req;

class ServiceController {

    private ServiceService $serviceService;
    private CategoryService $categoryService;
    private string $baseFolder;

    public function __construct() {
        $this->serviceService = new ServiceService();
        $this->categoryService = new CategoryService();
        $this->baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    }

    public function index(): void {
        $services = $this->serviceService->getAll();
        ViewEngine::render('services/index.html.twig', [
            'services' => $services
        ]);
    }

    public function new(): void {
        $categories = $this->categoryService->getAll();
        ViewEngine::render('services/new.html.twig', [
            'categories' => $categories
        ]);
    }

    public function create(): void {
        $errors = Req::validate([
            'name' => 'required',
            'amount' => 'required'
        ]);

        if (!empty($errors)) {
            $categories = $this->categoryService->getAll();
            ViewEngine::render('services/new.html.twig', [
                'errors' => $errors,
                'old' => Req::post(),
                'categories' => $categories
            ]);
            return;
        }

        $this->serviceService->create(Req::post());
        $_SESSION['success'] = 'Servicio registrado correctamente';
        header('Location: ' . $this->baseFolder . '/services');
        exit;
    }

    public function edit(array $vars): void {
        $id = (int)$vars['id'];
        $service = $this->serviceService->getById($id);
        if (!$service) {
            $_SESSION['error'] = 'Servicio no encontrado';
            header('Location: ' . $this->baseFolder . '/services');
            exit;
        }

        $categories = $this->categoryService->getAll();
        ViewEngine::render('services/edit.html.twig', [
            'service' => $service,
            'categories' => $categories
        ]);
    }

    public function update(array $vars): void {
        $id = (int)$vars['id'];
        $errors = Req::validate([
            'name' => 'required',
            'amount' => 'required'
        ]);

        if (!empty($errors)) {
            $service = $this->serviceService->getById($id);
            $categories = $this->categoryService->getAll();
            ViewEngine::render('services/edit.html.twig', [
                'service' => $service,
                'errors' => $errors,
                'old' => Req::post(),
                'categories' => $categories
            ]);
            return;
        }

        $this->serviceService->update($id, Req::post());
        $_SESSION['updated'] = 'Servicio actualizado correctamente';
        header('Location: ' . $this->baseFolder . '/services');
        exit;
    }

    public function delete(array $vars): void {
        $id = (int)$vars['id'];
        $this->serviceService->delete($id);
        $_SESSION['deleted'] = 'Servicio eliminado correctamente';
        header('Location: ' . $this->baseFolder . '/services');
        exit;
    }
}
