<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditAccountEntry extends Model
{
    protected $fillable = ['credit_account_id', 'booking_id', 'type', 'amount', 'description'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2'];
    }

    public function creditAccount(): BelongsTo
    {
        return $this->belongsTo(CreditAccount::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
