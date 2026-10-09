<?php
namespace App\Controller;

use App\Service\CreditService;
use ViewEngine;
use Req;

class CreditController {

    private CreditService $creditService;
    private string $baseFolder;

    public function __construct() {
        $this->creditService = new CreditService();
        $this->baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    }

    public function index(): void {
        $credits = $this->creditService->getAll();
        ViewEngine::render('credits/index.html.twig', [
            'credits' => $credits
        ]);
    }

    public function new(): void {
        ViewEngine::render('credits/new.html.twig');
    }

    public function create(): void {
        $errors = Req::validate(['name' => 'required']);
        if (!empty($errors)) {
            ViewEngine::render('credits/new.html.twig', [
                'errors' => $errors,
                'old' => Req::post()
            ]);
            return;
        }

        $this->creditService->create(Req::post());
        $_SESSION['success'] = 'Tipo de crédito agregado exitosamente';
        header('Location: ' . $this->baseFolder . '/credits');
        exit;
    }

    public function edit(array $vars): void {
        $id = (int)$vars['id'];
        $credit = $this->creditService->getById($id);
        if (!$credit) {
            header('Location: ' . $this->baseFolder . '/credits');
            exit;
        }
        ViewEngine::render('credits/edit.html.twig', ['credit' => $credit]);
    }

    public function update(array $vars): void {
        $id = (int)$vars['id'];
        $errors = Req::validate(['name' => 'required']);
        if (!empty($errors)) {
            $credit = $this->creditService->getById($id);
            ViewEngine::render('credits/edit.html.twig', [
                'credit' => $credit,
                'errors' => $errors
            ]);
            return;
        }

        $this->creditService->update($id, Req::post());
        $_SESSION['updated'] = 'Tipo de crédito actualizado exitosamente';
        header('Location: ' . $this->baseFolder . '/credits');
        exit;
    }

    public function delete(array $vars): void {
        $id = (int)$vars['id'];
        $this->creditService->delete($id);
        $_SESSION['deleted'] = 'Tipo de crédito eliminado exitosamente';
        header('Location: ' . $this->baseFolder . '/credits');
        exit;
    }
}
