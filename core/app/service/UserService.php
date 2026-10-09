<?php
namespace App\Service;

use UserData;

/**
 * Servicio para la gestión de Usuarios y Seguridad.
 */
class UserService {

    public function getAll(): array {
        return UserData::getAll();
    }

    public function getById(int $id): ?UserData {
        return UserData::getById($id);
    }

    public function create(array $data): void {
        $user = new UserData();
        $user->name = $data['name'] ?? '';
        $user->lastname = $data['lastname'] ?? '';
        $user->email = $data['email'] ?? '';
        $user->username = $data['username'] ?? ($data['email'] ?? '');
        $user->kind = !empty($data['kind']) ? (int)$data['kind'] : 1;
        $user->status = !empty($data['status']) ? (int)$data['status'] : 1;

        $rawPassword = !empty($data['password']) ? $data['password'] : '123456';
        $user->password = password_hash($rawPassword, PASSWORD_DEFAULT);

        $user->add();
    }

    public function update(int $id, array $data): void {
        $user = UserData::getById($id);
        if (!$user) return;

        $user->name = $data['name'] ?? $user->name;
        $user->lastname = $data['lastname'] ?? $user->lastname;
        $user->email = $data['email'] ?? $user->email;
        if (!empty($data['username'])) {
            $user->username = $data['username'];
        }
        $user->kind = !empty($data['kind']) ? (int)$data['kind'] : $user->kind;
        $user->status = isset($data['status']) ? (int)$data['status'] : $user->status;

        $user->update();

        if (!empty($data['password'])) {
            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $user->update_passwd();
        }
    }

    public function delete(int $id, int $currentUserId): bool {
        if ($id === $currentUserId) {
            return false; // No se puede auto-eliminar el usuario en sesión
        }

        $user = UserData::getById($id);
        if ($user) {
            $user->del();
            return true;
        }
        return false;
    }

    public function count(): int {
        return count(UserData::getAll());
    }
}
