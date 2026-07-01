@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">{{ $item->product_name }}</h1>
        <p class="text-muted mb-0">Product code: {{ $item->product_code }}</p>
    </div>
    <a href="{{ route('admin.products.edit', $item) }}" class="btn btn-primary">Edit Product</a>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card soft-card">
            <div class="card-body">
                <h5 class="mb-3">Product Details</h5>
                <div class="mb-2"><strong>Unit Price:</strong> {{ format_money($item->unit_price) }}</div>
                <div class="mb-2"><strong>Tax %:</strong> {{ number_format((float) $item->tax_percentage, 2) }}%</div>
                <div class="mb-2"><strong>Status:</strong> {{ $item->status instanceof \App\Enums\RecordStatus ? $item->status->label() : $item->status }}</div>
                <div><strong>Description:</strong><br>{{ $item->description ?? '-' }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card soft-card">
            <div class="card-body">
                <h5 class="mb-3">Usage Summary</h5>
                <div class="mb-2">Purchase rows: {{ $item->purchaseInvoiceItems->count() }}</div>
                <div class="mb-2">Sales rows: {{ $item->salesInvoiceItems->count() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

