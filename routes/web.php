<?php

use App\Http\Controllers\Admin\BudgetController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseInvoiceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SalesInvoiceController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/change-password', [ProfileController::class, 'editPassword'])->name('profile.password');
    Route::put('/change-password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('customers', CustomerController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('products', ProductController::class);
    Route::resource('purchase-invoices', PurchaseInvoiceController::class);
    Route::get('purchase-invoices/{purchase_invoice}/print', [PurchaseInvoiceController::class, 'print'])->name('purchase-invoices.print');
    Route::get('purchase-invoices/{purchase_invoice}/pdf', [PurchaseInvoiceController::class, 'pdf'])->name('purchase-invoices.pdf');

    Route::resource('sales-invoices', SalesInvoiceController::class);
    Route::get('sales-invoices/{sales_invoice}/print', [SalesInvoiceController::class, 'print'])->name('sales-invoices.print');
    Route::get('sales-invoices/{sales_invoice}/pdf', [SalesInvoiceController::class, 'pdf'])->name('sales-invoices.pdf');

    Route::resource('budgets', BudgetController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('payments', PaymentController::class);

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/{type}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('reports/{type}/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('reports/{type}/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
});
