<?php

namespace Database\Seeders;

use App\Models\SalesInvoice;
use Illuminate\Database\Seeder;

class SalesInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        SalesInvoice::factory()->count(15)->create();
    }
}

