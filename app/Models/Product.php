<?php

namespace App\Models;

use App\Enums\RecordStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'product_name',
        'description',
        'unit_price',
        'tax_percentage',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'tax_percentage' => 'decimal:2',
            'status' => RecordStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $product): void {
            $product->product_code = $product->product_code ?: generate_entity_code('PROD', 'products');
        });
    }

    public function purchaseInvoiceItems(): HasMany
    {
        return $this->hasMany(PurchaseInvoiceItem::class);
    }

    public function salesInvoiceItems(): HasMany
    {
        return $this->hasMany(SalesInvoiceItem::class);
    }

    public function scopeSearch($query, ?string $search = null)
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function ($builder) use ($search) {
            $builder->where('product_code', 'like', "%{$search}%")
                ->orWhere('product_name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }
}

