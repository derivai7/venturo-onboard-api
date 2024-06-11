<?php
namespace App\Helpers\Promo;

use App\Helpers\Venturo;
use App\Models\PromoModel;
use Throwable;

class PromoHelper extends Venturo
{
    const PROMO_PHOTO_DIRECTORY = 'foto-promo';
    private $promoModel;

    public function __construct()
    {
        $this->promoModel = new PromoModel();
    }

    public function create(array $payload): array
    {
        try {
            $payload = $this->uploadGetPayload($payload);
            $promo = $this->promoModel->store($payload);

            return [
                'status' => true,
                'data' => $promo
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
            $this->promoModel->drop($id);

            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $promos = $this->promoModel->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $promos
        ];
    }

    public function getById(string $id): array
    {
        $promo = $this->promoModel->getById($id);
        if (empty($promo)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $promo
        ];
    }

    public function update(array $payload, string $id): array
    {
        try {
            $payload = $this->uploadGetPayload($payload);

            if (isset($payload['status'])) {
                if ($payload['status'] === 'voucher') {
                    $payload['nominal_percentage'] = null;
                } else if ($payload['status'] === 'discount') {
                    $payload['nominal_rupiah'] = null;
                }
            }

            $this->promoModel->edit($payload, $id);

            $promo = $this->getById($id);

            return [
                'status' => true,
                'data' => $promo['data']
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
            $fileName = $this->generateFileName($payload['photo'], 'PROMO_' . date('Ymdhis'));
            $photo = $payload['photo']->storeAs(self::PROMO_PHOTO_DIRECTORY, $fileName, 'public');
            $payload['photo'] = $photo;
        } else {
            unset($payload['photo']);
        }

        return $payload;
    }
}
