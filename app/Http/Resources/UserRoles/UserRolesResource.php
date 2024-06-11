<?php

namespace App\Http\Resources\UserRoles;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $email
 * @property mixed $access
 */
class UserRolesResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'access' => $this->access,
        ];
    }
}
