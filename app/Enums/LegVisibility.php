<?php

namespace App\Enums;

enum LegVisibility: string
{
    case Public = 'public';
    case Agency = 'agency';
    case Internal = 'internal';
    case Private = 'private';
    case Medevac = 'medevac';
    case Cargo = 'cargo';

    public function label(): string
    {
        return match ($this) {
            self::Public => 'Public',
            self::Agency => 'Agency Only',
            self::Internal => 'Internal',
            self::Private => 'Private Charter',
            self::Medevac => 'Medevac (Hidden)',
            self::Cargo => 'Cargo (Hidden)',
        };
    }

    public function isHidden(): bool
    {
        return in_array($this, [self::Medevac, self::Cargo, self::Internal], true);
    }
}
