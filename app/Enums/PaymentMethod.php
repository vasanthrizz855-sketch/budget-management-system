<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case Upi = 'upi';
    case BankTransfer = 'bank_transfer';
    case CreditCard = 'credit_card';
    case DebitCard = 'debit_card';

    public function label(): string
    {
        return match ($this) {
            self::Cash => 'Cash',
            self::Upi => 'UPI',
            self::BankTransfer => 'Bank Transfer',
            self::CreditCard => 'Credit Card',
            self::DebitCard => 'Debit Card',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $case) => [$case->value => $case->label()])->all();
    }
}

