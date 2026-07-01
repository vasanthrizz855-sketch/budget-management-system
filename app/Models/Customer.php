<?php

namespace App\Models;

use App\Enums\RecordStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'customer_name',
        'email',
        'phone',
        'address',
        'gst_number',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => RecordStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $customer): void {
            $customer->customer_code = $customer->customer_code ?: generate_entity_code('CUST', 'customers');
        });
    }

    public function salesInvoices(): HasMany
    {
        return $this->hasMany(SalesInvoice::class);
    }

    public function scopeSearch($query, ?string $search = null)
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function ($builder) use ($search) {
            $builder->where('customer_code', 'like', "%{$search}%")
                ->orWhere('customer_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
        });
    }
}

