<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case Check = 'check';
    case Wire = 'wire';
    case Card = 'card';
    case AgencyCollected = 'agency_collected';
    case CreditAccount = 'credit_account';

    public function label(): string
    {
        return match ($this) {
            self::Cash => 'Cash',
            self::Check => 'Check',
            self::Wire => 'Wire Transfer',
            self::Card => 'Credit Card',
            self::AgencyCollected => 'Agency Collected',
            self::CreditAccount => 'Credit Account',
        };
    }
}
