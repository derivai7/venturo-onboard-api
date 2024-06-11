<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Promo\DiscountHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Promo\DiscountRequest;
use App\Http\Resources\Promo\DiscountResource;

class DiscountController extends Controller
{
    private $discount;

    public function __construct()
    {
        $this->discount = new DiscountHelper();
    }

    public function index()
    {
        $discounts = $this->discount->getTotalDiscountsByPromoIds();

        return response()->success($discounts['data']);
    }

    public function store(DiscountRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['customer_id', 'promo_id']);
        $payload = $this->renamePayload($payload);
        $discount = $this->discount->create($payload);

        if (!$discount['status']) {
            return response()->failed($discount['error']);
        }

        return response()->success(new DiscountResource($discount['data']), 'Discount berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $discount = $this->discount->delete($id);

        if (!$discount) {
            return response()->failed(['Mohon maaf discount tidak ditemukan']);
        }

        return response()->success($discount, 'Discount berhasil dihapus');
    }

    public function renamePayload($payload) {
        $payload['m_customer_id'] = $payload['customer_id'] ?? null;
        $payload['m_promo_id'] = $payload['promo_id'] ?? null;
        unset($payload['customer_id']);
        unset($payload['promo_id']);
        return $payload;
    }
}
