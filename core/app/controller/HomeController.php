<?php
namespace App\Controller;

use App\Service\CotizationService;
use App\Service\PersonService;
use App\Service\ServiceService;
use App\Service\UserService;
use ViewEngine;

class HomeController {

    private CotizationService $cotizationService;
    private PersonService $personService;
    private ServiceService $serviceService;
    private UserService $userService;

    public function __construct() {
        $this->cotizationService = new CotizationService();
        $this->personService = new PersonService();
        $this->serviceService = new ServiceService();
        $this->userService = new UserService();
    }

    public function index(): void {
        $stats = [
            'cotizations_count' => $this->cotizationService->count(),
            'clients_count' => $this->personService->count(),
            'services_count' => $this->serviceService->count(),
            'users_count' => $this->userService->count(),
        ];

        $latestCotizations = array_slice($this->cotizationService->getAll(), 0, 5);
        $chartData = $this->cotizationService->getSoldDailyStatsLast30Days();

        ViewEngine::render('home/index.html.twig', [
            'stats' => $stats,
            'latest' => $latestCotizations,
            'chart_data' => $chartData
        ]);
    }
}
