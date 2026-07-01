<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Requests\Admin\PurchaseInvoiceRequest;
use App\Models\PurchaseInvoice;
use App\Models\Product;
use App\Models\Supplier;
use App\Repositories\Contracts\PurchaseInvoiceRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PurchaseInvoiceController extends ResourceController
{
    public function __construct(PurchaseInvoiceRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function viewPath(): string
    {
        return 'admin.purchase-invoices';
    }

    protected function resourceName(): string
    {
        return 'purchase-invoices';
    }

    protected function relations(): array
    {
        return ['supplier', 'items.product', 'payments'];
    }

    protected function formViewData(?int $id = null): array
    {
        return [
            'suppliers' => Supplier::query()->latest()->get(),
            'products' => Product::query()->latest()->get(),
            'paymentStatuses' => PaymentStatus::options(),
        ];
    }

    public function store(PurchaseInvoiceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $this->repository->saveInvoice($data, $data['items']);

        return redirect()->route('admin.purchase-invoices.index')->with('success', 'Purchase invoice created successfully.');
    }

    public function update(PurchaseInvoiceRequest $request, int $id): RedirectResponse
    {
        $data = $request->validated();
        $invoice = PurchaseInvoice::query()->findOrFail($id);
        $this->repository->saveInvoice($data, $data['items'], $invoice);

        return redirect()->route('admin.purchase-invoices.index')->with('success', 'Purchase invoice updated successfully.');
    }

    public function print(PurchaseInvoice $purchase_invoice): View
    {
        return view('admin.purchase-invoices.print', [
            'item' => $purchase_invoice->load($this->relations()),
        ]);
    }

    public function pdf(PurchaseInvoice $purchase_invoice)
    {
        $invoice = $purchase_invoice->load($this->relations());

        return Pdf::loadView('admin.purchase-invoices.print', [
            'item' => $invoice,
        ])->download($invoice->invoice_no.'.pdf');
    }
}

