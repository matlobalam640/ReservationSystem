<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HourUsageLog extends Model
{
    protected $fillable = ['prepaid_hour_account_id', 'flight_leg_id', 'usage_type', 'hours', 'notes'];

    protected function casts(): array
    {
        return ['hours' => 'decimal:2'];
    }

    public function prepaidHourAccount(): BelongsTo
    {
        return $this->belongsTo(PrepaidHourAccount::class);
    }

    public function flightLeg(): BelongsTo
    {
        return $this->belongsTo(FlightLeg::class);
    }
}
