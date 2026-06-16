<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReconciliationDiscrepancy extends Model
{
    protected $fillable = [
        'operator_invoice_id', 'type', 'internal_value', 'operator_value', 'description', 'resolved',
    ];

    protected function casts(): array
    {
        return [
            'internal_value' => 'decimal:2',
            'operator_value' => 'decimal:2',
            'resolved' => 'boolean',
        ];
    }

    public function operatorInvoice(): BelongsTo
    {
        return $this->belongsTo(OperatorInvoice::class);
    }
}
