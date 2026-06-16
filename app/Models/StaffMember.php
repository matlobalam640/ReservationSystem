<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StaffMember extends Model
{
    protected $fillable = [
        'name', 'role', 'per_flight_rate', 'per_leg_rate', 'per_day_rate', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'per_flight_rate' => 'decimal:2',
            'per_leg_rate' => 'decimal:2',
            'per_day_rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(StaffAssignment::class);
    }
}
