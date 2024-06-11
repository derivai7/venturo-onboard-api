<?php

namespace App\Http\Requests\Customer;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class CreateRequest extends FormRequest
{
    use ConvertsBase64ToFiles;

    public $validator;

    /**
     * Tampilkan pesan error ketika validasi gagal
     *
     * @return void
     */
    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:100',
            'email' => 'nullable|email|max:50|unique:m_customer,email',
            'phone_number' => 'nullable|numeric|digits_between:10,25',
            'date_of_birth' => 'nullable|date',
            'photo' => 'nullable|file|image|max:1024',
            'is_verified' => 'nullable|boolean',
        ];
    }

    protected function base64FileKeys(): array
    {
        return [
            'photo' => 'photo-customer.jpg',
        ];
    }
}
