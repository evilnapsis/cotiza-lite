<?php
namespace App\Service;

use FormaData;

/**
 * Servicio para la gestión de Formas de Pago.
 */
class FormaService {

    public function getAll(): array {
        return FormaData::getAll();
    }

    public function getById(int $id): ?FormaData {
        return FormaData::getById($id);
    }

    public function create(array $data): void {
        $forma = new FormaData();
        $forma->addExtraFieldString("name", $data['name'] ?? '');
        $forma->add();
    }

    public function update(int $id, array $data): void {
        $forma = FormaData::getById($id);
        if (!$forma) return;

        $forma->addExtraFieldString("name", $data['name'] ?? '');
        $forma->update();
    }

    public function delete(int $id): void {
        $forma = FormaData::getById($id);
        if ($forma) {
            $forma->del();
        }
    }
}
