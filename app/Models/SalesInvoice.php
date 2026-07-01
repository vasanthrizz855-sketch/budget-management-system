<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'customer_id',
        'invoice_date',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'grand_total',
        'payment_status',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'subtotal' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'payment_status' => PaymentStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $invoice): void {
            $invoice->invoice_no = $invoice->invoice_no ?: generate_invoice_no('SINV', 'sales_invoices');
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SalesInvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'invoice_id')->where('invoice_type', 'sales');
    }

    public function scopeSearch($query, ?string $search = null)
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function ($builder) use ($search) {
            $builder->where('invoice_no', 'like', "%{$search}%")
                ->orWhereHas('customer', fn ($customer) => $customer->where('customer_name', 'like', "%{$search}%"));
        });
    }

    public function getPaidAmountAttribute(): float
    {
        if (array_key_exists('paid_amount', $this->attributes)) {
            return (float) $this->attributes['paid_amount'];
        }

        if ($this->relationLoaded('payments')) {
            return (float) $this->payments->sum('amount');
        }

        return (float) $this->payments()->sum('amount');
    }

    public function getDueAmountAttribute(): float
    {
        return max(0, (float) $this->grand_total - $this->paid_amount);
    }
}
