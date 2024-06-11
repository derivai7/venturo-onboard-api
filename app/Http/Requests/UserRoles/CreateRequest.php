<?php
namespace App\Http\Requests\UserRoles;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public $validator;

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
