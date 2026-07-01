@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Sales Invoices</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Sales Invoices</li>
        </ol>
    </div>
    <a href="{{ route('admin.sales-invoices.create') }}" class="btn btn-primary">Add Sales Invoice</a>
</div>

<div class="card soft-card">
    <div class="card-body">
        <form class="row g-2 mb-4" method="GET" action="{{ route('admin.sales-invoices.index') }}">
            <div class="col-md-10">
                <input type="search" name="search" class="form-control" placeholder="Search invoice..." value="{{ $search }}">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-primary">Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Invoice No</th><th>Customer</th><th>Date</th><th>Total</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse($items as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_no }}</td>
                        <td>{{ $invoice->customer?->customer_name }}</td>
                        <td>{{ format_date($invoice->invoice_date) }}</td>
                        <td>{{ format_money($invoice->grand_total) }}</td>
                        <td><span class="badge bg-{{ status_badge_class($invoice->payment_status instanceof \App\Enums\PaymentStatus ? $invoice->payment_status->value : $invoice->payment_status) }}">{{ $invoice->payment_status instanceof \App\Enums\PaymentStatus ? $invoice->payment_status->label() : $invoice->payment_status }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('admin.sales-invoices.show', $invoice) }}" class="btn btn-sm btn-outline-secondary">View</a>
                            <a href="{{ route('admin.sales-invoices.edit', $invoice) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="{{ route('admin.sales-invoices.print', $invoice) }}" class="btn btn-sm btn-outline-dark" target="_blank">Print</a>
                            <a href="{{ route('admin.sales-invoices.pdf', $invoice) }}" class="btn btn-sm btn-outline-success">PDF</a>
                            <form action="{{ route('admin.sales-invoices.destroy', $invoice) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this sales invoice?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">No sales invoices found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </div>
</div>
@endsection

