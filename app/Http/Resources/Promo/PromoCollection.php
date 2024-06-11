<?php

namespace App\Http\Resources\Promo;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @method getUrlRange(int $int, $lastPage)
 * @method lastPage()
 * @method total()
 */
class PromoCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'list' => $this->collection,
            'meta' => [
                'links' => $this->getUrlRange(1, $this->lastPage()),
                'total' => $this->total()
            ]
        ];
    }
}
