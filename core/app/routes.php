<?php
use App\Controller\HomeController;
use App\Controller\AuthController;
use App\Controller\CotizationController;
use App\Controller\PersonController;
use App\Controller\ServiceController;
use App\Controller\CategoryController;
use App\Controller\CityController;
use App\Controller\CreditController;
use App\Controller\FormaController;
use App\Controller\UserController;
use App\Controller\SettingsController;
use App\Controller\ReportController;

return function(FastRoute\RouteCollector $r) {
    // Rutas públicas y de autenticación
    $r->addRoute('GET', '/login', [AuthController::class, 'showLogin']);
    $r->addRoute('POST', '/login', [AuthController::class, 'login']);
    $r->addRoute('GET', '/logout', [AuthController::class, 'logout']);

    // Dashboard
    $r->addRoute('GET', '/', [HomeController::class, 'index']);
    $r->addRoute('GET', '/home', [HomeController::class, 'index']);

    // Cotizaciones
    $r->addRoute('GET', '/cotizations', [CotizationController::class, 'index']);
    $r->addRoute('GET', '/cotization/new', [CotizationController::class, 'new']);
    $r->addRoute('GET', '/cotization/{id:\d+}/edit', [CotizationController::class, 'edit']);
    $r->addRoute('POST', '/cotization/{id:\d+}/update', [CotizationController::class, 'update']);
    $r->addRoute('POST', '/cotization/{id:\d+}/updatenotes', [CotizationController::class, 'updateNotes']);
    $r->addRoute('POST', '/cotization/{id:\d+}/item/add', [CotizationController::class, 'addItem']);
    $r->addRoute('POST', '/cotization/{id:\d+}/item/{itemId:\d+}/delete', [CotizationController::class, 'deleteItem']);
    $r->addRoute('POST', '/cotization/{id:\d+}/delete', [CotizationController::class, 'delete']);
    $r->addRoute('GET', '/cotization/{id:\d+}/pdf', [CotizationController::class, 'pdf']);

    // Clientes
    $r->addRoute('GET', '/clients', [PersonController::class, 'index']);
    $r->addRoute('GET', '/client/new', [PersonController::class, 'new']);
    $r->addRoute('POST', '/client/create', [PersonController::class, 'create']);
    $r->addRoute('GET', '/client/{id:\d+}/edit', [PersonController::class, 'edit']);
    $r->addRoute('POST', '/client/{id:\d+}/update', [PersonController::class, 'update']);
    $r->addRoute('POST', '/client/{id:\d+}/delete', [PersonController::class, 'delete']);

    // Servicios
    $r->addRoute('GET', '/services', [ServiceController::class, 'index']);
    $r->addRoute('GET', '/service/new', [ServiceController::class, 'new']);
    $r->addRoute('POST', '/service/create', [ServiceController::class, 'create']);
    $r->addRoute('GET', '/service/{id:\d+}/edit', [ServiceController::class, 'edit']);
    $r->addRoute('POST', '/service/{id:\d+}/update', [ServiceController::class, 'update']);
    $r->addRoute('POST', '/service/{id:\d+}/delete', [ServiceController::class, 'delete']);

    // Categorías
    $r->addRoute('GET', '/categories', [CategoryController::class, 'index']);
    $r->addRoute('GET', '/category/new', [CategoryController::class, 'new']);
    $r->addRoute('POST', '/category/create', [CategoryController::class, 'create']);
    $r->addRoute('GET', '/category/{id:\d+}/edit', [CategoryController::class, 'edit']);
    $r->addRoute('POST', '/category/{id:\d+}/update', [CategoryController::class, 'update']);
    $r->addRoute('POST', '/category/{id:\d+}/delete', [CategoryController::class, 'delete']);

    // Ciudades
    $r->addRoute('GET', '/cities', [CityController::class, 'index']);
    $r->addRoute('GET', '/city/new', [CityController::class, 'new']);
    $r->addRoute('POST', '/city/create', [CityController::class, 'create']);
    $r->addRoute('GET', '/city/{id:\d+}/edit', [CityController::class, 'edit']);
    $r->addRoute('POST', '/city/{id:\d+}/update', [CityController::class, 'update']);
    $r->addRoute('POST', '/city/{id:\d+}/delete', [CityController::class, 'delete']);

    // Créditos
    $r->addRoute('GET', '/credits', [CreditController::class, 'index']);
    $r->addRoute('GET', '/credit/new', [CreditController::class, 'new']);
    $r->addRoute('POST', '/credit/create', [CreditController::class, 'create']);
    $r->addRoute('GET', '/credit/{id:\d+}/edit', [CreditController::class, 'edit']);
    $r->addRoute('POST', '/credit/{id:\d+}/update', [CreditController::class, 'update']);
    $r->addRoute('POST', '/credit/{id:\d+}/delete', [CreditController::class, 'delete']);

    // Formas de Pago
    $r->addRoute('GET', '/formas', [FormaController::class, 'index']);
    $r->addRoute('GET', '/forma/new', [FormaController::class, 'new']);
    $r->addRoute('POST', '/forma/create', [FormaController::class, 'create']);
    $r->addRoute('GET', '/forma/{id:\d+}/edit', [FormaController::class, 'edit']);
    $r->addRoute('POST', '/forma/{id:\d+}/update', [FormaController::class, 'update']);
    $r->addRoute('POST', '/forma/{id:\d+}/delete', [FormaController::class, 'delete']);

    // Usuarios
    $r->addRoute('GET', '/users', [UserController::class, 'index']);
    $r->addRoute('GET', '/user/new', [UserController::class, 'new']);
    $r->addRoute('POST', '/user/create', [UserController::class, 'create']);
    $r->addRoute('GET', '/user/{id:\d+}/edit', [UserController::class, 'edit']);
    $r->addRoute('POST', '/user/{id:\d+}/update', [UserController::class, 'update']);
    $r->addRoute('POST', '/user/{id:\d+}/delete', [UserController::class, 'delete']);

    // Ajustes
    $r->addRoute('GET', '/settings', [SettingsController::class, 'index']);
    $r->addRoute('POST', '/settings/update', [SettingsController::class, 'update']);

    // Reportes
    $r->addRoute('GET', '/reports', [ReportController::class, 'index']);
    $r->addRoute('GET', '/reports/excel', [ReportController::class, 'excel']);
};
