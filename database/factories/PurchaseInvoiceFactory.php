<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseInvoiceFactory extends Factory
{
    protected $model = PurchaseInvoice::class;

    public function definition(): array
    {
        return [
            'supplier_id' => \App\Models\Supplier::query()->inRandomOrder()->value('id') ?? \App\Models\Supplier::factory(),
            'invoice_date' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'subtotal' => 0,
            'tax_amount' => 0,
            'discount_amount' => fake()->randomFloat(2, 0, 1000),
            'grand_total' => 0,
            'payment_status' => fake()->randomElement(array_keys(PaymentStatus::options())),
            'remarks' => fake()->optional()->sentence(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (PurchaseInvoice $invoice): void {
            if ($invoice->items()->exists()) {
                return;
            }

            $items = PurchaseInvoiceItem::factory()->count(fake()->numberBetween(2, 4))->make([
                'purchase_invoice_id' => $invoice->id,
            ]);

            $subtotal = 0;
            $taxAmount = 0;

            foreach ($items as $item) {
                $item->purchase_invoice_id = $invoice->id;
                $item->total = round(((float) $item->quantity * (float) $item->price) + (float) $item->tax, 2);
                $item->save();

                $subtotal += ((float) $item->quantity * (float) $item->price);
                $taxAmount += (float) $item->tax;
            }

            $invoice->updateQuietly([
                'subtotal' => round($subtotal, 2),
                'tax_amount' => round($taxAmount, 2),
                'grand_total' => round($subtotal + $taxAmount - (float) $invoice->discount_amount, 2),
            ]);
        });
    }
}
