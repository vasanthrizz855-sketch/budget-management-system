<?php

namespace Database\Factories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'budget_id' => \App\Models\Budget::query()->inRandomOrder()->value('id') ?? \App\Models\Budget::factory(),
            'expense_name' => fake()->sentence(3),
            'expense_amount' => fake()->randomFloat(2, 100, 10000),
            'expense_date' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
