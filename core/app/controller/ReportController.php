<?php
namespace App\Controller;

use App\Service\ReportService;
use ViewEngine;
use Req;

class ReportController {

    private ReportService $reportService;

    public function __construct() {
        $this->reportService = new ReportService();
    }

    public function index(): void {
        $start = (string)Req::get('start_at', '');
        $finish = (string)Req::get('finish_at', '');

        $results = [];
        if (!empty($start) && !empty($finish)) {
            $results = $this->reportService->getCotizationsByRange($start, $finish);
        }

        ViewEngine::render('reports/index.html.twig', [
            'results' => $results,
            'start_at' => $start,
            'finish_at' => $finish
        ]);
    }

    public function excel(): void {
        $start = (string)Req::get('start', '');
        $finish = (string)Req::get('finish', '');

        if (empty($start) || empty($finish)) {
            die("Rango de fechas requerido para generar Excel");
        }

        $this->reportService->exportToExcel($start, $finish);
    }
}
