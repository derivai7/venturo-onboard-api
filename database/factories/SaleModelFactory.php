<?php

namespace Database\Factories;

use App\Helpers\Sale\SaleHelper;
use App\Models\CustomerModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleModelFactory extends Factory
{
    public function getCustomersId(): array
    {
        $model = new CustomerModel();
        $customers = $model->get();

        return array_map(function ($customer) {
            return $customer['id'];
        }, $customers->toArray());
    }

    public function setNoReceipt(): string
    {
        return (new SaleHelper())->generateReceiptNumber();
    }

    public function definition(): array
    {
        $customers = $this->getCustomersId();

        return [
            'no_receipt'=> $this->setNoReceipt(),
            'm_customer_id' => $this->faker->randomElement($customers),
            'date' => $this->faker->dateTimeBetween('-500 days', 'yesterday'),

        ];
    }
}
