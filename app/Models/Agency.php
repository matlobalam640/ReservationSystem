<?php

namespace App\Models;

use App\Enums\AgencyPaymentModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Agency extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'code',
        'contact_email',
        'contact_phone',
        'payment_model',
        'default_commission_rate',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'payment_model' => AgencyPaymentModel::class,
            'default_commission_rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }
}
