@php
    $payment = $item ?? null;
    $currentType = data_get($payment, 'invoice_type');
    $currentType = $currentType instanceof \App\Enums\InvoiceType ? $currentType->value : ($currentType ?? 'purchase');
    $currentInvoiceId = old('invoice_id', data_get($payment, 'invoice_id', ''));
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Invoice Type</label>
        <select name="invoice_type" id="invoice_type" class="form-select @error('invoice_type') is-invalid @enderror">
            @foreach($invoiceTypes as $value => $label)
                <option value="{{ $value }}" @selected(old('invoice_type', $currentType) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('invoice_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Invoice Number</label>
        <select id="purchase_invoice_select" class="form-select invoice-select mb-2">
            <option value="">Select purchase invoice</option>
            @foreach($purchaseInvoices as $invoice)
                <option value="{{ $invoice->id }}" @selected($currentType === 'purchase' && $currentInvoiceId == $invoice->id)>{{ $invoice->invoice_no }}</option>
            @endforeach
        </select>
        <select id="sales_invoice_select" class="form-select invoice-select mb-2">
            <option value="">Select sales invoice</option>
            @foreach($salesInvoices as $invoice)
                <option value="{{ $invoice->id }}" @selected($currentType === 'sales' && $currentInvoiceId == $invoice->id)>{{ $invoice->invoice_no }}</option>
            @endforeach
        </select>
        <input type="hidden" name="invoice_id" id="invoice_id" value="{{ old('invoice_id', $currentInvoiceId) }}">
        @error('invoice_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Payment Method</label>
        <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror">
            @foreach($paymentMethods as $value => $label)
                <option value="{{ $value }}" @selected(old('payment_method', data_get($payment, 'payment_method') instanceof \App\Enums\PaymentMethod ? data_get($payment, 'payment_method')->value : data_get($payment, 'payment_method')) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Payment Date</label>
        <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ old('payment_date', optional(data_get($payment, 'payment_date'))->format('Y-m-d') ?? '') }}">
        @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Amount</label>
        <input type="number" step="0.01" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', data_get($payment, 'amount', '')) }}">
        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
        <label class="form-label">Remarks</label>
        <textarea name="remarks" rows="3" class="form-control @error('remarks') is-invalid @enderror">{{ old('remarks', data_get($payment, 'remarks', '')) }}</textarea>
        @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

@push('scripts')
<script>
    function syncInvoiceSelect() {
        const type = document.getElementById('invoice_type').value;
        const hidden = document.getElementById('invoice_id');
        const purchaseSelect = document.getElementById('purchase_invoice_select');
        const salesSelect = document.getElementById('sales_invoice_select');

        purchaseSelect.style.display = type === 'purchase' ? 'block' : 'none';
        salesSelect.style.display = type === 'sales' ? 'block' : 'none';
        hidden.value = type === 'purchase' ? purchaseSelect.value : salesSelect.value;
    }

    document.getElementById('invoice_type').addEventListener('change', syncInvoiceSelect);
    document.getElementById('purchase_invoice_select').addEventListener('change', function () {
        if (document.getElementById('invoice_type').value === 'purchase') {
            document.getElementById('invoice_id').value = this.value;
        }
    });
    document.getElementById('sales_invoice_select').addEventListener('change', function () {
        if (document.getElementById('invoice_type').value === 'sales') {
            document.getElementById('invoice_id').value = this.value;
        }
    });
    syncInvoiceSelect();
</script>
@endpush

