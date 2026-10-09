<?php
namespace App\Controller;

use App\Service\CotizationService;
use App\Service\PersonService;
use App\Service\ServiceService;
use App\Service\FormaService;
use App\Service\ReportService;
use StatusData;
use ViewEngine;
use Req;

class CotizationController {

    private CotizationService $cotizationService;
    private PersonService $personService;
    private ServiceService $serviceService;
    private FormaService $formaService;
    private ReportService $reportService;
    private string $baseFolder;

    public function __construct() {
        $this->cotizationService = new CotizationService();
        $this->personService = new PersonService();
        $this->serviceService = new ServiceService();
        $this->formaService = new FormaService();
        $this->reportService = new ReportService();
        $this->baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    }

    public function index(): void {
        $cotizations = $this->cotizationService->getAll();
        ViewEngine::render('cotizations/index.html.twig', [
            'cotizations' => $cotizations
        ]);
    }

    public function new(): void {
        $userId = (int)($_SESSION['user_id'] ?? 1);
        $newId = $this->cotizationService->create($userId);
        $_SESSION['success'] = 'Nueva cotización #' . $newId . ' creada';
        header('Location: ' . $this->baseFolder . '/cotization/' . $newId . '/edit');
        exit;
    }

    public function edit(array $vars): void {
        $id = (int)$vars['id'];
        $cotization = $this->cotizationService->getById($id);
        if (!$cotization) {
            $_SESSION['error'] = 'La cotización solicitada no existe';
            header('Location: ' . $this->baseFolder . '/cotizations');
            exit;
        }

        $clients = $this->personService->getAll();
        $statuses = StatusData::getAll();
        $services = $this->serviceService->getAll();
        $formas = $this->formaService->getAll();

        ViewEngine::render('cotizations/edit.html.twig', [
            'cotization' => $cotization,
            'clients' => $clients,
            'statuses' => $statuses,
            'services' => $services,
            'formas' => $formas
        ]);
    }

    public function update(array $vars): void {
        $id = (int)$vars['id'];
        $personId = !empty(Req::post('person_id')) ? (int)Req::post('person_id') : null;
        $statusId = (int)Req::post('status_id', 1);

        $this->cotizationService->updateClientAndStatus($id, $personId, $statusId);
        $_SESSION['updated'] = 'Datos principales de la cotización actualizados';
        header('Location: ' . $this->baseFolder . '/cotization/' . $id . '/edit');
        exit;
    }

    public function updateNotes(array $vars): void {
        $id = (int)$vars['id'];
        $formaId = !empty(Req::post('forma_id')) ? (int)Req::post('forma_id') : null;
        $notes = (string)Req::post('notes', '');

        $this->cotizationService->updateNotesAndPayment($id, $formaId, $notes);
        $_SESSION['updated'] = 'Forma de pago y notas actualizadas';
        header('Location: ' . $this->baseFolder . '/cotization/' . $id . '/edit');
        exit;
    }

    public function addItem(array $vars): void {
        $id = (int)$vars['id'];
        $serviceId = (int)Req::post('service_id');
        $quantity = (float)Req::post('q', 1);

        if ($serviceId > 0 && $quantity > 0) {
            $this->cotizationService->addItem($id, $serviceId, $quantity);
            $_SESSION['success'] = 'Ítem agregado a la cotización';
        } else {
            $_SESSION['error'] = 'Debes seleccionar un servicio y una cantidad válida';
        }

        header('Location: ' . $this->baseFolder . '/cotization/' . $id . '/edit');
        exit;
    }

    public function deleteItem(array $vars): void {
        $id = (int)$vars['id'];
        $itemId = (int)$vars['itemId'];

        $this->cotizationService->deleteItem($itemId);
        $_SESSION['deleted'] = 'Ítem removido de la cotización';
        header('Location: ' . $this->baseFolder . '/cotization/' . $id . '/edit');
        exit;
    }

    public function delete(array $vars): void {
        $id = (int)$vars['id'];
        $this->cotizationService->delete($id);
        $_SESSION['deleted'] = 'Cotización #' . $id . ' eliminada correctamente';
        header('Location: ' . $this->baseFolder . '/cotizations');
        exit;
    }

    public function pdf(array $vars): void {
        $id = (int)$vars['id'];
        $this->reportService->generatePdf($id);
    }
}
