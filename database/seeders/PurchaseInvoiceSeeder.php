<?php

namespace Database\Seeders;

use App\Models\PurchaseInvoice;
use Illuminate\Database\Seeder;

class PurchaseInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        PurchaseInvoice::factory()->count(12)->create();
    }
}

