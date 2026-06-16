<?php

namespace App\Enums;

enum CommissionStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Adjusted = 'adjusted';

    public function label(): string
    {
        return str($this->name)->headline()->toString();
    }
}
