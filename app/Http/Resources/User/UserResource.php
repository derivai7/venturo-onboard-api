<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $email
 * @property mixed $phone_number
 * @property mixed $updated_security
 * @property mixed $user_roles_id
 */
class UserResource extends JsonResource
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
            'photo_url' => !empty($this->photo) ? Storage::disk('public')->url($this->photo) : null,
            'phone_number' => $this->phone_number,
            'updated_security' => $this->updated_security,
            'user_roles_id' => (string) $this->user_roles_id,
            'access' => isset($this->role->access) ? json_decode($this->role->access) : [],

        ];
    }
}
