<?php

namespace App\Models;

use App\Enums\ContractType;
use App\Enums\BillingTimeBasis;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Operator extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'contact_email',
        'contact_phone',
        'contract_type',
        'billing_time_basis',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'contract_type' => ContractType::class,
            'billing_time_basis' => BillingTimeBasis::class,
            'is_active' => 'boolean',
        ];
    }

    public function aircraft(): HasMany
    {
        return $this->hasMany(Aircraft::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }
}
