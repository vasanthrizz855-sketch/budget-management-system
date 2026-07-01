<?php

namespace App\Models;

use App\Enums\InvoiceType;
use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_type',
        'invoice_id',
        'payment_method',
        'payment_date',
        'amount',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'invoice_type' => InvoiceType::class,
            'payment_method' => PaymentMethod::class,
            'payment_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function purchaseInvoice(): BelongsTo
    {
        return $this->belongsTo(PurchaseInvoice::class, 'invoice_id');
    }

    public function salesInvoice(): BelongsTo
    {
        return $this->belongsTo(SalesInvoice::class, 'invoice_id');
    }

    public function getInvoiceReferenceAttribute(): string
    {
        $invoiceType = $this->invoice_type instanceof InvoiceType ? $this->invoice_type->value : $this->invoice_type;

        return match ($invoiceType) {
            InvoiceType::Purchase->value => $this->purchaseInvoice?->invoice_no ?? 'N/A',
            InvoiceType::Sales->value => $this->salesInvoice?->invoice_no ?? 'N/A',
            default => 'N/A',
        };
    }

    public function scopeSearch($query, ?string $search = null)
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function ($builder) use ($search) {
            $builder->where('remarks', 'like', "%{$search}%")
                ->orWhere('invoice_type', 'like', "%{$search}%");
        });
    }
}
