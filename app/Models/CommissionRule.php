<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionRule extends Model
{
    protected $fillable = [
        'name', 'agency_id', 'channel', 'split_type',
        'hero_amount', 'agency_amount', 'priority', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'hero_amount' => 'decimal:2',
            'agency_amount' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }
}
