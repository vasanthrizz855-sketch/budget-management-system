<?php

namespace Database\Factories;

use App\Enums\InvoiceType;
use App\Enums\PaymentMethod;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'invoice_type' => fake()->randomElement(array_keys(InvoiceType::options())),
            'invoice_id' => fake()->numberBetween(1, 1000),
            'payment_method' => fake()->randomElement(array_keys(PaymentMethod::options())),
            'payment_date' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'amount' => fake()->randomFloat(2, 100, 10000),
            'remarks' => fake()->optional()->sentence(),
        ];
    }
}
