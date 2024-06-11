<?php

namespace App\Http\Resources\Report;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $details
 */
class SaleTransactionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'no_receipt' => $this->no_receipt ?? null,
            'customer_name' => $this->customer->name ?? null,
            'date_transaction' => $this->date ?? null,
            'voucher_name' => $this->voucher->promo->name ?? null,
            'discount_name' => $this->discount->promo->name ?? null,
            'total_payment' => $this->total_payment ?? null,
            'details' => $this->details->map(function ($detail) {
                return [
                    'menu' => $this->getMenuWithDescription($detail),
                    'total_item' => $detail->total_item,
                    'price' => $detail->price / $detail->total_item,
                    'total' => $detail->price,
                ];
            }),
        ];
    }

    private function getMenuWithDescription($detail)
    {
        $menu = $detail->product->name;
        if ($detail->detail) {
            $menu .= ' (' . $detail->detail->description . ')';
        }
        return $menu;
    }
}
