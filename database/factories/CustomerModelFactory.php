<?php

namespace Database\Factories;

use App\Models\CustomerModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerModelFactory extends Factory
{
    protected $model = CustomerModel::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name()
        ];
    }
}
