<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingPassenger extends Model
{
    protected $fillable = [
        'booking_id',
        'passenger_id',
        'seat_id',
        'weight_kg',
        'baggage_weight_kg',
        'ticket_status',
        'payment_method',
        'amount',
        'commission_amount',
        'actual_weight_kg',
        'actual_baggage_weight_kg',
        'checked_in_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'ticket_status' => TicketStatus::class,
            'weight_kg' => 'decimal:2',
            'baggage_weight_kg' => 'decimal:2',
            'actual_weight_kg' => 'decimal:2',
            'actual_baggage_weight_kg' => 'decimal:2',
            'checked_in_at' => 'datetime',
            'amount' => 'decimal:2',
            'commission_amount' => 'decimal:2',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(Passenger::class);
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }
}
