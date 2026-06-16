<?php

namespace App\Enums;

enum AgencyPaymentModel: string
{
    case HeroCollects = 'hero_collects';
    case AgencyCollects = 'agency_collects';
    case Mixed = 'mixed';

    public function label(): string
    {
        return match ($this) {
            self::HeroCollects => 'HERO Collects → Pays Commission',
            self::AgencyCollects => 'Agency Collects → Remits Net',
            self::Mixed => 'Mixed Model',
        };
    }
}
