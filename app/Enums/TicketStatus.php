<?php

namespace App\Enums;

enum TicketStatus: string
{
    case Confirmed = 'confirmed';
    case CheckedIn = 'checked_in';
    case Cancelled = 'cancelled';
    case NoShow = 'no_show';

    public function label(): string
    {
        return match ($this) {
            self::Confirmed => 'Confirmed',
            self::CheckedIn => 'Checked In',
            self::Cancelled => 'Cancelled',
            self::NoShow => 'No Show',
        };
    }
}
