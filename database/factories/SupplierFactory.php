<?php

namespace Database\Factories;

use App\Enums\RecordStatus;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition(): array
    {
        return [
            'supplier_name' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->numerify('##########'),
            'address' => fake()->address(),
            'gst_number' => fake()->unique()->bothify('GST##??####'),
            'status' => RecordStatus::Active->value,
        ];
    }
}

