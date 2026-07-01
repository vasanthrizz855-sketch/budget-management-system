@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">{{ $item->supplier_name }}</h1>
        <p class="text-muted mb-0">Supplier code: {{ $item->supplier_code }}</p>
    </div>
    <a href="{{ route('admin.suppliers.edit', $item) }}" class="btn btn-primary">Edit Supplier</a>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card soft-card">
            <div class="card-body">
                <h5 class="mb-3">Supplier Details</h5>
                <div class="mb-2"><strong>Email:</strong> {{ $item->email ?? '-' }}</div>
                <div class="mb-2"><strong>Phone:</strong> {{ $item->phone ?? '-' }}</div>
                <div class="mb-2"><strong>GST:</strong> {{ $item->gst_number ?? '-' }}</div>
                <div class="mb-2"><strong>Status:</strong> {{ $item->status instanceof \App\Enums\RecordStatus ? $item->status->label() : $item->status }}</div>
                <div><strong>Address:</strong><br>{{ $item->address ?? '-' }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card soft-card">
            <div class="card-body">
                <h5 class="mb-3">Purchase Invoice History</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($item->purchaseInvoices as $invoice)
                            <tr>
                                <td>{{ $invoice->invoice_no }}</td>
                                <td>{{ format_date($invoice->invoice_date) }}</td>
                                <td>{{ format_money($invoice->grand_total) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">No invoices yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

