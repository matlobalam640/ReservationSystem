<?php

namespace App\Models;

use App\Enums\LegVisibility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AddOn extends Model
{
    protected $fillable = ['name', 'code', 'price', 'weight_kg', 'visibility', 'is_active'];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'weight_kg' => 'decimal:2',
            'visibility' => LegVisibility::class,
            'is_active' => 'boolean',
        ];
    }

    public function bookingAddOns(): HasMany
    {
        return $this->hasMany(BookingAddOn::class);
    }
}
