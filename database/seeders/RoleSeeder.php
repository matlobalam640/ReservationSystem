<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin',
            'reservations',
            'dispatch',
            'accounting',
            'check-in',
            'agency',
            'medical-dispatch',
        ];

        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }
    }
}
