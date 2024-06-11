<?php

namespace App\Http\Resources\Report;

use Illuminate\Http\Resources\Json\JsonResource;

class SalePromoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'date_transaction' => $this->date ?? null,
            'customer_name' => $this->customer->name ?? null,
            'voucher_name' => $this->voucher->promo->name ?? null,
            'discount_name' => $this->discount->promo->name ?? null,
        ];
    }

}
