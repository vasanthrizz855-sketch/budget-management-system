<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Paid = 'paid';
    case Pending = 'pending';
    case Overdue = 'overdue';

    public function label(): string
    {
        return match ($this) {
            self::Paid => 'Paid',
            self::Pending => 'Pending',
            self::Overdue => 'Overdue',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $case) => [$case->value => $case->label()])->all();
    }
}

