<?php

namespace App\Enums;

enum BillingType: string
{
    case Fixed = 'fixed';
    case Hourly = 'hourly';
    case Hybrid = 'hybrid';

    public function label(): string
    {
        return match ($this) {
            self::Fixed => 'Fixed Rate',
            self::Hourly => 'Hourly Rate',
            self::Hybrid => 'Hybrid',
        };
    }
}
