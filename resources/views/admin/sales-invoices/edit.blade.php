@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="h3 mb-1">Edit Sales Invoice</h1>
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.sales-invoices.index') }}">Sales Invoices</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
</div>

<div class="card soft-card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.sales-invoices.update', $item) }}">
            @csrf
            @method('PUT')
            @include('admin.sales-invoices._form', ['item' => $item])
            <div class="mt-4">
                <button class="btn btn-primary">Update Sales Invoice</button>
                <a href="{{ route('admin.sales-invoices.index') }}" class="btn btn-outline-secondary">Back</a>
            </div>
        </form>
    </div>
</div>
@endsection

