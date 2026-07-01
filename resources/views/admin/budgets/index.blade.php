@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Budgets</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Budgets</li>
        </ol>
    </div>
    <a href="{{ route('admin.budgets.create') }}" class="btn btn-primary">Add Budget</a>
</div>

<div class="card soft-card">
    <div class="card-body">
        <form class="row g-2 mb-4" method="GET" action="{{ route('admin.budgets.index') }}">
            <div class="col-md-10">
                <input type="search" name="search" class="form-control" placeholder="Search budget..." value="{{ $search }}">
            </div>
            <div class="col-md-2 d-grid">
                <button class="btn btn-outline-primary">Search</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Allocated</th>
                    <th>Used</th>
                    <th>Remaining</th>
                    <th>Consumption</th>
                    <th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $budget)
                    <tr>
                        <td>{{ $budget->budget_name }}</td>
                        <td>{{ $budget->budget_type instanceof \App\Enums\BudgetType ? $budget->budget_type->label() : $budget->budget_type }}</td>
                        <td>{{ format_money($budget->allocated_amount) }}</td>
                        <td>{{ format_money($budget->used_amount) }}</td>
                        <td>{{ format_money($budget->remaining_amount) }}</td>
                        <td style="min-width: 180px;">
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: {{ min(100, $budget->consumption_rate) }}%">{{ number_format($budget->consumption_rate, 2) }}%</div>
                            </div>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.budgets.show', $budget) }}" class="btn btn-sm btn-outline-secondary">View</a>
                            <a href="{{ route('admin.budgets.edit', $budget) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('admin.budgets.destroy', $budget) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this budget?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-5 text-muted">No budgets found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $items->links() }}</div>
    </div>
</div>
@endsection

