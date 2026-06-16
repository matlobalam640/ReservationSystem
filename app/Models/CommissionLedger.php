<?php

namespace App\Models;

use App\Enums\CommissionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommissionLedger extends Model
{
    protected $table = 'commission_ledger';

    protected $fillable = ['booking_id', 'agency_id', 'hero_amount', 'agency_amount', 'status', 'notes'];

    protected function casts(): array
    {
        return [
            'hero_amount' => 'decimal:2',
            'agency_amount' => 'decimal:2',
            'status' => CommissionStatus::class,
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }
}
