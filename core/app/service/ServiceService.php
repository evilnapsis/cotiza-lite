<?php
namespace App\Service;

use ServiceData;
use CategoryData;

/**
 * Servicio para la gestión del catálogo de Servicios / Conceptos.
 */
class ServiceService {

    public function getAll(): array {
        $services = ServiceData::getAll();
        foreach ($services as $s) {
            $this->enrich($s);
        }
        return $services;
    }

    public function getById(int $id): ?ServiceData {
        $service = ServiceData::getById($id);
        if ($service) {
            $this->enrich($service);
        }
        return $service;
    }

    public function create(array $data): void {
        $s = new ServiceData();
        $s->addExtraFieldString("code", $data['code'] ?? '');
        $s->addExtraFieldString("name", $data['name'] ?? '');
        $s->addExtraFieldString("kind", $data['kind'] ?? '');
        $s->addExtraFieldString("amount", (string)($data['amount'] ?? 0));

        if (!empty($data['category_id'])) {
            $s->addExtraField("category_id", (string)(int)$data['category_id']);
        } else {
            $s->addExtraField("category_id", "NULL");
        }

        $s->add();
    }

    public function update(int $id, array $data): void {
        $s = ServiceData::getById($id);
        if (!$s) return;

        $s->addExtraFieldString("code", $data['code'] ?? '');
        $s->addExtraFieldString("name", $data['name'] ?? '');
        $s->addExtraFieldString("kind", $data['kind'] ?? '');
        $s->addExtraFieldString("amount", (string)($data['amount'] ?? 0));

        if (!empty($data['category_id'])) {
            $s->addExtraField("category_id", (string)(int)$data['category_id']);
        } else {
            $s->addExtraField("category_id", "NULL");
        }

        $s->update();
    }

    public function delete(int $id): void {
        $s = ServiceData::getById($id);
        if ($s) {
            $s->del();
        }
    }

    public function count(): int {
        return count(ServiceData::getAll());
    }

    private function enrich(ServiceData $s): void {
        $cat = !empty($s->category_id) ? CategoryData::getById($s->category_id) : null;
        $s->category = $cat;
        $s->category_name = $cat ? $cat->name : 'Sin categoría';
    }
}
