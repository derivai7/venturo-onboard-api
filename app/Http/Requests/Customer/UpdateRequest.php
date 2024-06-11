<?php

namespace App\Http\Requests\Customer;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class UpdateRequest extends FormRequest
{
    use ConvertsBase64ToFiles;

    public $validator = null;

    /**
     * Tampilkan pesan error ketika validasi gagal
     *
     * @return void
     */
    public function failedValidation(Validator $validator)
    {
       $this->validator = $validator;
    }

    public function rules(): array
    {
        $customerId = $this->input('id');

        return [
            'id' => 'required',
            'name' => 'required|max:100',
            'email' => [
                'nullable',
                'email',
                Rule::unique('m_customer')->ignore($customerId),
            ],
            'phone_number' => 'nullable|numeric|digits_between:10,25',
            'date_of_birth' => 'nullable|date',
            'photo' => 'nullable|file|image|max:1024',
            'is_verified' => 'nullable|boolean',
        ];
    }

    /**
     * inisialisasi key "photo" dengan value base64 sebagai "FILE"
     *
     * @return array
     */
    protected function base64FileKeys(): array
    {
        return [
            'photo' => 'photo-customer.jpg',
        ];
    }
}
