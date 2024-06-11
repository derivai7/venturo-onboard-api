<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Promo\VoucherHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Promo\VoucherRequest;
use App\Http\Resources\Promo\VoucherCollection;
use App\Http\Resources\Promo\VoucherResource;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    private $voucher;

    public function __construct()
    {
        $this->voucher = new VoucherHelper();
    }

    public function destroy($id)
    {
        $voucher = $this->voucher->delete($id);

        if (!$voucher) {
            return response()->failed(['Mohon maaf voucher tidak ditemukan']);
        }

        return response()->success($voucher, 'Voucher berhasil dihapus');
    }

    public function index(Request $request)
    {
        $filter = [
            'm_customer_id' => isset($request->customer_id) ? explode(',', $request->customer_id) : [],
            'start_time' => $request->start_time ?? '',
            'end_time' => $request->end_time ?? '',
        ];
        $vouchers = $this->voucher->getAll($filter, $request->per_page ?? 25, $request->sort ?? '', false);

        return response()->success(new VoucherCollection($vouchers['data']));
    }


    public function show($id)
    {
        $voucher = $this->voucher->getById($id);

        if (!($voucher['status'])) {
            return response()->failed(['Voucher tidak ditemukan'], 404);
        }

        return response()->success(new VoucherResource($voucher['data']));
    }

    public function store(VoucherRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only([
            'customer_id',
            'promo_id',
            'start_time',
            'end_time',
            'total_voucher',
            'nominal_rupiah',
            'photo',
            'description'
        ]);
        $payload = $this->renamePayload($payload);
        $voucher = $this->voucher->create($payload);

        if (!$voucher['status']) {
            return response()->failed($voucher['error']);
        }

        return response()->success(new VoucherResource($voucher['data']), 'Voucher berhasil ditambahkan');
    }

    public function update(VoucherRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['id', 'customer_id', 'promo_id', 'start_time', 'end_time', 'total_voucher', 'nominal_rupiah', 'photo', 'description']);
        $payload = $this->renamePayload($payload);
        $voucher = $this->voucher->update($payload, $payload['id'] ?? '');

        if (!$voucher['status']) {
            return response()->failed($voucher['error']);
        }

        return response()->success(new VoucherResource($voucher['data']), 'Voucher berhasil diubah');
    }

    public function renamePayload($payload)
    {
        $payload['m_customer_id'] = $payload['customer_id'] ?? null;
        $payload['m_promo_id'] = $payload['promo_id'] ?? null;
        unset($payload['customer_id']);
        unset($payload['promo_id']);
        return $payload;
    }
}
