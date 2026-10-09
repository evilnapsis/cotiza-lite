<?php
namespace App\Controller;

use App\Service\AuthService;
use ViewEngine;
use Req;

class AuthController {

    private AuthService $authService;
    private string $baseFolder;

    public function __construct() {
        $this->authService = new AuthService();
        $this->baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    }

    public function showLogin(): void {
        if (AuthService::check()) {
            header('Location: ' . $this->baseFolder . '/');
            exit;
        }
        ViewEngine::render('auth/login.html.twig');
    }

    public function login(): void {
        $username = trim((string)Req::post('email', ''));
        $password = (string)Req::post('password', '');

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Ingresa tu usuario/correo y contraseña';
            header('Location: ' . $this->baseFolder . '/login');
            exit;
        }

        if ($this->authService->login($username, $password)) {
            $_SESSION['success'] = '¡Bienvenido al Sistema!';
            header('Location: ' . $this->baseFolder . '/');
            exit;
        }

        $_SESSION['error'] = 'Credenciales incorrectas o usuario inactivo';
        header('Location: ' . $this->baseFolder . '/login');
        exit;
    }

    public function logout(): void {
        $this->authService->logout();
        header('Location: ' . $this->baseFolder . '/login');
        exit;
    }
}
