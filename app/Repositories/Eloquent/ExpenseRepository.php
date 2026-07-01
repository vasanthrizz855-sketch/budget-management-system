<?php

namespace App\Repositories\Eloquent;

use App\Models\Expense;
use App\Repositories\Contracts\ExpenseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class ExpenseRepository extends BaseRepository implements ExpenseRepositoryInterface
{
    protected array $with = [
        'budget',
    ];

    protected function modelClass(): string
    {
        return Expense::class;
    }

    protected function applySearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function ($builder) use ($search): void {
            $builder->where('expense_name', 'like', "%{$search}%")
                ->orWhereHas('budget', fn ($budget) => $budget->where('budget_name', 'like', "%{$search}%"));
        });
    }
}

