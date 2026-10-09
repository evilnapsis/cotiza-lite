<?php
namespace App\Service;

use CategoryData;

/**
 * Servicio para la gestión de Categorías.
 */
class CategoryService {

    public function getAll(): array {
        return CategoryData::getAll();
    }

    public function getById(int $id): ?CategoryData {
        return CategoryData::getById($id);
    }

    public function create(array $data): void {
        $cat = new CategoryData();
        $cat->addExtraFieldString("name", $data['name'] ?? '');
        $cat->add();
    }

    public function update(int $id, array $data): void {
        $cat = CategoryData::getById($id);
        if (!$cat) return;

        $cat->addExtraFieldString("name", $data['name'] ?? '');
        $cat->update();
    }

    public function delete(int $id): void {
        $cat = CategoryData::getById($id);
        if ($cat) {
            $cat->del();
        }
    }
}
