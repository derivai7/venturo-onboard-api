<?php

namespace Database\Factories;

use App\Helpers\Sale\SaleHelper;
use App\Models\ProductModel;
use App\Models\SaleModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleDetailModelFactory extends Factory
{
    public function getProducts(): array
    {
        $products = ProductModel::all();

        return $products->map(function ($product) {
            return [
                'id' => $product->id,
                'price' => $product->price,
            ];
        })->toArray();
    }

    public function definition(): array
    {
        $products = $this->getProducts();
        $product = $this->faker->randomElement($products);
        $totalItem = $this->faker->numberBetween(1, 3);

        return [
            'm_product_id' => $product['id'],
            'price' => $totalItem * floatval($product['price']),
            'total_item' => $totalItem,
        ];
    }
}
