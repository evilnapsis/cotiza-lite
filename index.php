<?php
/**
 * Cotiza Lite 2 - Front Controller & Router Principal
 * Arquitectura LegoBox v5 MVC con Capa de Servicios
 */

define("ROOT", dirname(__FILE__));

$debug = true;
if ($debug) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL & ~E_DEPRECATED);
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/core/autoload.php';

session_start();

use App\Service\AuthService;
use App\Controller\AuthController;

$routes = require_once __DIR__ . '/core/app/routes.php';
$dispatcher = FastRoute\simpleDispatcher($routes);

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if (!empty($baseFolder) && strpos($uri, $baseFolder) === 0) {
    $uri = substr($uri, strlen($baseFolder));
}
if (empty($uri)) {
    $uri = '/';
}

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        ViewEngine::render('404.html.twig');
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo "<h1>405 Método No Permitido</h1>";
        break;

    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        // Middleware de autenticación: todas las rutas privadas requieren login
        if ($handler[0] !== AuthController::class && !AuthService::check()) {
            header('Location: ' . $baseFolder . '/login');
            exit;
        }

        // Validación de seguridad CSRF para formularios POST (excepto si se especifica cabecera ajax o api)
        if ($httpMethod === 'POST') {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            if (!Session::validateCsrf($token)) {
                http_response_code(403);
                echo "<html><head><title>403 - Token CSRF Inválido</title><meta charset='UTF-8'></head><body style='font-family:sans-serif; text-align:center; padding-top:10%; background:#f8f9fa;'><h1>403 - Token CSRF Inválido o Expirado</h1><p>Por favor regresa a la página anterior, recárgala e inténtalo de nuevo.</p></body></html>";
                exit;
            }
        }

        list($controllerClass, $method) = $handler;
        $controller = new $controllerClass();
        $controller->$method($vars);
        break;
}
?>
