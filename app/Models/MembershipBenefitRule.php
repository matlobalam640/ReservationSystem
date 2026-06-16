<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipBenefitRule extends Model
{
    protected $fillable = ['plan_level', 'benefit_type', 'discount_percent', 'fixed_discount', 'is_active'];

    protected function casts(): array
    {
        return [
            'discount_percent' => 'decimal:2',
            'fixed_discount' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}
