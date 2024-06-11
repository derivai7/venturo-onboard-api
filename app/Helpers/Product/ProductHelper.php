<?php

namespace App\Helpers\Product;

use App\Helpers\Venturo;
use App\Models\ProductDetailModel;
use App\Models\ProductModel;
use Throwable;

class ProductHelper extends Venturo
{
    const PRODUCT_PHOTO_DIRECTORY = 'foto-produk';
    private $product;
    private $productDetail;

    public function __construct()
    {
        $this->product = new ProductModel();
        $this->productDetail = new ProductDetailModel();
    }

    private function uploadAndGetPayload(array $payload): array
    {
        if (!empty($payload['photo'])) {
            $fileName = $this->generateFileName($payload['photo'], 'PRODUCT_' . date('Ymdhis'));
            $photo = $payload['photo']->storeAs(self::PRODUCT_PHOTO_DIRECTORY, $fileName, 'public');
            $payload['photo'] = $photo;
        } else {
            unset($payload['photo']);
        }

        return $payload;
    }


    public function create(array $payload): array
    {
        try {
            $payload = $this->uploadAndGetPayload($payload);

            $this->beginTransaction();

            $product = $this->product->store($payload);

            $this->insertUpdateDetail($payload['details'] ?? [], $product->id);

            $this->commitTransaction();

            return [
                'status' => true,
                'data' => $product
            ];
        } catch (Throwable $th) {
            $this->rollbackTransaction();

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    public function delete(string $productId): array
    {
        try {
            $this->beginTransaction();

            $this->product->drop($productId);

            $this->productDetail->dropByProductId($productId);

            $this->commitTransaction();

            return [
                'status' => true,
                'data' => $productId
            ];
        } catch (Throwable $th) {
            $this->rollbackTransaction();

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $this->updateIdDetail();
        $products = $this->product->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $products
        ];
    }

    public function getById(string $id): array
    {
        $product = $this->product->getById($id);
        if (empty($product)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $product
        ];
    }

    public function update(array $payload): array
    {
        try {
            $payload = $this->uploadAndGetPayload($payload);

            $this->beginTransaction();

            $this->product->edit($payload, $payload['id']);

            $this->insertUpdateDetail($payload['details'] ?? [], $payload['id']);
            $this->deleteDetail($payload['details_deleted'] ?? []);

            $product = $this->getById($payload['id']);
            $this->commitTransaction();

            return [
                'status' => true,
                'data' => $product['data']
            ];
        } catch (Throwable $th) {
            $this->rollbackTransaction();

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    private function deleteDetail(array $details): void
    {
        if (empty($details)) {
            return;
        }

        foreach ($details as $val) {
            $this->productDetail->drop($val['id']);
        }
    }

    private function insertUpdateDetail(array $details, string $productId): void
    {
        if (empty($details)) {
            return;
        }

        foreach ($details as $val) {
            // Insert
            if (isset($val['is_added']) && $val['is_added']) {
                $val['m_product_id'] = $productId;
                $this->productDetail->store($val);
            }

            // Update
            if (isset($val['is_updated']) && $val['is_updated']) {
                $this->productDetail->edit($val, $val['id']);
            }
        }
    }

    private function updateIdDetail() {
        $productsDetail = $this->productDetail->where('m_product_id', '0')->get();

        if (!$productsDetail->isEmpty()) {
            $productNewest = $this->product->getNewest();
            $productId = $productNewest->id;

            $this->productDetail->where('m_product_id', '0')->update(['m_product_id' => $productId]);
        }
    }
}
