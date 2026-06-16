<?php

namespace App\Enums;

enum BookingChannel: string
{
    case Website = 'website';
    case Phone = 'phone';
    case Counter = 'counter';
    case Agency = 'agency';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Website => 'Website',
            self::Phone => 'Phone',
            self::Counter => 'Counter / Walk-in',
            self::Agency => 'Travel Agency',
            self::Admin => 'Admin',
        };
    }
}
