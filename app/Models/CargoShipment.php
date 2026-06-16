<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CargoShipment extends Model
{
    protected $fillable = [
        'flight_leg_id', 'client_name', 'weight_kg', 'origin', 'destination', 'invoice_amount', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'weight_kg' => 'decimal:2',
            'invoice_amount' => 'decimal:2',
        ];
    }

    public function flightLeg(): BelongsTo
    {
        return $this->belongsTo(FlightLeg::class);
    }
}
