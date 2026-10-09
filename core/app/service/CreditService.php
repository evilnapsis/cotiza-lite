<?php
namespace App\Service;

use CreditData;

/**
 * Servicio para la gestión de Tipos de Crédito.
 */
class CreditService {

    public function getAll(): array {
        return CreditData::getAll();
    }

    public function getById(int $id): ?CreditData {
        return CreditData::getById($id);
    }

    public function create(array $data): void {
        $credit = new CreditData();
        $credit->addExtraFieldString("name", $data['name'] ?? '');
        $credit->add();
    }

    public function update(int $id, array $data): void {
        $credit = CreditData::getById($id);
        if (!$credit) return;

        $credit->addExtraFieldString("name", $data['name'] ?? '');
        $credit->update();
    }

    public function delete(int $id): void {
        $credit = CreditData::getById($id);
        if ($credit) {
            $credit->del();
        }
    }
}
