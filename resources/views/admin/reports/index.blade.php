@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Reports</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Reports</li>
        </ol>
    </div>
</div>

<div class="card soft-card mb-4">
    <div class="card-body">
        <form class="row g-3 align-items-end" method="GET" action="{{ route('admin.reports.index') }}">
            <div class="col-md-3">
                <label class="form-label">Report Type</label>
                <select name="type" class="form-select">
                    <option value="sales" @selected($selectedType === 'Sales Report')>Sales Report</option>
                    <option value="purchase" @selected($selectedType === 'Purchase Report')>Purchase Report</option>
                    <option value="expense" @selected($selectedType === 'Expense Report')>Expense Report</option>
                    <option value="budget" @selected($selectedType === 'Budget Report')>Budget Report</option>
                    <option value="payment" @selected($selectedType === 'Payment Report')>Payment Report</option>
                    <option value="profit-loss" @selected($selectedType === 'Profit & Loss Report')>Profit & Loss Report</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Range</label>
                <select name="range" class="form-select">
                    <option value="today" @selected(request('range') === 'today')>Today</option>
                    <option value="week" @selected(request('range') === 'week')>This Week</option>
                    <option value="month" @selected(request('range', 'month') === 'month')>This Month</option>
                    <option value="custom" @selected(request('range') === 'custom')>Custom</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">From</label>
                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">To</label>
                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="col-md-3 d-grid">
                <button class="btn btn-primary">Generate Report</button>
            </div>
        </form>
    </div>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
    @php($reportQuery = http_build_query(request()->only('range', 'from', 'to')))
    @php($reportSlug = match($selectedType) {
        'Purchase Report' => 'purchase',
        'Expense Report' => 'expense',
        'Budget Report' => 'budget',
        'Payment Report' => 'payment',
        'Profit & Loss Report' => 'profit-loss',
        default => 'sales',
    })
    <a href="{{ route('admin.reports.show', 'sales') . ($reportQuery ? '?'.$reportQuery : '') }}" class="btn btn-outline-primary">Sales</a>
    <a href="{{ route('admin.reports.show', 'purchase') . ($reportQuery ? '?'.$reportQuery : '') }}" class="btn btn-outline-primary">Purchase</a>
    <a href="{{ route('admin.reports.show', 'expense') . ($reportQuery ? '?'.$reportQuery : '') }}" class="btn btn-outline-primary">Expense</a>
    <a href="{{ route('admin.reports.show', 'budget') . ($reportQuery ? '?'.$reportQuery : '') }}" class="btn btn-outline-primary">Budget</a>
    <a href="{{ route('admin.reports.show', 'payment') . ($reportQuery ? '?'.$reportQuery : '') }}" class="btn btn-outline-primary">Payment</a>
    <a href="{{ route('admin.reports.show', 'profit-loss') . ($reportQuery ? '?'.$reportQuery : '') }}" class="btn btn-outline-primary">Profit & Loss</a>
    <a href="{{ route('admin.reports.export.pdf', $reportSlug) . ($reportQuery ? '?'.$reportQuery : '') }}" class="btn btn-outline-dark">Export PDF</a>
    <a href="{{ route('admin.reports.export.excel', $reportSlug) . ($reportQuery ? '?'.$reportQuery : '') }}" class="btn btn-outline-success">Export Excel</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted small">Total Records</div>
                <div class="display-6 fw-bold">{{ $summary['count'] ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted small">Total Value</div>
                <div class="display-6 fw-bold">{{ format_money($summary['total'] ?? 0) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="text-muted small">Current Report</div>
                <div class="h4 mb-0">{{ $selectedType }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card soft-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr>
                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @forelse($rows as $row)
                    <tr>
                        @foreach(array_values($row) as $value)
                            <td>{{ is_numeric($value) ? number_format((float) $value, 2) : $value }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr><td colspan="{{ count($headers) }}" class="text-center py-5 text-muted">No records found for the selected filters.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
