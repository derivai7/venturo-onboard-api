<?php
namespace App\Helpers\User;

use App\Helpers\Venturo;
use App\Models\RoleModel;
use Throwable;

class RoleHelper extends Venturo
{
    private $rolesModel;

    public function __construct()
    {
        $this->rolesModel = new RoleModel();
    }

    public function create(array $payload): array
    {
        try {
            $roles = $this->rolesModel->store($payload);

            return [
                'status' => true,
                'data' => $roles
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    public function delete(string $id): bool
    {
        try {
            $this->rolesModel->drop($id);

            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $roles = $this->rolesModel->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $roles
        ];
    }

    public function getById(string $id): array
    {
        $roles = $this->rolesModel->getById($id);
        if (empty($roles)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $roles
        ];
    }

    public function update(array $payload, string  $id): array
    {
        try {
            $this->rolesModel->edit($payload, $id);

            $roles = $this->getById($id);

            return [
                'status' => true,
                'data' => $roles['data']
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }
}
