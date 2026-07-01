<?php

namespace App\Enums;

enum InvoiceType: string
{
    case Purchase = 'purchase';
    case Sales = 'sales';

    public function label(): string
    {
        return match ($this) {
            self::Purchase => 'Purchase',
            self::Sales => 'Sales',
        };
    }

    public function prefix(): string
    {
        return match ($this) {
            self::Purchase => 'PINV',
            self::Sales => 'SINV',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $case) => [$case->value => $case->label()])->all();
    }
}

