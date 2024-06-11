<?php
namespace App\Helpers\Customer;

use App\Helpers\Venturo;
use App\Models\CustomerModel;
use Throwable;

/**
 * Helper untuk manajemen customer
 * Mengambil data, menambah, mengubah, & menghapus ke tabel m_customer
 */
class CustomerHelper extends Venturo
{
    const CUSTOMER_PHOTO_DIRECTORY = 'foto-customer';
    private $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    public function create(array $payload): array
    {
        try {
            $payload = $this->uploadGetPayload($payload);
            $customer = $this->customerModel->store($payload);

            return [
                'status' => true,
                'data' => $customer
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
            $this->customerModel->drop($id);
            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $customers = $this->customerModel->getAll($filter, $itemPerPage, $sort);
        return [
            'status' => true,
            'data' => $customers
        ];
    }

    public function getById(string $id): array
    {
        $customer = $this->customerModel->getById($id);
        if (!$customer) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $customer
        ];
    }

    public function update(array $payload, string $id): array
    {
        try {
            $payload = $this->uploadGetPayload($payload);
            $this->customerModel->edit($payload, $id);
            $customer = $this->getById($id);

            return [
                'status' => true,
                'data' => $customer['data']
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    private function uploadGetPayload(array $payload): array
    {
        if (!empty($payload['photo'])) {
            $fileName = $this->generateFileName($payload['photo'], 'CUSTOMER_' . date('Ymdhis'));
            $photo = $payload['photo']->storeAs(self::CUSTOMER_PHOTO_DIRECTORY, $fileName, 'public');
            $payload['photo'] = $photo;
        } else {
            unset($payload['photo']);
        }

        return $payload;
    }
}

