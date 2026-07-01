<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

if (! function_exists('format_money')) {
    function format_money(float|int|string|null $amount, string $currency = 'INR'): string
    {
        $amount = is_numeric($amount) ? (float) $amount : 0.0;

        return $currency.' '.number_format($amount, 2, '.', ',');
    }
}

if (! function_exists('format_date')) {
    function format_date(string|\DateTimeInterface|null $value, string $format = 'd M, Y'): string
    {
        return $value ? Carbon::parse($value)->format($format) : '-';
    }
}

if (! function_exists('status_badge_class')) {
    function status_badge_class(string|null $value): string
    {
        return match (Str::lower((string) $value)) {
            'active', 'paid', 'completed' => 'success',
            'pending' => 'warning',
            'overdue', 'inactive', 'failed', 'cancelled' => 'danger',
            default => 'secondary',
        };
    }
}

if (! function_exists('nav_active')) {
    function nav_active(string|array $routes): string
    {
        return request()->routeIs($routes) ? 'active' : '';
    }
}

if (! function_exists('generate_entity_code')) {
    function generate_entity_code(string $prefix, string $table, int $digits = 4): string
    {
        $sequence = ((int) DB::table($table)->max('id')) + 1;

        return sprintf('%s-%s-%0'.$digits.'d', strtoupper($prefix), now()->format('ymd'), $sequence);
    }
}

if (! function_exists('generate_invoice_no')) {
    function generate_invoice_no(string $prefix, string $table, int $digits = 5): string
    {
        return generate_entity_code($prefix, $table, $digits);
    }
}

if (! function_exists('percentage_value')) {
    function percentage_value(float|int|null $amount, float|int|null $percentage): float
    {
        return round(((float) $amount * (float) $percentage) / 100, 2);
    }
}
