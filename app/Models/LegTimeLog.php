<?php

namespace App\Models;

use App\Enums\BillingType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegTimeLog extends Model
{
    protected $fillable = [
        'flight_leg_id',
        'engine_start_at',
        'takeoff_at',
        'landing_at',
        'engine_shutdown_at',
        'flight_time_hours',
        'block_time_hours',
        'billing_method',
        'calculated_cost',
        'fixed_route_cost',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'engine_start_at' => 'datetime',
            'takeoff_at' => 'datetime',
            'landing_at' => 'datetime',
            'engine_shutdown_at' => 'datetime',
            'flight_time_hours' => 'decimal:2',
            'block_time_hours' => 'decimal:2',
            'billing_method' => BillingType::class,
            'calculated_cost' => 'decimal:2',
            'fixed_route_cost' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (LegTimeLog $log): void {
            if ($log->takeoff_at && $log->landing_at) {
                $log->flight_time_hours = round($log->takeoff_at->diffInMinutes($log->landing_at) / 60, 2);
            }
            if ($log->engine_start_at && $log->engine_shutdown_at) {
                $log->block_time_hours = round($log->engine_start_at->diffInMinutes($log->engine_shutdown_at) / 60, 2);
            }
            if ($log->flight_leg_id) {
                $leg = FlightLeg::with('aircraft')->find($log->flight_leg_id);
                if ($leg?->aircraft) {
                    if ($leg->aircraft->billing_type === BillingType::Fixed) {
                        $log->calculated_cost = (float) ($log->fixed_route_cost ?? $leg->base_price ?? 0);
                    } else {
                        $hours = (float) ($log->block_time_hours ?? $log->flight_time_hours ?? 0);
                        $log->calculated_cost = round($hours * (float) ($leg->aircraft->hourly_rate ?? 0), 2);
                    }
                }
            }
        });
    }

    public function flightLeg(): BelongsTo
    {
        return $this->belongsTo(FlightLeg::class);
    }
}
