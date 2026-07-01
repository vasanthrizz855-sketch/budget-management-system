<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn (self $case) => [$case->value => $case->label()])->all();
    }
}

