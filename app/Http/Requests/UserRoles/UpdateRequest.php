<?php

namespace App\Http\Requests\UserRoles;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UpdateRequest extends FormRequest
{
    public $validator = null;

    public function failedValidation(Validator $validator)
    {
       $this->validator = $validator;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:100',
            'access' => 'required|array',
        ];
    }
}
