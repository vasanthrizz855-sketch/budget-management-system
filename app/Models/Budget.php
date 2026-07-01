<?php

namespace App\Models;

use App\Enums\BudgetType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_name',
        'budget_type',
        'allocated_amount',
        'start_date',
        'end_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'budget_type' => BudgetType::class,
            'allocated_amount' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function getUsedAmountAttribute(): float
    {
        if (array_key_exists('used_amount', $this->attributes)) {
            return (float) $this->attributes['used_amount'];
        }

        if ($this->relationLoaded('expenses')) {
            return (float) $this->expenses->sum('expense_amount');
        }

        return (float) $this->expenses()->sum('expense_amount');
    }

    public function getRemainingAmountAttribute(): float
    {
        return round((float) $this->allocated_amount - $this->used_amount, 2);
    }

    public function getConsumptionRateAttribute(): float
    {
        if ((float) $this->allocated_amount <= 0) {
            return 0.0;
        }

        return round(($this->used_amount / (float) $this->allocated_amount) * 100, 2);
    }

    public function scopeSearch($query, ?string $search = null)
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function ($builder) use ($search) {
            $builder->where('budget_name', 'like', "%{$search}%")
                ->orWhere('budget_type', 'like', "%{$search}%");
        });
    }
}
