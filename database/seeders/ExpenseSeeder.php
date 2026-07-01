<?php

namespace Database\Seeders;

use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        Budget::query()->take(8)->get()->each(function (Budget $budget): void {
            Expense::factory()->count(fake()->numberBetween(2, 5))->create([
                'budget_id' => $budget->id,
            ]);
        });
    }
}

