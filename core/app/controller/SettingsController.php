<?php
namespace App\Controller;

use App\Service\SettingService;
use ViewEngine;
use Req;

class SettingsController {

    private SettingService $settingService;
    private string $baseFolder;

    public function __construct() {
        $this->settingService = new SettingService();
        $this->baseFolder = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    }

    public function index(): void {
        $settings = $this->settingService->getAll();
        ViewEngine::render('settings/index.html.twig', [
            'settings' => $settings
        ]);
    }

    public function update(): void {
        $this->settingService->updateAll(Req::post(), $_FILES);
        $_SESSION['updated'] = 'Ajustes de la empresa actualizados correctamente';
        header('Location: ' . $this->baseFolder . '/settings');
        exit;
    }
}
