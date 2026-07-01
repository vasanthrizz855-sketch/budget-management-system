<?php

namespace Database\Factories;

use App\Models\SalesInvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalesInvoiceItemFactory extends Factory
{
    protected $model = SalesInvoiceItem::class;

    public function definition(): array
    {
        $quantity = fake()->randomFloat(2, 1, 20);
        $price = fake()->randomFloat(2, 10, 1500);
        $tax = round(($quantity * $price) * fake()->randomFloat(2, 0, 18) / 100, 2);

        return [
            'product_id' => \App\Models\Product::query()->inRandomOrder()->value('id') ?? \App\Models\Product::factory(),
            'quantity' => $quantity,
            'price' => $price,
            'tax' => $tax,
            'total' => round(($quantity * $price) + $tax, 2),
        ];
    }
}
