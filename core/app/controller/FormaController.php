<?php
namespace App\Controller;

use App\Service\FormaService;
use ViewEngine;
use Req;

class FormaController {

    private FormaService $formaService;
    private string $baseFolder;

    public function __construct() {
        $this->formaService = new FormaService();
        $this->baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    }

    public function index(): void {
        $formas = $this->formaService->getAll();
        ViewEngine::render('formas/index.html.twig', [
            'formas' => $formas
        ]);
    }

    public function new(): void {
        ViewEngine::render('formas/new.html.twig');
    }

    public function create(): void {
        $errors = Req::validate(['name' => 'required']);
        if (!empty($errors)) {
            ViewEngine::render('formas/new.html.twig', [
                'errors' => $errors,
                'old' => Req::post()
            ]);
            return;
        }

        $this->formaService->create(Req::post());
        $_SESSION['success'] = 'Forma de pago agregada exitosamente';
        header('Location: ' . $this->baseFolder . '/formas');
        exit;
    }

    public function edit(array $vars): void {
        $id = (int)$vars['id'];
        $forma = $this->formaService->getById($id);
        if (!$forma) {
            header('Location: ' . $this->baseFolder . '/formas');
            exit;
        }
        ViewEngine::render('formas/edit.html.twig', ['forma' => $forma]);
    }

    public function update(array $vars): void {
        $id = (int)$vars['id'];
        $errors = Req::validate(['name' => 'required']);
        if (!empty($errors)) {
            $forma = $this->formaService->getById($id);
            ViewEngine::render('formas/edit.html.twig', [
                'forma' => $forma,
                'errors' => $errors
            ]);
            return;
        }

        $this->formaService->update($id, Req::post());
        $_SESSION['updated'] = 'Forma de pago actualizada exitosamente';
        header('Location: ' . $this->baseFolder . '/formas');
        exit;
    }

    public function delete(array $vars): void {
        $id = (int)$vars['id'];
        $this->formaService->delete($id);
        $_SESSION['deleted'] = 'Forma de pago eliminada exitosamente';
        header('Location: ' . $this->baseFolder . '/formas');
        exit;
    }
}
