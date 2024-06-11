<?php

namespace App\Http\Resources\Sale;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property $id
 * @property $t_sales_id
 * @property $m_product_id
 * @property $m_product_detail_id
 * @property $total_item
 * @property $price
 * @property $discount_nominal
 * @property $note
 */
class SaleDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'sales_id' => $this->t_sales_id,
            'product_id' => $this->m_product_id,
            'product_detail_id' => $this->m_product_detail_id,
            'total_item' => $this->total_item,
            'price' => $this->price,
            'discount_nominal' => $this->discount_nominal,
            'note' => $this->note,
        ];
    }
}
