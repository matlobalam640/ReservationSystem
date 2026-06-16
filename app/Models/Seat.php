<?php

namespace App\Models;

use App\Enums\SeatType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Seat extends Model
{
    protected $fillable = [
        'flight_leg_id',
        'seat_number',
        'seat_type',
        'is_available',
        'max_weight_kg',
    ];

    protected function casts(): array
    {
        return [
            'seat_type' => SeatType::class,
            'is_available' => 'boolean',
            'max_weight_kg' => 'decimal:2',
        ];
    }

    public function flightLeg(): BelongsTo
    {
        return $this->belongsTo(FlightLeg::class);
    }

    public function bookingPassenger(): HasOne
    {
        return $this->hasOne(BookingPassenger::class);
    }
}
