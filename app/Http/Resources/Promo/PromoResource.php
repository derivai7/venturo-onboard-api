<?php

namespace App\Http\Resources\Promo;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $status
 * @property mixed $expired_in_day
 * @property mixed $nominal_percentage
 * @property mixed $nominal_rupiah
 * @property mixed $term_conditions
 * @property mixed $photo
 */
class PromoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'expired_in_day' => $this->expired_in_day,
            'nominal_percentage' => $this->nominal_percentage,
            'nominal_rupiah' => $this->nominal_rupiah,
            'term_conditions' => $this->term_conditions,
            'photo_url' => !empty($this->photo) ? Storage::disk('public')->url($this->photo) : null,
        ];
    }
}
