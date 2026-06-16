<?php

namespace App\Enums;

enum ReturnLegResale: string
{
    case Public = 'public';
    case AgencyOnly = 'agency_only';
    case InternalOnly = 'internal_only';
    case Blocked = 'blocked';
    case CargoOnly = 'cargo_only';

    public function label(): string
    {
        return match ($this) {
            self::Public => 'Public Resale',
            self::AgencyOnly => 'Agency-Only Resale',
            self::InternalOnly => 'Internal Only',
            self::Blocked => 'Blocked (Private Return)',
            self::CargoOnly => 'Cargo Only',
        };
    }
}
