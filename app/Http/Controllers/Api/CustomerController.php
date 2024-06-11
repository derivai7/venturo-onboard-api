<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Customer\CustomerHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CreateRequest;
use App\Http\Requests\Customer\UpdateRequest;
use App\Http\Resources\Customer\CustomerCollection;
use App\Http\Resources\Customer\CustomerResource;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private $customerHelper;

    public function __construct()
    {
        $this->customerHelper = new CustomerHelper();
    }

    public function destroy($id)
    {
        $result = $this->customerHelper->delete($id);

        if (!$result) {
            return response()->failed(['Mohon maaf data pelanggan tidak ditemukan']);
        }

        return response()->success("Pelanggan berhasil dihapus");
    }

    public function index(Request $request)
    {
        $filter = [
            'id' => $request->id ?? '',
            'name' => $request->name ?? '',
            'is_verified' => $request->is_verified ?? '',
        ];

        $customers = $this->customerHelper->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');

        return response()->success(new CustomerCollection($customers['data']));
    }

    public function show($id)
    {
        $customer = $this->customerHelper->getById($id);

        if (!$customer['status']) {
            return response()->failed(['Data pelanggan tidak ditemukan'], 404);
        }

        return response()->success(new CustomerResource($customer['data']));
    }

    public function store(CreateRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['name', 'email', 'phone_number', 'date_of_birth', 'photo', 'is_verified']);

        $customer = $this->customerHelper->create($payload);

        if (!$customer['status']) {
            return response()->failed([$customer['error']]);
        }

        return response()->success(new CustomerResource($customer['data']), "Pelanggan berhasil ditambahkan");
    }

    public function update(UpdateRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['id', 'name', 'email', 'phone_number', 'date_of_birth', 'photo', 'is_verified']);

        $customer = $this->customerHelper->update($payload, $payload['id'] ?? '');

        if (!$customer['status']) {
            return response()->failed([$customer['error']]);
        }

        return response()->success(new CustomerResource($customer['data']), "Pelanggan berhasil diubah");
    }
}
