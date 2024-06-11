<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $price
 * @property mixed $m_product_category_id
 * @property mixed $category
 * @property mixed $is_available
 * @property mixed $description
 * @property mixed $details
 * @property mixed $type
 */
class ProductResource extends JsonResource
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
            'name' => $this->name,
            'price' => $this->price,
            'product_category_id' => $this->m_product_category_id,
            'product_category_name' => $this->category->name,
            'is_available' => (string) $this->is_available,
            'description' => $this->description,
            'photo_url' => !empty($this->photo) ? Storage::disk('public')->url($this->photo) : null,
            'details' => ProductDetailResource::collection($this->details),
        ];
    }
}
