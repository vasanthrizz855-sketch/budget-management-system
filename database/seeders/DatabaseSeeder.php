<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            CustomerSeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
            BudgetSeeder::class,
            ExpenseSeeder::class,
            PurchaseInvoiceSeeder::class,
            SalesInvoiceSeeder::class,
            PaymentSeeder::class,
        ]);
    }
}

