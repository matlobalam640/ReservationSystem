<?php

namespace Database\Seeders;

use App\Enums\LegVisibility;
use App\Models\AddOn;
use App\Models\Agency;
use App\Models\CommissionRule;
use App\Models\GroundHandler;
use App\Models\HeroMembership;
use App\Models\MembershipBenefitRule;
use App\Models\Passenger;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ExtendedModuleSeeder extends Seeder
{
    public function run(): void
    {
        AddOn::query()->firstOrCreate(
            ['code' => 'CARRYON'],
            ['name' => 'Carry-On Bag', 'price' => 0, 'weight_kg' => 7, 'visibility' => LegVisibility::Public],
        );
        AddOn::query()->firstOrCreate(
            ['code' => 'SUITCASE'],
            ['name' => 'Checked Suitcase', 'price' => 35, 'weight_kg' => 23, 'visibility' => LegVisibility::Public],
        );
        AddOn::query()->firstOrCreate(
            ['code' => 'OVERSIZE'],
            ['name' => 'Oversize Baggage', 'price' => 75, 'weight_kg' => 32, 'visibility' => LegVisibility::Public],
        );
        AddOn::query()->firstOrCreate(
            ['code' => 'VIP'],
            ['name' => 'VIP Handling', 'price' => 150, 'weight_kg' => 0, 'visibility' => LegVisibility::Internal],
        );

        $agency = Agency::first();
        if ($agency) {
            CommissionRule::create([
                'name' => 'Default Agency Split',
                'agency_id' => $agency->id,
                'channel' => 'agency',
                'hero_amount' => 30,
                'agency_amount' => 30,
                'priority' => 10,
            ]);
        }

        GroundHandler::create(['name' => 'JCI Ground Services', 'default_rate' => 250]);

        $passenger = Passenger::create([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'john@example.com',
            'phone' => '+509-555-0100',
        ]);

        HeroMembership::create([
            'passenger_id' => $passenger->id,
            'member_code' => 'HERO-VIP-001',
            'plan_level' => 'VIP',
            'member_name' => 'John Smith',
            'email' => 'john@example.com',
            'expires_at' => now()->addYear(),
            'is_active' => true,
        ]);

        MembershipBenefitRule::create([
            'plan_level' => 'VIP',
            'benefit_type' => 'baggage_discount',
            'discount_percent' => 50,
        ]);

        $agencyUser = User::firstOrCreate(
            ['email' => 'agency@hero.ops'],
            ['name' => 'Agency User', 'password' => Hash::make('password')],
        );
        $agencyUser->assignRole('agency');
        if ($agency) {
            $agencyUser->update(['agency_id' => $agency->id]);
        }

        $customerUser = User::firstOrCreate(
            ['email' => 'john@example.com'],
            ['name' => 'John Smith', 'password' => Hash::make('password')],
        );
        $customerUser->update(['passenger_id' => $passenger->id]);

        $this->call(ReconciliationSampleSeeder::class);
    }
}
