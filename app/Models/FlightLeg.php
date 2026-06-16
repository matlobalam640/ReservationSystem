<?php

namespace App\Models;

use App\Enums\LegStatus;
use App\Enums\LegVisibility;
use App\Enums\ReturnLegResale;
use App\Services\SeatGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FlightLeg extends Model
{
    use LogsActivity;

    protected $fillable = [
        'flight_id',
        'aircraft_id',
        'sort_order',
        'origin',
        'destination',
        'departure_at',
        'arrival_at',
        'visibility',
        'status',
        'return_leg_resale',
        'is_return_leg',
        'parent_leg_id',
        'base_price',
        'baggage_rules',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'departure_at' => 'datetime',
            'arrival_at' => 'datetime',
            'visibility' => LegVisibility::class,
            'status' => LegStatus::class,
            'return_leg_resale' => ReturnLegResale::class,
            'is_return_leg' => 'boolean',
            'base_price' => 'decimal:2',
            'baggage_rules' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (FlightLeg $leg): void {
            app(SeatGenerator::class)->generateForLeg($leg);
        });

        static::updated(function (FlightLeg $leg): void {
            if ($leg->wasChanged('aircraft_id')) {
                app(SeatGenerator::class)->regenerateForLeg($leg);
            }
        });
    }

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }

    public function aircraft(): BelongsTo
    {
        return $this->belongsTo(Aircraft::class);
    }

    public function parentLeg(): BelongsTo
    {
        return $this->belongsTo(FlightLeg::class, 'parent_leg_id');
    }

    public function returnLegs(): HasMany
    {
        return $this->hasMany(FlightLeg::class, 'parent_leg_id');
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function bookingPassengers(): HasManyThrough
    {
        return $this->hasManyThrough(BookingPassenger::class, Booking::class);
    }

    public function timeLog(): HasOne
    {
        return $this->hasOne(LegTimeLog::class);
    }

    public function routeLabel(): string
    {
        $origin = config("hero.locations.{$this->origin}", strtoupper($this->origin));
        $destination = config("hero.locations.{$this->destination}", strtoupper($this->destination));

        return $origin.' → '.$destination;
    }

    public function cloneAsNewLeg(int $sortOrder): self
    {
        $clone = $this->replicate(['parent_leg_id']);
        $clone->sort_order = $sortOrder;
        $clone->status = LegStatus::Planned;
        $clone->save();

        return $clone;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }
}
