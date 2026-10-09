<?php
namespace App\Service;

use PersonData;
use CityData;
use CreditData;

/**
 * Servicio para la gestión de Clientes (Personas).
 */
class PersonService {

    public function getAll(): array {
        $persons = PersonData::getAll();
        foreach ($persons as $p) {
            $this->enrich($p);
        }
        return $persons;
    }

    public function getById(int $id): ?PersonData {
        $person = PersonData::getById($id);
        if ($person) {
            $this->enrich($person);
        }
        return $person;
    }

    public function create(array $data): void {
        $p = new PersonData();
        $p->addExtraFieldString("code", $data['code'] ?? '');
        $p->addExtraFieldString("rfc", $data['rfc'] ?? '');
        $p->addExtraFieldString("name", $data['name'] ?? '');
        $p->addExtraFieldString("lastname", $data['lastname'] ?? '');
        $p->addExtraFieldString("email", $data['email'] ?? '');
        $p->addExtraFieldString("phone", $data['phone'] ?? '');
        $p->addExtraFieldString("address", $data['address'] ?? '');
        $p->addExtraFieldString("contact", $data['contact'] ?? '');
        $p->addExtraFieldString("comments", $data['comments'] ?? '');

        if (!empty($data['city_id'])) {
            $p->addExtraField("city_id", (string)(int)$data['city_id']);
        } else {
            $p->addExtraField("city_id", "NULL");
        }

        if (!empty($data['credit_id'])) {
            $p->addExtraField("credit_id", (string)(int)$data['credit_id']);
        } else {
            $p->addExtraField("credit_id", "NULL");
        }

        $p->add();
    }

    public function update(int $id, array $data): void {
        $p = PersonData::getById($id);
        if (!$p) return;

        $p->addExtraFieldString("code", $data['code'] ?? '');
        $p->addExtraFieldString("rfc", $data['rfc'] ?? '');
        $p->addExtraFieldString("name", $data['name'] ?? '');
        $p->addExtraFieldString("lastname", $data['lastname'] ?? '');
        $p->addExtraFieldString("email", $data['email'] ?? '');
        $p->addExtraFieldString("phone", $data['phone'] ?? '');
        $p->addExtraFieldString("address", $data['address'] ?? '');
        $p->addExtraFieldString("contact", $data['contact'] ?? '');
        $p->addExtraFieldString("comments", $data['comments'] ?? '');

        if (!empty($data['city_id'])) {
            $p->addExtraField("city_id", (string)(int)$data['city_id']);
        } else {
            $p->addExtraField("city_id", "NULL");
        }

        if (!empty($data['credit_id'])) {
            $p->addExtraField("credit_id", (string)(int)$data['credit_id']);
        } else {
            $p->addExtraField("credit_id", "NULL");
        }

        $p->update();
    }

    public function delete(int $id): void {
        $p = PersonData::getById($id);
        if ($p) {
            $p->del();
        }
    }

    public function count(): int {
        return count(PersonData::getAll());
    }

    private function enrich(PersonData $p): void {
        $city = !empty($p->city_id) ? CityData::getById($p->city_id) : null;
        $p->city = $city;
        $p->city_name = $city ? $city->name : 'Sin asignar';

        $credit = !empty($p->credit_id) ? CreditData::getById($p->credit_id) : null;
        $p->credit = $credit;
        $p->credit_name = $credit ? $credit->name : 'Sin asignar';
    }
}
