<?php

namespace App\Http\Resources\Sale;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $no_receipt
 * @property mixed $subtotal
 * @property mixed $total_payment
 * @property mixed $voucher_nominal
 * @property mixed $details
 * @property mixed $date
 * @property mixed $m_customer_id
 * @property mixed $m_voucher_id
 * @property mixed $m_discount_id
 */
class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'no_receipt' => $this->no_receipt,
            'subtotal' => $this->subtotal,
            'total_payment' => $this->total_payment,
            'customer_id' => $this->m_customer_id,
            'voucher_id' => $this->m_voucher_id,
            'discount_id' => $this->m_discount_id,
            'voucher_nominal' => $this->voucher_nominal,
            'date' => $this->date,
            'details' => SaleDetailResource::collection($this->details),
        ];
    }
}
