<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffAssignment extends Model
{
    protected $fillable = ['staff_member_id', 'flight_leg_id', 'assignment_date', 'calculated_pay'];

    protected function casts(): array
    {
        return [
            'assignment_date' => 'date',
            'calculated_pay' => 'decimal:2',
        ];
    }

    public function staffMember(): BelongsTo
    {
        return $this->belongsTo(StaffMember::class);
    }

    public function flightLeg(): BelongsTo
    {
        return $this->belongsTo(FlightLeg::class);
    }
}
