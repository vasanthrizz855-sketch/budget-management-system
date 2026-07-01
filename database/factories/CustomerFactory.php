<?php

namespace Database\Factories;

use App\Enums\RecordStatus;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'customer_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('##########'),
            'address' => fake()->address(),
            'gst_number' => fake()->unique()->bothify('GST##??####'),
            'status' => RecordStatus::Active->value,
        ];
    }
}

