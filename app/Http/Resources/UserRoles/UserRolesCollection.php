<?php

namespace App\Http\Resources\UserRoles;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @method getUrlRange(int $int, $lastPage)
 * @method total()
 * @method lastPage()
 */
class UserRolesCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return [
            'list' => $this->collection, // otomatis mengikuti format CustomerResource
            'meta' => [
                'links' => $this->getUrlRange(1, $this->lastPage()),
                'total' => $this->total()
            ]
        ];
    }
}
