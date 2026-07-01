<?php

namespace Database\Factories;

use App\Enums\BudgetType;
use App\Models\Budget;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetFactory extends Factory
{
    protected $model = Budget::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-6 months', '+1 month');
        $end = (clone $start)->modify('+3 months');

        return [
            'budget_name' => fake()->bs(),
            'budget_type' => fake()->randomElement(array_keys(BudgetType::options())),
            'allocated_amount' => fake()->randomFloat(2, 10000, 500000),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
