<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Sale\SaleHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sale\SaleRequest;
use App\Http\Resources\Sale\SaleCollection;
use App\Http\Resources\Sale\SaleResource;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    private $sale;

    public function __construct()
    {
        $this->sale = new SaleHelper();
    }

    public function index(Request $request)
    {
        $filter = [
            'name' => $request->name ?? '',
            'm_product_category_id' => $request->product_category_id ?? '',
            'is_available' => isset($request->is_available) ? $request->is_available : '',
        ];
        $sale = $this->sale->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');
        return response()->success(new SaleCollection($sale['data']));
    }

    public function store(SaleRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }
        $payload = $request->only([
            'customer_id',
            'voucher_id',
            'voucher_nominal',
            'discount_id',
            'subtotal',
            'total_payment',
            'details',
        ]);

        foreach ($payload['details'] as $key => $detail) {
            $payload['details'][$key]['m_product_id'] = $detail['product_id'];
            if (isset($detail['product_detail_id'])) {
                $payload['details'][$key]['m_product_detail_id'] = $detail['product_detail_id'];
            }
        }
        $sale = $this->sale->create($this->renamePayload($payload));
        if (!$sale['status']) {
            return response()->failed($sale['error']);
        }
        return response()->success(new SaleResource($sale['data']), 'Transaksi berhasil ditambahkan');
    }

    public function renamePayload($payload)
    {
        $payload['m_customer_id'] = $payload['customer_id'];
        $payload['m_voucher_id'] = $payload['voucher_id'] ?? null;
        $payload['m_discount_id'] = $payload['discount_id'] ?? null;
        unset($payload['customer_id']);
        unset($payload['voucher_id']);
        unset($payload['discount_id']);
        foreach ($payload['details'] as $key => $detail) {
            $payload['details'][$key]['m_product_id'] = $detail['product_id'];
            $payload['details'][$key]['m_product_detail_id'] = $detail['product_detail_id'] ?? null;
            unset($detail['product_id']);
            unset($detail['product_detail_id']);
        }
        return $payload;
    }
}
