<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Promo\PromoHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Promo\CreateRequest;
use App\Http\Requests\Promo\UpdateRequest;
use App\Http\Resources\Promo\PromoCollection;
use App\Http\Resources\Promo\PromoResource;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    private $promoHelper;

    public function __construct()
    {
        $this->promoHelper = new PromoHelper();
    }

    public function destroy($id)
    {
        $result = $this->promoHelper->delete($id);

        if (!$result) {
            return response()->failed(['Mohon maaf data promo tidak ditemukan']);
        }

        return response()->success($result, "Promo berhasil dihapus");
    }

    public function index(Request $request)
    {
        $filter = [
            'name' => $request->name ?? '',
            'status' => $request->status ?? '',
        ];
        $promos = $this->promoHelper->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');

        return response()->success(new PromoCollection($promos['data']));
    }

    public function show($id)
    {
        $promo = $this->promoHelper->getById($id);

        if (!($promo['status'])) {
            return response()->failed(['Data promo tidak ditemukan'], 404);
        }

        return response()->success(new PromoResource($promo['data']));
    }

    public function store(CreateRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only([
            'name',
            'status',
            'expired_in_day',
            'nominal_percentage',
            'nominal_rupiah',
            'term_conditions',
            'photo',
        ]);
        $promo = $this->promoHelper->create($payload);

        if (!$promo['status']) {
            return response()->failed($promo['error']);
        }

        return response()->success(new PromoResource($promo['data']), "Promo berhasil ditambahkan");
    }

    public function update(UpdateRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only([
            'id',
            'name',
            'status',
            'expired_in_day',
            'nominal_percentage',
            'nominal_rupiah',
            'term_conditions',
            'photo',
        ]);

        $promo = $this->promoHelper->update($payload, $payload['id'] ?? '');

        if (!$promo['status']) {
            return response()->failed($promo['error']);
        }

        return response()->success(new PromoResource($promo['data']), "Promo berhasil diubah");
    }
}
