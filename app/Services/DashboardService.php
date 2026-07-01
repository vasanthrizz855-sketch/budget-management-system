<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Budget;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Product;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use App\Models\Supplier;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardService
{
    public function cards(): array
    {
        $purchaseTotal = (float) PurchaseInvoice::query()->sum('grand_total');
        $salesTotal = (float) SalesInvoice::query()->sum('grand_total');
        $expenseTotal = (float) Expense::query()->sum('expense_amount');

        return [
            'total_customers' => Customer::query()->count(),
            'total_suppliers' => Supplier::query()->count(),
            'total_products' => Product::query()->count(),
            'total_purchase_amount' => round($purchaseTotal, 2),
            'total_sales_amount' => round($salesTotal, 2),
            'total_expenses' => round($expenseTotal, 2),
            'total_budgets' => Budget::query()->count(),
            'pending_payments' => $this->pendingPaymentsCount(),
            'overdue_payments' => $this->overduePaymentsCount(),
            'total_profit' => round($salesTotal - $purchaseTotal - $expenseTotal, 2),
        ];
    }

    public function monthlySeries(int $months = 12): array
    {
        $period = CarbonPeriod::create(now()->subMonths($months - 1)->startOfMonth(), '1 month', now()->startOfMonth());
        $labels = [];
        $sales = [];
        $purchases = [];
        $expenses = [];

        foreach ($period as $date) {
            $monthStart = Carbon::parse($date)->startOfMonth();
            $monthEnd = Carbon::parse($date)->endOfMonth();
            $labels[] = $monthStart->format('M Y');

            $sales[] = round((float) SalesInvoice::query()
                ->whereBetween('invoice_date', [$monthStart, $monthEnd])
                ->sum('grand_total'), 2);

            $purchases[] = round((float) PurchaseInvoice::query()
                ->whereBetween('invoice_date', [$monthStart, $monthEnd])
                ->sum('grand_total'), 2);

            $expenses[] = round((float) Expense::query()
                ->whereBetween('expense_date', [$monthStart, $monthEnd])
                ->sum('expense_amount'), 2);
        }

        return [
            'labels' => $labels,
            'sales' => $sales,
            'purchases' => $purchases,
            'expenses' => $expenses,
            'revenue_vs_expense' => [
                'revenue' => $sales,
                'expense' => array_map(static fn ($purchase, $expense) => round($purchase + $expense, 2), $purchases, $expenses),
            ],
        ];
    }

    public function budgetConsumption(): array
    {
        $budgets = Budget::query()
            ->withSum('expenses as used_amount', 'expense_amount')
            ->latest()
            ->take(8)
            ->get();

        return [
            'labels' => $budgets->pluck('budget_name')->values()->all(),
            'allocated' => $budgets->pluck('allocated_amount')->map(fn ($value) => (float) $value)->values()->all(),
            'used' => $budgets->pluck('used_amount')->map(fn ($value) => round((float) ($value ?? 0), 2))->values()->all(),
        ];
    }

    public function recentActivities(): array
    {
        return [
            'recent_sales' => SalesInvoice::query()->with('customer')->latest()->take(5)->get(),
            'recent_purchases' => PurchaseInvoice::query()->with('supplier')->latest()->take(5)->get(),
            'recent_expenses' => Expense::query()->with('budget')->latest()->take(5)->get(),
        ];
    }

    private function pendingPaymentsCount(): int
    {
        return PurchaseInvoice::query()->where('payment_status', PaymentStatus::Pending->value)->count()
            + SalesInvoice::query()->where('payment_status', PaymentStatus::Pending->value)->count();
    }

    private function overduePaymentsCount(): int
    {
        return PurchaseInvoice::query()->where('payment_status', PaymentStatus::Overdue->value)->count()
            + SalesInvoice::query()->where('payment_status', PaymentStatus::Overdue->value)->count();
    }
}
