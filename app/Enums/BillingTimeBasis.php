<?php

namespace App\Enums;

enum BillingTimeBasis: string
{
    case FlightTime = 'flight_time';
    case BlockTime = 'block_time';

    public function label(): string
    {
        return match ($this) {
            self::FlightTime => 'Flight Time (takeoff → landing)',
            self::BlockTime => 'Block Time (engine start → shutdown)',
        };
    }
}
