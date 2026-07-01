@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Expenses</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Expenses</li>
        </ol>
    </div>
    <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary">Add Expense</a>
</div>

<div class="card soft-card">
    <div class="card-body">
        <form class="row g-2 mb-4" method="GET" action="{{ route('admin.expenses.index') }}">
            <div class="col-md-10">
                <input type="search" name="search" class="form-control" placeholder="Search expense..." value="{{ $search }}">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-primary">Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Name</th><th>Budget</th><th>Date</th><th>Amount</th><th class="text-end">Actions</th></tr></thead>
                <tbody>
                @forelse($items as $expense)
                    <tr>
                        <td>{{ $expense->expense_name }}</td>
                        <td>{{ $expense->budget?->budget_name ?? '-' }}</td>
                        <td>{{ format_date($expense->expense_date) }}</td>
                        <td>{{ format_money($expense->expense_amount) }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.expenses.show', $expense) }}" class="btn btn-sm btn-outline-secondary">View</a>
                            <a href="{{ route('admin.expenses.edit', $expense) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.expenses.destroy', $expense) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this expense?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted">No expenses found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </div>
</div>
@endsection

