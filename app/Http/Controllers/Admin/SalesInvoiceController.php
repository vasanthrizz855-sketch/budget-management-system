<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Requests\Admin\SalesInvoiceRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesInvoice;
use App\Repositories\Contracts\SalesInvoiceRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SalesInvoiceController extends ResourceController
{
    public function __construct(SalesInvoiceRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function viewPath(): string
    {
        return 'admin.sales-invoices';
    }

    protected function resourceName(): string
    {
        return 'sales-invoices';
    }

    protected function relations(): array
    {
        return ['customer', 'items.product', 'payments'];
    }

    protected function formViewData(?int $id = null): array
    {
        return [
            'customers' => Customer::query()->latest()->get(),
            'products' => Product::query()->latest()->get(),
            'paymentStatuses' => PaymentStatus::options(),
        ];
    }

    public function store(SalesInvoiceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->repository->saveInvoice($data, $data['items']);

        return redirect()->route('admin.sales-invoices.index')->with('success', 'Sales invoice created successfully.');
    }

    public function update(SalesInvoiceRequest $request, int $id): RedirectResponse
    {
        $data = $request->validated();
        $invoice = SalesInvoice::query()->findOrFail($id);
        $this->repository->saveInvoice($data, $data['items'], $invoice);

        return redirect()->route('admin.sales-invoices.index')->with('success', 'Sales invoice updated successfully.');
    }

    public function print(SalesInvoice $sales_invoice): View
    {
        return view('admin.sales-invoices.print', [
            'item' => $sales_invoice->load($this->relations()),
        ]);
    }

    public function pdf(SalesInvoice $sales_invoice)
    {
        $invoice = $sales_invoice->load($this->relations());

        return Pdf::loadView('admin.sales-invoices.print', [
            'item' => $invoice,
        ])->download($invoice->invoice_no.'.pdf');
    }
}

