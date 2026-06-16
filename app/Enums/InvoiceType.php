<?php

namespace App\Enums;

enum InvoiceType: string
{
    case Passenger = 'passenger';
    case Agency = 'agency';
    case Charter = 'charter';
    case Medevac = 'medevac';
    case Cargo = 'cargo';
    case Monthly = 'monthly';

    public function label(): string
    {
        return str($this->name)->headline()->toString();
    }
}
