@php
    $items = $invoice->items ?? collect();
    $payments = $invoice->payments ?? collect();
@endphp

<div class="card soft-card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h4 class="mb-1">{{ $title }}</h4>
                <div class="text-muted">{{ $invoice->invoice_no }}</div>
            </div>
            <div class="text-end">
                <div><strong>Date:</strong> {{ format_date($invoice->invoice_date) }}</div>
                <div><strong>Status:</strong> {{ $invoice->payment_status instanceof \App\Enums\PaymentStatus ? $invoice->payment_status->label() : $invoice->payment_status }}</div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light">
                    <div class="fw-semibold mb-1">{{ $partyLabel }}</div>
                    <div>{{ $partyName }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 rounded-3 bg-light">
                    <div class="fw-semibold mb-1">Financial Summary</div>
                    <div>Subtotal: {{ format_money($invoice->subtotal) }}</div>
                    <div>Tax: {{ format_money($invoice->tax_amount) }}</div>
                    <div>Discount: {{ format_money($invoice->discount_amount) }}</div>
                    <div class="fw-bold">Grand Total: {{ format_money($invoice->grand_total) }}</div>
                </div>
            </div>
        </div>

        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Tax</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row->product?->product_name ?? '-' }}</td>
                        <td>{{ number_format((float) $row->quantity, 2) }}</td>
                        <td>{{ format_money($row->price) }}</td>
                        <td>{{ format_money($row->tax) }}</td>
                        <td>{{ format_money($row->total) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No line items found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="row g-3">
            <div class="col-lg-6">
                <h6 class="mb-3">Payment History</h6>
                <div class="table-responsive">
                    <table class="table table-sm datatable">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Method</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ format_date($payment->payment_date) }}</td>
                                <td>{{ $payment->payment_method instanceof \App\Enums\PaymentMethod ? $payment->payment_method->label() : $payment->payment_method }}</td>
                                <td>{{ format_money($payment->amount) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">No payments recorded.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-3 rounded-3 bg-light h-100">
                    <div class="fw-semibold mb-2">Remarks</div>
                    <div>{{ $invoice->remarks ?? 'No remarks provided.' }}</div>
                    <div class="mt-3">
                        <div>Due Amount: <strong>{{ format_money($invoice->due_amount) }}</strong></div>
                        <div>Paid Amount: <strong>{{ format_money($invoice->paid_amount) }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

