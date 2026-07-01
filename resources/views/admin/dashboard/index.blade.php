@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Dashboard</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row g-3 mb-4">
    @php
        $statCards = [
            ['label' => 'Total Customers', 'value' => $cards['total_customers'], 'color' => 'primary'],
            ['label' => 'Total Suppliers', 'value' => $cards['total_suppliers'], 'color' => 'success'],
            ['label' => 'Total Products', 'value' => $cards['total_products'], 'color' => 'warning'],
            ['label' => 'Total Purchases', 'value' => format_money($cards['total_purchase_amount']), 'color' => 'danger'],
            ['label' => 'Total Sales', 'value' => format_money($cards['total_sales_amount']), 'color' => 'info'],
            ['label' => 'Total Expenses', 'value' => format_money($cards['total_expenses']), 'color' => 'secondary'],
            ['label' => 'Total Budgets', 'value' => $cards['total_budgets'], 'color' => 'dark'],
            ['label' => 'Pending Payments', 'value' => $cards['pending_payments'], 'color' => 'warning'],
            ['label' => 'Overdue Payments', 'value' => $cards['overdue_payments'], 'color' => 'danger'],
            ['label' => 'Total Profit', 'value' => format_money($cards['total_profit']), 'color' => 'success'],
        ];
    @endphp
    @foreach($statCards as $card)
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase">{{ $card['label'] }}</div>
                    <div class="display-6 fw-bold text-{{ $card['color'] }}">{{ $card['value'] }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card soft-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Monthly Sales Chart</h5>
                </div>
                <canvas id="salesChart" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card soft-card h-100">
            <div class="card-body">
                <h5 class="mb-3">Currency Rates</h5>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Currency</th>
                                <th>Rate</th>
                                <th>Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($currencyRates as $rate)
                                <tr>
                                    <td>{{ $rate['base_currency'] }} -> {{ $rate['target_currency'] }}</td>
                                    <td>{{ is_null($rate['rate']) ? 'N/A' : number_format((float) $rate['rate'], 4) }}</td>
                                    <td>{{ $rate['last_updated'] ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No exchange data available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card soft-card h-100">
            <div class="card-body">
                <h5 class="mb-3">Monthly Purchase Chart</h5>
                <canvas id="purchaseChart" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card soft-card h-100">
            <div class="card-body">
                <h5 class="mb-3">Monthly Expense Chart</h5>
                <canvas id="expenseChart" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card soft-card h-100">
            <div class="card-body">
                <h5 class="mb-3">Budget Consumption</h5>
                <canvas id="budgetChart" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card soft-card h-100">
            <div class="card-body">
                <h5 class="mb-3">Revenue vs Expense</h5>
                <canvas id="revenueExpenseChart" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card soft-card h-100">
            <div class="card-body">
                <h5 class="mb-3">Recent Sales</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($recentActivities['recent_sales'] as $sale)
                            <tr>
                                <td>{{ $sale->invoice_no }}</td>
                                <td>{{ $sale->customer?->customer_name }}</td>
                                <td>{{ format_money($sale->grand_total) }}</td>
                                <td><span class="badge bg-{{ status_badge_class($sale->payment_status instanceof \App\Enums\PaymentStatus ? $sale->payment_status->value : $sale->payment_status) }}">{{ $sale->payment_status instanceof \App\Enums\PaymentStatus ? $sale->payment_status->label() : $sale->payment_status }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No sales yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card soft-card h-100">
            <div class="card-body">
                <h5 class="mb-3">Recent Purchases</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Supplier</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($recentActivities['recent_purchases'] as $purchase)
                            <tr>
                                <td>{{ $purchase->invoice_no }}</td>
                                <td>{{ $purchase->supplier?->supplier_name }}</td>
                                <td>{{ format_money($purchase->grand_total) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">No purchases yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-12">
        <div class="card soft-card h-100">
            <div class="card-body">
                <h5 class="mb-3">Recent Expenses</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Expense</th>
                            <th>Budget</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($recentActivities['recent_expenses'] as $expense)
                            <tr>
                                <td>{{ $expense->expense_name }}</td>
                                <td>{{ $expense->budget?->budget_name }}</td>
                                <td>{{ format_money($expense->expense_amount) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">No expenses yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const chartLabels = @json($charts['labels']);
    const salesData = @json($charts['sales']);
    const purchaseData = @json($charts['purchases']);
    const expenseData = @json($charts['expenses']);
    const revenueData = @json($charts['revenue_vs_expense']['revenue']);
    const revenueExpenseData = @json($charts['revenue_vs_expense']['expense']);
    const budgetLabels = @json($budgetConsumption['labels']);
    const budgetAllocated = @json($budgetConsumption['allocated']);
    const budgetUsed = @json($budgetConsumption['used']);

    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Sales',
                data: salesData,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,.1)',
                tension: 0.35,
                fill: true
            }]
        }
    });

    new Chart(document.getElementById('purchaseChart'), {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Purchases',
                data: purchaseData,
                backgroundColor: '#ef4444'
            }]
        }
    });

    new Chart(document.getElementById('expenseChart'), {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Expenses',
                data: expenseData,
                backgroundColor: '#f59e0b'
            }]
        }
    });

    new Chart(document.getElementById('revenueExpenseChart'), {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Revenue',
                data: revenueData,
                borderColor: '#16a34a',
                tension: 0.35
            }, {
                label: 'Expense',
                data: revenueExpenseData,
                borderColor: '#dc2626',
                tension: 0.35
            }]
        }
    });

    new Chart(document.getElementById('budgetChart'), {
        type: 'bar',
        data: {
            labels: budgetLabels,
            datasets: [{
                label: 'Allocated',
                data: budgetAllocated,
                backgroundColor: '#0ea5e9'
            }, {
                label: 'Used',
                data: budgetUsed,
                backgroundColor: '#8b5cf6'
            }]
        }
    });
</script>
@endpush
@endsection
