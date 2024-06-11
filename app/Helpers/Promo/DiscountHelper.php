<?php

namespace App\Helpers\Promo;

use App\Helpers\Venturo;
use App\Models\DiscountModel;
use Throwable;

class DiscountHelper extends Venturo
{
    private $discount;

    public function __construct()
    {
        $this->discount = new DiscountModel();
    }

    public function create(array $payload): array
    {
        try {
            $discount = $this->discount->store($payload);

            return [
                'status' => true,
                'data' => $discount
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
            $this->discount->drop($id);

            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    public function getTotalDiscountsByPromoIds(): array
    {
        try {
            $discounts = $this->discount->getTotalDiscountsByPromoIds();

            $result = $discounts->reduce(function ($discount, $item) {
                $discount[$item->m_promo_id] = $item->total;
                return $discount;
            }, []);

            return [
                'status' => true,
                'data' => $result
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }
}
