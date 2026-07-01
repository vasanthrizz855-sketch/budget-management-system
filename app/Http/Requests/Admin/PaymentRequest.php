<?php

namespace App\Http\Requests\Admin;

use App\Enums\InvoiceType;
use App\Enums\PaymentMethod;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoice_type' => ['required', Rule::in(array_keys(InvoiceType::options()))],
            'invoice_id' => ['required', 'integer'],
            'payment_method' => ['required', Rule::in(array_keys(PaymentMethod::options()))],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator): void {
                $invoiceType = $this->input('invoice_type');
                $invoiceId = $this->input('invoice_id');

                if ($invoiceType === InvoiceType::Purchase->value && ! PurchaseInvoice::query()->whereKey($invoiceId)->exists()) {
                    $validator->errors()->add('invoice_id', 'The selected purchase invoice does not exist.');
                }

                if ($invoiceType === InvoiceType::Sales->value && ! SalesInvoice::query()->whereKey($invoiceId)->exists()) {
                    $validator->errors()->add('invoice_id', 'The selected sales invoice does not exist.');
                }
            },
        ];
    }
}

