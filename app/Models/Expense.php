<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'expense_name',
        'expense_amount',
        'expense_date',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'expense_amount' => 'decimal:2',
            'expense_date' => 'date',
        ];
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function scopeSearch($query, ?string $search = null)
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function ($builder) use ($search) {
            $builder->where('expense_name', 'like', "%{$search}%")
                ->orWhereHas('budget', fn ($budget) => $budget->where('budget_name', 'like', "%{$search}%"));
        });
    }
}

