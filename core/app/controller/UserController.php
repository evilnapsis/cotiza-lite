<?php
namespace App\Controller;

use App\Service\UserService;
use ViewEngine;
use Req;

class UserController {

    private UserService $userService;
    private string $baseFolder;

    public function __construct() {
        $this->userService = new UserService();
        $this->baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    }

    public function index(): void {
        $users = $this->userService->getAll();
        ViewEngine::render('users/index.html.twig', [
            'users' => $users
        ]);
    }

    public function new(): void {
        ViewEngine::render('users/new.html.twig');
    }

    public function create(): void {
        $errors = Req::validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:4'
        ]);

        if (!empty($errors)) {
            ViewEngine::render('users/new.html.twig', [
                'errors' => $errors,
                'old' => Req::post()
            ]);
            return;
        }

        $this->userService->create(Req::post());
        $_SESSION['success'] = 'Usuario creado exitosamente';
        header('Location: ' . $this->baseFolder . '/users');
        exit;
    }

    public function edit(array $vars): void {
        $id = (int)$vars['id'];
        $user = $this->userService->getById($id);
        if (!$user) {
            header('Location: ' . $this->baseFolder . '/users');
            exit;
        }
        ViewEngine::render('users/edit.html.twig', ['user' => $user]);
    }

    public function update(array $vars): void {
        $id = (int)$vars['id'];
        $errors = Req::validate([
            'name' => 'required',
            'email' => 'required|email'
        ]);

        if (!empty($errors)) {
            $user = $this->userService->getById($id);
            ViewEngine::render('users/edit.html.twig', [
                'user' => $user,
                'errors' => $errors,
                'old' => Req::post()
            ]);
            return;
        }

        $this->userService->update($id, Req::post());
        $_SESSION['updated'] = 'Usuario actualizado exitosamente';
        header('Location: ' . $this->baseFolder . '/users');
        exit;
    }

    public function delete(array $vars): void {
        $id = (int)$vars['id'];
        $currentUserId = (int)($_SESSION['user_id'] ?? 0);

        if ($this->userService->delete($id, $currentUserId)) {
            $_SESSION['deleted'] = 'Usuario eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'No puedes eliminar tu propio usuario activo';
        }

        header('Location: ' . $this->baseFolder . '/users');
        exit;
    }
}
