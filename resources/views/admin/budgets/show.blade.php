@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">{{ $item->budget_name }}</h1>
        <p class="text-muted mb-0">{{ $item->budget_type instanceof \App\Enums\BudgetType ? $item->budget_type->label() : $item->budget_type }} budget</p>
    </div>
    <a href="{{ route('admin.budgets.edit', $item) }}" class="btn btn-primary">Edit Budget</a>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card soft-card">
            <div class="card-body">
                <h5 class="mb-3">Budget Summary</h5>
                <div class="mb-2"><strong>Allocated:</strong> {{ format_money($item->allocated_amount) }}</div>
                <div class="mb-2"><strong>Used:</strong> {{ format_money($item->used_amount) }}</div>
                <div class="mb-2"><strong>Remaining:</strong> {{ format_money($item->remaining_amount) }}</div>
                <div class="mb-2"><strong>Consumption:</strong> {{ number_format($item->consumption_rate, 2) }}%</div>
                <div class="mb-2"><strong>Period:</strong> {{ format_date($item->start_date) }} to {{ format_date($item->end_date) }}</div>
                <div><strong>Notes:</strong><br>{{ $item->notes ?? '-' }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card soft-card">
            <div class="card-body">
                <h5 class="mb-3">Expenses</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Name</th><th>Date</th><th>Amount</th></tr></thead>
                        <tbody>
                        @forelse($item->expenses as $expense)
                            <tr>
                                <td>{{ $expense->expense_name }}</td>
                                <td>{{ format_date($expense->expense_date) }}</td>
                                <td>{{ format_money($expense->expense_amount) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">No expenses linked yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

