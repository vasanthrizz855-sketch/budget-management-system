@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Payments</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Payments</li>
        </ol>
    </div>
    <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">Record Payment</a>
</div>

<div class="card soft-card">
    <div class="card-body">
        <form class="row g-2 mb-4" method="GET" action="{{ route('admin.payments.index') }}">
            <div class="col-md-10">
                <input type="search" name="search" class="form-control" placeholder="Search payment..." value="{{ $search }}">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-primary">Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Invoice Type</th><th>Reference</th><th>Method</th><th>Date</th><th>Amount</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse($items as $payment)
                    <tr>
                        <td>{{ $payment->invoice_type instanceof \App\Enums\InvoiceType ? $payment->invoice_type->label() : $payment->invoice_type }}</td>
                        <td>{{ $payment->invoice_reference }}</td>
                        <td>{{ $payment->payment_method instanceof \App\Enums\PaymentMethod ? $payment->payment_method->label() : $payment->payment_method }}</td>
                        <td>{{ format_date($payment->payment_date) }}</td>
                        <td>{{ format_money($payment->amount) }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-outline-secondary">View</a>
                            <a href="{{ route('admin.payments.edit', $payment) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this payment?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-5 text-muted">No payments recorded yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </div>
</div>
@endsection

