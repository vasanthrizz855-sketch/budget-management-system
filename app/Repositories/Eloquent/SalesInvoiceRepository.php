<?php

namespace App\Repositories\Eloquent;

use App\Models\SalesInvoice;
use App\Repositories\Contracts\SalesInvoiceRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SalesInvoiceRepository extends BaseRepository implements SalesInvoiceRepositoryInterface
{
    protected array $with = [
        'customer',
        'items.product',
        'payments',
    ];

    protected function modelClass(): string
    {
        return SalesInvoice::class;
    }

    protected function applySearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function ($builder) use ($search): void {
            $builder->where('invoice_no', 'like', "%{$search}%")
                ->orWhereHas('customer', fn ($customer) => $customer->where('customer_name', 'like', "%{$search}%"));
        });
    }

    public function saveInvoice(array $data, array $items, ?SalesInvoice $invoice = null): SalesInvoice
    {
        return DB::transaction(function () use ($data, $items, $invoice): SalesInvoice {
            $discount = (float) Arr::get($data, 'discount_amount', 0);
            $products = Product::query()
                ->whereIn('id', collect($items)->pluck('product_id')->filter()->unique())
                ->get()
                ->keyBy('id');

            $invoiceData = Arr::except($data, ['items']);
            $invoiceData['subtotal'] = 0;
            $invoiceData['tax_amount'] = 0;
            $invoiceData['grand_total'] = 0;
            $invoiceData['discount_amount'] = $discount;

            $record = $invoice ? $this->update($invoice, $invoiceData) : $this->create($invoiceData);

            $record->items()->delete();

            $subtotal = 0;
            $taxAmount = 0;

            foreach ($items as $item) {
                $quantity = (float) $item['quantity'];
                $price = (float) $item['price'];
                $product = $products->get((int) $item['product_id']);
                $taxRate = (float) ($product?->tax_percentage ?? 0);
                $tax = round(($quantity * $price * $taxRate) / 100, 2);
                $total = round(($quantity * $price) + $tax, 2);

                $record->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'tax' => $tax,
                    'total' => $total,
                ]);

                $subtotal += $quantity * $price;
                $taxAmount += $tax;
            }

            $record->update([
                'subtotal' => round($subtotal, 2),
                'tax_amount' => round($taxAmount, 2),
                'discount_amount' => $discount,
                'grand_total' => round($subtotal + $taxAmount - $discount, 2),
            ]);

            return $record->refresh()->load($this->with);
        });
    }

    public function delete(Model|int $model): bool
    {
        return DB::transaction(function () use ($model): bool {
            $record = $model instanceof SalesInvoice ? $model : $this->find($model);

            if ($record->payments()->exists()) {
                throw new RuntimeException('Cannot delete a sales invoice that already has payment records. Remove the payments first.');
            }

            return parent::delete($record);
        });
    }
}
