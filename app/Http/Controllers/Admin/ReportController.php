<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BudgetType;
use App\Enums\InvoiceType;
use App\Exports\GenericReportExport;
use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        [$type, $rows, $summary, $headers] = $this->buildReport($request->get('type', 'sales'), $request);

        return view('admin.reports.index', [
            'selectedType' => $type,
            'rows' => $rows,
            'summary' => $summary,
            'headers' => $headers,
            'range' => $this->rangeOptions($request),
        ]);
    }

    public function show(string $type, Request $request): View
    {
        [$reportType, $rows, $summary, $headers] = $this->buildReport($type, $request);

        return view('admin.reports.index', [
            'selectedType' => $reportType,
            'rows' => $rows,
            'summary' => $summary,
            'headers' => $headers,
            'range' => $this->rangeOptions($request),
        ]);
    }

    public function exportPdf(string $type, Request $request)
    {
        [$reportType, $rows, $summary, $headers] = $this->buildReport($type, $request);

        return Pdf::loadView('admin.reports.pdf', [
            'reportType' => $reportType,
            'rows' => $rows,
            'summary' => $summary,
            'headers' => $headers,
        ])->download(str_replace(' ', '-', $reportType).'-report.pdf');
    }

    public function exportExcel(string $type, Request $request)
    {
        [$reportType, $rows, $summary, $headers] = $this->buildReport($type, $request);

        return Excel::download(
            new GenericReportExport($reportType, $headers, $rows, $summary),
            str_replace(' ', '-', $reportType).'-report.xlsx'
        );
    }

    private function buildReport(string $type, Request $request): array
    {
        $type = strtolower($type);
        $range = $this->dateRange($request);

        return match ($type) {
            'purchase' => $this->purchaseReport($range),
            'expense' => $this->expenseReport($range),
            'budget' => $this->budgetReport($range),
            'payment' => $this->paymentReport($range),
            'profit-loss', 'profit' => $this->profitLossReport($range),
            default => $this->salesReport($range),
        };
    }

    private function salesReport(array $range): array
    {
        $query = SalesInvoice::query()->with('customer')->whereBetween('invoice_date', $range);

        $rows = $query->get()->map(function (SalesInvoice $invoice): array {
            return [
                'invoice_no' => $invoice->invoice_no,
                'party' => $invoice->customer?->customer_name,
                'date' => $invoice->invoice_date?->format('Y-m-d'),
                'subtotal' => $invoice->subtotal,
                'tax_amount' => $invoice->tax_amount,
                'discount_amount' => $invoice->discount_amount,
                'grand_total' => $invoice->grand_total,
                'payment_status' => $invoice->payment_status instanceof \App\Enums\PaymentStatus ? $invoice->payment_status->label() : $invoice->payment_status,
            ];
        })->all();

        return [
            'Sales Report',
            $rows,
            [
                'total' => round($query->sum('grand_total'), 2),
                'count' => $query->count(),
            ],
            ['Invoice No', 'Customer', 'Date', 'Subtotal', 'Tax', 'Discount', 'Grand Total', 'Status'],
        ];
    }

    private function purchaseReport(array $range): array
    {
        $query = PurchaseInvoice::query()->with('supplier')->whereBetween('invoice_date', $range);

        $rows = $query->get()->map(function (PurchaseInvoice $invoice): array {
            return [
                'invoice_no' => $invoice->invoice_no,
                'party' => $invoice->supplier?->supplier_name,
                'date' => $invoice->invoice_date?->format('Y-m-d'),
                'subtotal' => $invoice->subtotal,
                'tax_amount' => $invoice->tax_amount,
                'discount_amount' => $invoice->discount_amount,
                'grand_total' => $invoice->grand_total,
                'payment_status' => $invoice->payment_status instanceof \App\Enums\PaymentStatus ? $invoice->payment_status->label() : $invoice->payment_status,
            ];
        })->all();

        return [
            'Purchase Report',
            $rows,
            [
                'total' => round($query->sum('grand_total'), 2),
                'count' => $query->count(),
            ],
            ['Invoice No', 'Supplier', 'Date', 'Subtotal', 'Tax', 'Discount', 'Grand Total', 'Status'],
        ];
    }

    private function expenseReport(array $range): array
    {
        $query = Expense::query()->with('budget')->whereBetween('expense_date', $range);

        $rows = $query->get()->map(fn (Expense $expense): array => [
            'expense_name' => $expense->expense_name,
            'budget' => $expense->budget?->budget_name,
            'date' => $expense->expense_date?->format('Y-m-d'),
            'amount' => $expense->expense_amount,
            'description' => $expense->description,
        ])->all();

        return [
            'Expense Report',
            $rows,
            [
                'total' => round($query->sum('expense_amount'), 2),
                'count' => $query->count(),
            ],
            ['Expense', 'Budget', 'Date', 'Amount', 'Description'],
        ];
    }

    private function budgetReport(array $range): array
    {
        $budgets = Budget::query()->withSum('expenses as used_amount', 'expense_amount')->where(function ($query) use ($range): void {
            $query->whereBetween('start_date', $range)->orWhereBetween('end_date', $range);
        })->get();

        $rows = $budgets->map(fn (Budget $budget): array => [
            'budget_name' => $budget->budget_name,
            'budget_type' => $budget->budget_type instanceof BudgetType ? $budget->budget_type->label() : $budget->budget_type,
            'allocated_amount' => $budget->allocated_amount,
            'used_amount' => round((float) ($budget->used_amount ?? 0), 2),
            'remaining_amount' => round((float) $budget->allocated_amount - (float) ($budget->used_amount ?? 0), 2),
        ])->all();

        return [
            'Budget Report',
            $rows,
            [
                'total' => round($budgets->sum('allocated_amount'), 2),
                'count' => $budgets->count(),
            ],
            ['Budget', 'Type', 'Allocated', 'Used', 'Remaining'],
        ];
    }

    private function paymentReport(array $range): array
    {
        $query = Payment::query()->whereBetween('payment_date', $range);

        $rows = $query->get()->map(fn (Payment $payment): array => [
            'invoice_type' => $payment->invoice_type instanceof InvoiceType ? $payment->invoice_type->label() : $payment->invoice_type,
            'invoice_reference' => $payment->invoice_reference,
            'payment_method' => $payment->payment_method?->label() ?? $payment->payment_method,
            'date' => $payment->payment_date?->format('Y-m-d'),
            'amount' => $payment->amount,
            'remarks' => $payment->remarks,
        ])->all();

        return [
            'Payment Report',
            $rows,
            [
                'total' => round($query->sum('amount'), 2),
                'count' => $query->count(),
            ],
            ['Invoice Type', 'Reference', 'Method', 'Date', 'Amount', 'Remarks'],
        ];
    }

    private function profitLossReport(array $range): array
    {
        $sales = (float) SalesInvoice::query()->whereBetween('invoice_date', $range)->sum('grand_total');
        $purchases = (float) PurchaseInvoice::query()->whereBetween('invoice_date', $range)->sum('grand_total');
        $expenses = (float) Expense::query()->whereBetween('expense_date', $range)->sum('expense_amount');

        $rows = [
            ['label' => 'Total Sales', 'amount' => $sales],
            ['label' => 'Total Purchases', 'amount' => $purchases],
            ['label' => 'Total Expenses', 'amount' => $expenses],
            ['label' => 'Net Profit', 'amount' => round($sales - $purchases - $expenses, 2)],
        ];

        return [
            'Profit & Loss Report',
            $rows,
            [
                'total' => round($sales - $purchases - $expenses, 2),
                'count' => count($rows),
            ],
            ['Label', 'Amount'],
        ];
    }

    private function dateRange(Request $request): array
    {
        $range = strtolower((string) $request->get('range', 'month'));

        return match ($range) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'week' => [now()->startOfWeek(), now()->endOfWeek()],
            'custom' => [
                $request->filled('from') ? Carbon::parse((string) $request->input('from')) : now()->startOfMonth(),
                $request->filled('to') ? Carbon::parse((string) $request->input('to')) : now(),
            ],
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };
    }

    private function rangeOptions(Request $request): array
    {
        return [
            'today' => $request->get('range') === 'today',
            'week' => $request->get('range') === 'week',
            'month' => $request->get('range', 'month') === 'month',
            'custom' => $request->get('range') === 'custom',
        ];
    }
}
