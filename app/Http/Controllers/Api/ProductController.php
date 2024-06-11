<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Product\ProductHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $product;

    public function __construct()
    {
        $this->product = new ProductHelper();
    }

    public function destroy($id)
    {
        $product = $this->product->delete($id);
        if (!$product['status']) {
            return response()->failed(['Mohon maaf product tidak ditemukan']);
        }
        return response()->success($product, 'Product berhasil dihapus');
    }

    public function index(Request $request)
    {
        $filter = [
            'name' => $request->name ?? '',
            'm_product_category_id' => $request->product_category_id ?? '',
            'is_available' => isset($request->is_available) ? $request->is_available : '',
        ];
        $products = $this->product->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');
        return response()->success(new ProductCollection($products['data']));
    }

    public function show($id)
    {
        $product = $this->product->getById($id);
        if (!($product['status'])) {
            return response()->failed(['Data product tidak ditemukan'], 404);
        }
        return response()->success(new ProductResource($product['data']));
    }

    public function store(ProductRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only([
            'name',
            'price',
            'description',
            'photo',
            'is_available',
            'details',
            'product_category_id'
        ]);

        $payload['m_product_category_id'] = $payload['product_category_id'];
        $product = $this->product->create($payload);

        if (!$product['status']) {
            return response()->failed($product['error']);
        }

        return response()->success(new ProductResource($product['data']), 'Product berhasil ditambahkan');
    }

    public function update(ProductRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only([
            'name',
            'price',
            'description',
            'photo',
            'is_available',
            'details',
            'details_deleted',
            'id',
            'product_category_id'
        ]);

        $payload['m_product_category_id'] = $payload['product_category_id'];
        $product = $this->product->update($payload);

        if (!$product['status']) {
            return response()->failed($product['error']);
        }

        return response()->success(new ProductResource($product['data']), 'Product berhasil diubah');
    }
}
