<?php

namespace App\Http\Requests\Sale;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    public $validator;

    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:m_customer,id',
            'voucher_id' => 'nullable',
            'voucher_nominal' => 'nullable|numeric',
            'discount_id' => 'nullable',

            'details' => 'required|array|min:1',
            'details.*.product_id' => 'required|exists:m_product,id',
            'details.*.total_item' => 'required|numeric|min:1',
            'details.*.price' => 'required|numeric|min:1',
            'details.*.discount_nominal' => 'nullable|numeric',
            'details.*.note' => 'nullable|string',
        ];
    }


    public function attributes(): array
    {
        return [
            'customer_id' => 'Customer',
            'voucher_id' => 'Voucher',
            'voucher_nominal' => 'Voucher Nominal',
            'discount_id' => 'Discount',

            'details.*.product_id' => 'Product',
            'details.*.total_item' => 'Total Item',
            'details.*.price' => 'Price',
            'details.*.discount_nominal' => 'Discount Nominal',
            'details.*.note' => 'Note',
        ];
    }

}
