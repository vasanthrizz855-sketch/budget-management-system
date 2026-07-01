<?php

namespace App\Providers;

use App\Repositories\Contracts\BudgetRepositoryInterface;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\ExpenseRepositoryInterface;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\PurchaseInvoiceRepositoryInterface;
use App\Repositories\Contracts\SalesInvoiceRepositoryInterface;
use App\Repositories\Contracts\SupplierRepositoryInterface;
use App\Repositories\Eloquent\BudgetRepository;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\ExpenseRepository;
use App\Repositories\Eloquent\PaymentRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Eloquent\PurchaseInvoiceRepository;
use App\Repositories\Eloquent\SalesInvoiceRepository;
use App\Repositories\Eloquent\SupplierRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(PurchaseInvoiceRepositoryInterface::class, PurchaseInvoiceRepository::class);
        $this->app->bind(SalesInvoiceRepositoryInterface::class, SalesInvoiceRepository::class);
        $this->app->bind(BudgetRepositoryInterface::class, BudgetRepository::class);
        $this->app->bind(ExpenseRepositoryInterface::class, ExpenseRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
    }
}

