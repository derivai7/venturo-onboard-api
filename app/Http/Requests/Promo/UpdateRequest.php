<?php

namespace App\Http\Requests\Promo;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class UpdateRequest extends FormRequest
{
    use ConvertsBase64ToFiles;

    public $validator;

    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|max:150',
            'status' => 'sometimes|required|in:voucher,discount',
            'expired_in_day' => 'nullable|integer',
            'nominal_percentage' => 'nullable|numeric|required_if:status,discount',
            'nominal_rupiah' => 'nullable|numeric|required_if:status,voucher',
            'term_conditions' => 'sometimes|required',
            'photo' => 'nullable|file|image',
        ];
    }

    protected function base64FileKeys(): array
    {
        return [
            'photo' => 'updated-promo-photo.jpg',
        ];
    }
}
