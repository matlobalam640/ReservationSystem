<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedevacCase extends Model
{
    protected $fillable = [
        'flight_leg_id', 'patient_name', 'condition', 'vitals',
        'pickup_location', 'dropoff_location', 'category', 'notes',
    ];

    public function flightLeg(): BelongsTo
    {
        return $this->belongsTo(FlightLeg::class);
    }
}
