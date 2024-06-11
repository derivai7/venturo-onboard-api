<?php

namespace App\Http\Resources\Customer;

use App\Http\Resources\Promo\DiscountResource;
use App\Http\Resources\Promo\VoucherResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $email
 * @property mixed $phone_number
 * @property mixed $date_of_birth
 * @property mixed $is_verified
 * @property mixed $discount
 * @property mixed $voucher
 */
class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'date_of_birth' => $this->date_of_birth,
            'photo_url' => !empty($this->photo) ? Storage::disk('public')->url($this->photo) : null,
            'is_verified' => $this->is_verified,
            'discount' => DiscountResource::collection($this->discount),
            'voucher' => VoucherResource::collection($this->voucher),
        ];
    }
}
