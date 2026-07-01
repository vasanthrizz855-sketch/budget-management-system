@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Payment #{{ $item->id }}</h1>
        <p class="text-muted mb-0">{{ $item->invoice_reference }}</p>
    </div>
    <a href="{{ route('admin.payments.edit', $item) }}" class="btn btn-primary">Edit Payment</a>
</div>

<div class="card soft-card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4"><strong>Invoice Type:</strong> {{ $item->invoice_type instanceof \App\Enums\InvoiceType ? $item->invoice_type->label() : $item->invoice_type }}</div>
            <div class="col-md-4"><strong>Method:</strong> {{ $item->payment_method instanceof \App\Enums\PaymentMethod ? $item->payment_method->label() : $item->payment_method }}</div>
            <div class="col-md-4"><strong>Date:</strong> {{ format_date($item->payment_date) }}</div>
            <div class="col-md-4"><strong>Amount:</strong> {{ format_money($item->amount) }}</div>
            <div class="col-12"><strong>Remarks:</strong><br>{{ $item->remarks ?? '-' }}</div>
        </div>
    </div>
</div>
@endsection

