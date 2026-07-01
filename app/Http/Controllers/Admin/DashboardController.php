<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CurrencyExchangeService;
use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService,
        private readonly CurrencyExchangeService $currencyExchangeService,
    ) {
    }

    public function index(): View
    {
        return view('admin.dashboard.index', [
            'cards' => $this->dashboardService->cards(),
            'charts' => $this->dashboardService->monthlySeries(),
            'budgetConsumption' => $this->dashboardService->budgetConsumption(),
            'recentActivities' => $this->dashboardService->recentActivities(),
            'currencyRates' => $this->currencyExchangeService->dashboardRates(
                config('services.currency_api.currencies', ['USD', 'EUR', 'GBP']),
                config('services.currency_api.default_target', 'INR')
            ),
        ]);
    }
}

