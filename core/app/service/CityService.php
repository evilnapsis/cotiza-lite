<?php
namespace App\Service;

use CityData;

/**
 * Servicio para la gestión de Ciudades.
 */
class CityService {

    public function getAll(): array {
        return CityData::getAll();
    }

    public function getById(int $id): ?CityData {
        return CityData::getById($id);
    }

    public function create(array $data): void {
        $city = new CityData();
        $city->addExtraFieldString("name", $data['name'] ?? '');
        $city->add();
    }

    public function update(int $id, array $data): void {
        $city = CityData::getById($id);
        if (!$city) return;

        $city->addExtraFieldString("name", $data['name'] ?? '');
        $city->update();
    }

    public function delete(int $id): void {
        $city = CityData::getById($id);
        if ($city) {
            $city->del();
        }
    }
}
