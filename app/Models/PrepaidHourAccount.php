<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrepaidHourAccount extends Model
{
    protected $fillable = [
        'client_name', 'hours_purchased', 'hours_used',
        'flight_hour_rate', 'ground_hour_rate', 'ferry_hour_rate', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'hours_purchased' => 'decimal:2',
            'hours_used' => 'decimal:2',
            'flight_hour_rate' => 'decimal:2',
            'ground_hour_rate' => 'decimal:2',
            'ferry_hour_rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function usageLogs(): HasMany
    {
        return $this->hasMany(HourUsageLog::class);
    }

    public function hoursRemaining(): float
    {
        return (float) $this->hours_purchased - (float) $this->hours_used;
    }
}
