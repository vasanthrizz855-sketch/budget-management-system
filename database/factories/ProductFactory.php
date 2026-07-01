<?php

namespace Database\Factories;

use App\Enums\RecordStatus;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'product_name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'unit_price' => fake()->randomFloat(2, 50, 5000),
            'tax_percentage' => fake()->randomFloat(2, 0, 18),
            'status' => RecordStatus::Active->value,
        ];
    }
}

