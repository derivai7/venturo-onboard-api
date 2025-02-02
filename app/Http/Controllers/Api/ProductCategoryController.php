<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Product\ProductCategoryHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\CategoryRequest;
use App\Http\Resources\Product\CategoryCollection;
use App\Http\Resources\Product\CategoryResource;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    private $category;

    public function __construct()
    {
        $this->category = new ProductCategoryHelper();
    }

    public function destroy($id)
    {
        $category = $this->category->delete($id);

        if (!$category) {
            return response()->failed(['Mohon maaf Category tidak ditemukan']);
        }

        return response()->success($category, 'Category berhasil dihapus');
    }

    public function index(Request $request)
    {
        $filter = [
            'name' => $request->name ?? '',
        ];
        $categories = $this->category->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');

        return response()->success(new CategoryCollection($categories['data']));
    }

    public function show($id)
    {
        $category = $this->category->getById($id);

        if (!($category['status'])) {
            return response()->failed(['Data Category tidak ditemukan'], 404);
        }

        return response()->success(new CategoryResource($category['data']));
    }

    public function store(CategoryRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['name']);
        $category = $this->category->create($payload);

        if (!$category['status']) {
            return response()->failed($category['error']);
        }

        return response()->success(new CategoryResource($category['data']), 'Category berhasil ditambahkan');
    }

    public function update(CategoryRequest $request)
    {
        //update index ketika drag and drop
        if ($request->drag) {
            $payload = $request->only([
                'index',
                'id',
            ]);
            $category = $this->category->updateDrag($payload, $payload['id'] ?? '');
            if (!$category['status']) {
                return response()->failed($category['error']);
            }
            return response()->success(new CategoryResource($category['data']), 'Data Category berhasil disimpan');
        }
        //end update index ketika drag and drop

        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['name', 'id']);
        $category = $this->category->update($payload, $payload['id'] ?? '');

        if (!$category['status']) {
            return response()->failed($category['error']);
        }

        return response()->success(new CategoryResource($category['data']), 'Category berhasil diubah');
    }
}
