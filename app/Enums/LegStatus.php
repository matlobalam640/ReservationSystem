<?php

namespace App\Enums;

enum LegStatus: string
{
    case Planned = 'planned';
    case Available = 'available';
    case Boarding = 'boarding';
    case Departed = 'departed';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Planned => 'Planned',
            self::Available => 'Available',
            self::Boarding => 'Boarding',
            self::Departed => 'Departed',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Planned => 'gray',
            self::Available => 'success',
            self::Boarding => 'warning',
            self::Departed => 'info',
            self::Completed => 'primary',
            self::Cancelled => 'danger',
        };
    }
}
