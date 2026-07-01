<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CurrencyExchangeService
{
    public function rate(string $baseCurrency, string $targetCurrency = 'INR'): array
    {
        $cacheMinutes = (int) config('services.currency_api.cache_minutes', 60);
        $cacheKey = sprintf('currency-rate:%s:%s', strtoupper($baseCurrency), strtoupper($targetCurrency));

        return Cache::remember($cacheKey, now()->addMinutes($cacheMinutes), function () use ($baseCurrency, $targetCurrency): array {
            $response = Http::baseUrl(rtrim((string) config('services.currency_api.base_url'), '/'))
                ->acceptJson()
                ->retry(2, 200)
                ->timeout(15)
                ->get('/latest', [
                    'from' => strtoupper($baseCurrency),
                    'to' => strtoupper($targetCurrency),
                ]);

            if (! $response->successful()) {
                throw new RuntimeException('Unable to fetch the current exchange rate.');
            }

            $payload = $response->json();
            $rate = data_get($payload, 'rates.'.strtoupper($targetCurrency));

            if ($rate === null) {
                throw new RuntimeException('Exchange rate response did not include the expected target currency.');
            }

            return [
                'currency' => strtoupper($baseCurrency).' to '.strtoupper($targetCurrency),
                'base_currency' => strtoupper($baseCurrency),
                'target_currency' => strtoupper($targetCurrency),
                'rate' => (float) $rate,
                'last_updated' => data_get($payload, 'date', now()->toDateString()),
            ];
        });
    }

    public function dashboardRates(array $currencies, string $targetCurrency = 'INR'): array
    {
        $rates = [];

        foreach ($currencies as $currency) {
            try {
                $rates[] = $this->rate($currency, $targetCurrency);
            } catch (\Throwable $throwable) {
                $rates[] = [
                    'currency' => strtoupper($currency).' to '.strtoupper($targetCurrency),
                    'base_currency' => strtoupper($currency),
                    'target_currency' => strtoupper($targetCurrency),
                    'rate' => null,
                    'last_updated' => null,
                    'error' => $throwable->getMessage(),
                ];
            }
        }

        return $rates;
    }
}

