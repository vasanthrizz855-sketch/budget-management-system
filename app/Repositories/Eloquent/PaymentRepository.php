<?php

namespace App\Repositories\Eloquent;

use App\Enums\InvoiceType;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use App\Repositories\Contracts\PaymentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    protected array $searchable = [
        'payment_method',
        'remarks',
        'invoice_type',
    ];

    protected function modelClass(): string
    {
        return Payment::class;
    }

    public function create(array $data): Model
    {
        return DB::transaction(function () use ($data): Model {
            $payment = parent::create($data);
            $this->syncInvoiceStatus($payment);

            return $payment;
        });
    }

    public function update(Model|int $model, array $data): Model
    {
        return DB::transaction(function () use ($model, $data): Model {
            $record = $model instanceof Payment ? $model : $this->find($model);
            $originalInvoiceType = $this->normalizeInvoiceType($record->invoice_type);
            $originalInvoiceId = (int) $record->invoice_id;

            $payment = parent::update($record, $data);

            $this->syncInvoiceStatus($originalInvoiceType, $originalInvoiceId);
            $this->syncInvoiceStatus($payment);

            return $payment;
        });
    }

    public function delete(Model|int $model): bool
    {
        return DB::transaction(function () use ($model): bool {
            $record = $model instanceof Payment ? $model : $this->find($model);
            $originalInvoiceType = $this->normalizeInvoiceType($record->invoice_type);
            $originalInvoiceId = (int) $record->invoice_id;

            $deleted = parent::delete($record);

            $this->syncInvoiceStatus($originalInvoiceType, $originalInvoiceId);

            return $deleted;
        });
    }

    private function syncInvoiceStatus(Model|string|InvoiceType $paymentOrType, ?int $invoiceId = null): void
    {
        if ($paymentOrType instanceof Payment) {
            $invoiceType = $this->normalizeInvoiceType($paymentOrType->invoice_type);
            $invoiceId = (int) $paymentOrType->invoice_id;
        } else {
            $invoiceType = $this->normalizeInvoiceType($paymentOrType);
        }

        if (! $invoiceId) {
            return;
        }

        $invoice = match ($invoiceType) {
            InvoiceType::Purchase->value => PurchaseInvoice::query()
                ->withSum('payments as paid_amount', 'amount')
                ->find($invoiceId),
            InvoiceType::Sales->value => SalesInvoice::query()
                ->withSum('payments as paid_amount', 'amount')
                ->find($invoiceId),
            default => null,
        };

        if (! $invoice) {
            return;
        }

        $paidAmount = (float) ($invoice->paid_amount ?? 0);
        $grandTotal = (float) $invoice->grand_total;
        $currentStatus = $invoice->payment_status instanceof PaymentStatus
            ? $invoice->payment_status->value
            : (string) $invoice->payment_status;

        $status = match (true) {
            $grandTotal <= 0, $paidAmount >= $grandTotal => PaymentStatus::Paid->value,
            $currentStatus === PaymentStatus::Overdue->value => PaymentStatus::Overdue->value,
            $paidAmount > 0 => PaymentStatus::Pending->value,
            $currentStatus === PaymentStatus::Paid->value => PaymentStatus::Pending->value,
            default => PaymentStatus::Pending->value,
        };

        $invoice->forceFill(['payment_status' => $status])->save();
    }

    private function normalizeInvoiceType(Model|string|InvoiceType $value): string
    {
        if ($value instanceof InvoiceType) {
            return $value->value;
        }

        return (string) $value;
    }
}
