<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroundHandler extends Model
{
    protected $fillable = ['name', 'default_rate', 'is_active'];

    protected function casts(): array
    {
        return ['default_rate' => 'decimal:2', 'is_active' => 'boolean'];
    }
}
