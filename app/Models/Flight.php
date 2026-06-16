<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Flight extends Model
{
    use LogsActivity;

    protected $fillable = [
        'reference_number',
        'name',
        'notes',
        'created_by',
    ];

    protected static function booted(): void
    {
        static::creating(function (Flight $flight): void {
            if (empty($flight->reference_number)) {
                $flight->reference_number = 'FLT-'.strtoupper(Str::random(8));
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function legs(): HasMany
    {
        return $this->hasMany(FlightLeg::class)->orderBy('sort_order');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }
}
