<?php

namespace App\Http\Resources\Promo;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @property mixed $id
 * @property mixed $customer
 * @property mixed $promo
 * @property mixed $start_time
 * @property mixed $end_time
 * @property mixed $total_voucher
 * @property mixed $nominal_rupiah
 * @property mixed $description
 * @property mixed $photo
 */
class VoucherResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer->id ?? null,
            'customer_name' => $this->customer->name ?? null,
            'promo_id' => $this->promo->id ?? null,
            'promo_name' => $this->promo->name ?? null,
            'expired_in_day' => $this->promo->expired_in_day ?? null,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'total_voucher' => $this->total_voucher,
            'nominal_rupiah' => $this->nominal_rupiah,
            'description' => $this->description,
            'photo' => $this->promo->photo,
            'photo_url' => !empty($this->promo->photo) ? Storage::disk('public')->url($this->promo->photo) : null,
        ];
    }
}
