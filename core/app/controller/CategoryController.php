<?php
namespace App\Controller;

use App\Service\CategoryService;
use ViewEngine;
use Req;

class CategoryController {

    private CategoryService $categoryService;
    private string $baseFolder;

    public function __construct() {
        $this->categoryService = new CategoryService();
        $this->baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    }

    public function index(): void {
        $categories = $this->categoryService->getAll();
        ViewEngine::render('categories/index.html.twig', [
            'categories' => $categories
        ]);
    }

    public function new(): void {
        ViewEngine::render('categories/new.html.twig');
    }

    public function create(): void {
        $errors = Req::validate(['name' => 'required']);
        if (!empty($errors)) {
            ViewEngine::render('categories/new.html.twig', [
                'errors' => $errors,
                'old' => Req::post()
            ]);
            return;
        }

        $this->categoryService->create(Req::post());
        $_SESSION['success'] = 'Categoría agregada exitosamente';
        header('Location: ' . $this->baseFolder . '/categories');
        exit;
    }

    public function edit(array $vars): void {
        $id = (int)$vars['id'];
        $category = $this->categoryService->getById($id);
        if (!$category) {
            header('Location: ' . $this->baseFolder . '/categories');
            exit;
        }
        ViewEngine::render('categories/edit.html.twig', ['category' => $category]);
    }

    public function update(array $vars): void {
        $id = (int)$vars['id'];
        $errors = Req::validate(['name' => 'required']);
        if (!empty($errors)) {
            $category = $this->categoryService->getById($id);
            ViewEngine::render('categories/edit.html.twig', [
                'category' => $category,
                'errors' => $errors
            ]);
            return;
        }

        $this->categoryService->update($id, Req::post());
        $_SESSION['updated'] = 'Categoría actualizada exitosamente';
        header('Location: ' . $this->baseFolder . '/categories');
        exit;
    }

    public function delete(array $vars): void {
        $id = (int)$vars['id'];
        $this->categoryService->delete($id);
        $_SESSION['deleted'] = 'Categoría eliminada exitosamente';
        header('Location: ' . $this->baseFolder . '/categories');
        exit;
    }
}
