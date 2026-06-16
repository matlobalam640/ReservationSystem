<?php

namespace App\Models;

use App\Enums\BillingType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Aircraft extends Model
{
    use LogsActivity;

    protected $table = 'aircraft';

    protected $fillable = [
        'operator_id',
        'tail_number',
        'aircraft_type',
        'seat_capacity',
        'max_weight_kg',
        'billing_type',
        'hourly_rate',
        'minimum_monthly_hours',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'billing_type' => BillingType::class,
            'seat_capacity' => 'integer',
            'max_weight_kg' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
            'minimum_monthly_hours' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    public function flightLegs(): HasMany
    {
        return $this->hasMany(FlightLeg::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }
}
