<?php

namespace App\Http\Controllers\Admin;

use App\Enums\InvoiceType;
use App\Enums\PaymentMethod;
use App\Http\Requests\Admin\PaymentRequest;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Http\RedirectResponse;

class PaymentController extends ResourceController
{
    public function __construct(PaymentRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    protected function viewPath(): string
    {
        return 'admin.payments';
    }

    protected function resourceName(): string
    {
        return 'payments';
    }

    protected function relations(): array
    {
        return ['purchaseInvoice', 'salesInvoice'];
    }

    protected function formViewData(?int $id = null): array
    {
        return [
            'invoiceTypes' => InvoiceType::options(),
            'paymentMethods' => PaymentMethod::options(),
            'purchaseInvoices' => PurchaseInvoice::query()->latest()->get(),
            'salesInvoices' => SalesInvoice::query()->latest()->get(),
        ];
    }

    public function store(PaymentRequest $request): RedirectResponse
    {
        $this->repository->create($request->validated());

        return redirect()->route('admin.payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function update(PaymentRequest $request, int $id): RedirectResponse
    {
        $this->repository->update($id, $request->validated());

        return redirect()->route('admin.payments.index')->with('success', 'Payment updated successfully.');
    }
}

