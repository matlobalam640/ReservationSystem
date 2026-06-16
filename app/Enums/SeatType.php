<?php

namespace App\Enums;

enum SeatType: string
{
    case Passenger = 'passenger';
    case Crew = 'crew';
    case Medical = 'medical';
    case Blocked = 'blocked';
    case Vip = 'vip';
    case WeightRestricted = 'weight_restricted';

    public function label(): string
    {
        return match ($this) {
            self::Passenger => 'Passenger',
            self::Crew => 'Crew',
            self::Medical => 'Medical Team',
            self::Blocked => 'Blocked',
            self::Vip => 'VIP Reserved',
            self::WeightRestricted => 'Weight Restricted',
        };
    }

    public function isBookable(): bool
    {
        return in_array($this, [self::Passenger, self::Vip, self::WeightRestricted], true);
    }
}
