<?php

namespace Database\Seeders;

use App\Enums\InvoiceType;
use App\Enums\PaymentMethod;
use App\Models\Payment;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        PurchaseInvoice::query()->take(6)->get()->each(function (PurchaseInvoice $invoice): void {
            Payment::query()->create([
                'invoice_type' => InvoiceType::Purchase->value,
                'invoice_id' => $invoice->id,
                'payment_method' => PaymentMethod::BankTransfer->value,
                'payment_date' => $invoice->invoice_date->copy()->addDays(3)->format('Y-m-d'),
                'amount' => round(((float) $invoice->grand_total) * 0.75, 2),
                'remarks' => 'Partial payment against '.$invoice->invoice_no,
            ]);
        });

        SalesInvoice::query()->take(8)->get()->each(function (SalesInvoice $invoice): void {
            Payment::query()->create([
                'invoice_type' => InvoiceType::Sales->value,
                'invoice_id' => $invoice->id,
                'payment_method' => PaymentMethod::Upi->value,
                'payment_date' => $invoice->invoice_date->copy()->addDays(5)->format('Y-m-d'),
                'amount' => round(((float) $invoice->grand_total) * 0.5, 2),
                'remarks' => 'Partial collection against '.$invoice->invoice_no,
            ]);
        });
    }
}

