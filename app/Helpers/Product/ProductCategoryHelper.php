<?php
namespace App\Helpers\Product;

use App\Helpers\Venturo;
use App\Models\ProductCategoryModel;
use Throwable;

class ProductCategoryHelper extends Venturo
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new ProductCategoryModel();
    }

    public function create(array $payload): array
    {
        try {
            //pemberian index awal
            $maxIndex = $this->categoryModel::max('index');
            if (!isset($maxIndex)) {
                $maxIndex = 0;
            } else if ($maxIndex == 0) {
                $maxIndex = 1;
            } else {
                $maxIndex += 1;
            }
            $payload['index'] = $maxIndex;
            $category = $this->categoryModel->store($payload);

            return [
                'status' => true,
                'data' => $category
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    public function delete(string $id): bool
    {
        try {
            $index = $this->categoryModel->getById($id)->index;
            $this->categoryModel->where('index', '>', $index)->decrement('index', 1);

            $this->categoryModel->drop($id);

            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $categories = $this->categoryModel->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $categories
        ];
    }

    public function getById(string $id): array
    {
        $category = $this->categoryModel->getById($id);
        if (empty($category)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $category
        ];
    }

    public function update(array $payload, string $id): array
    {
        try {
            $this->categoryModel->edit($payload, $id);

            $category = $this->getById($id);

            return [
                'status' => true,
                'data' => $category['data']
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    public function updateDrag(array $payload, string $id): array
    {
        try {
            $category = $this->categoryModel->find($id);

            if (isset($category->id)) {
                if ($category->index == $payload['index']) {
                    return [
                        'status' => true,
                        'data' => $this->getById($payload['id'])
                    ];
                } else if ($category->index < $payload['index']) {
                    $this->categoryModel->where('index', '>=', $category['index'])->where('index', '<=', $payload['index'])->decrement('index', 1);
                    $this->categoryModel->edit($payload, $id);
                } else if ($category->index > $payload['index']) {
                    $this->categoryModel->where('index', '<=', $category['index'])->where('index', '>=', $payload['index'])->increment('index', 1);
                    $this->categoryModel->edit($payload, $id);
                }
            }

            return [
                'status' => true,
                'data' => $this->getById($payload['id'])
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }
}
