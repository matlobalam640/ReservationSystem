<?php

namespace App\Enums;

enum ContractType: string
{
    case FixedRoute = 'fixed_route';
    case Hourly = 'hourly';
    case Hybrid = 'hybrid';

    public function label(): string
    {
        return match ($this) {
            self::FixedRoute => 'Fixed Route Pricing',
            self::Hourly => 'Hourly Billing',
            self::Hybrid => 'Hybrid',
        };
    }
}
