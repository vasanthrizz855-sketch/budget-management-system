@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">{{ $item->expense_name }}</h1>
        <p class="text-muted mb-0">{{ $item->budget?->budget_name ?? 'Unlinked budget' }}</p>
    </div>
    <a href="{{ route('admin.expenses.edit', $item) }}" class="btn btn-primary">Edit Expense</a>
</div>

<div class="card soft-card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4"><strong>Amount:</strong> {{ format_money($item->expense_amount) }}</div>
            <div class="col-md-4"><strong>Date:</strong> {{ format_date($item->expense_date) }}</div>
            <div class="col-md-4"><strong>Budget:</strong> {{ $item->budget?->budget_name ?? '-' }}</div>
            <div class="col-12"><strong>Description:</strong><br>{{ $item->description ?? '-' }}</div>
        </div>
    </div>
</div>
@endsection

