@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">{{ $item->invoice_no }}</h1>
        <p class="text-muted mb-0">Purchase Invoice</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.purchase-invoices.print', $item) }}" class="btn btn-outline-dark" target="_blank">Print</a>
        <a href="{{ route('admin.purchase-invoices.pdf', $item) }}" class="btn btn-outline-success">PDF</a>
        <a href="{{ route('admin.purchase-invoices.edit', $item) }}" class="btn btn-primary">Edit</a>
    </div>
</div>

@include('admin.invoices.document', [
    'invoice' => $item,
    'title' => 'Purchase Invoice',
    'partyLabel' => 'Supplier',
    'partyName' => $item->supplier?->supplier_name,
])
@endsection

