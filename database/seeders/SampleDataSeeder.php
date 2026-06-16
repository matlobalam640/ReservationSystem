<?php

namespace Database\Seeders;

use App\Enums\AgencyPaymentModel;
use App\Enums\BillingTimeBasis;
use App\Enums\BillingType;
use App\Enums\ContractType;
use App\Enums\LegStatus;
use App\Enums\LegVisibility;
use App\Models\Agency;
use App\Models\Aircraft;
use App\Models\Flight;
use App\Models\FlightLeg;
use App\Models\Operator;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@hero.ops'],
            [
                'name' => 'HERO Admin',
                'password' => Hash::make('password'),
            ],
        );
        $admin->assignRole('admin');

        $operator = Operator::create([
            'name' => 'HERO Aviation',
            'contact_email' => 'ops@hero.ops',
            'contract_type' => ContractType::Hybrid,
            'billing_time_basis' => BillingTimeBasis::BlockTime,
            'is_active' => true,
        ]);

        $aircraft = Aircraft::create([
            'operator_id' => $operator->id,
            'tail_number' => 'N123HE',
            'aircraft_type' => 'Bell 407',
            'seat_capacity' => 6,
            'max_weight_kg' => 1200,
            'billing_type' => BillingType::Hourly,
            'hourly_rate' => 800,
            'minimum_monthly_hours' => 40,
            'is_active' => true,
        ]);

        Agency::create([
            'name' => 'Island Travel Co.',
            'code' => 'ITC',
            'contact_email' => 'bookings@islandtravel.test',
            'payment_model' => AgencyPaymentModel::AgencyCollects,
            'default_commission_rate' => 30,
            'is_active' => true,
        ]);

        $flight = Flight::create([
            'name' => 'HERO — Haiti & DR Shuttle',
            'notes' => 'Sample routes aligned with halofirm.com/fly.',
            'created_by' => $admin->id,
        ]);

        FlightLeg::create([
            'flight_id' => $flight->id,
            'aircraft_id' => $aircraft->id,
            'sort_order' => 0,
            'origin' => 'PAP',
            'destination' => 'SDQ',
            'departure_at' => now()->addDays(3)->setTime(9, 0),
            'visibility' => LegVisibility::Public,
            'status' => LegStatus::Available,
            'base_price' => 575,
            'notes' => 'Pétion-Ville to Santo Domingo',
        ]);

        FlightLeg::create([
            'flight_id' => $flight->id,
            'aircraft_id' => $aircraft->id,
            'sort_order' => 1,
            'origin' => 'SDQ',
            'destination' => 'PAP',
            'departure_at' => now()->addDays(3)->setTime(15, 0),
            'visibility' => LegVisibility::Public,
            'status' => LegStatus::Available,
            'base_price' => 575,
            'is_return_leg' => true,
            'notes' => 'Santo Domingo to Pétion-Ville',
        ]);

        FlightLeg::create([
            'flight_id' => $flight->id,
            'aircraft_id' => $aircraft->id,
            'sort_order' => 2,
            'origin' => 'PAP',
            'destination' => 'CAP',
            'departure_at' => now()->addDays(4)->setTime(8, 0),
            'visibility' => LegVisibility::Public,
            'status' => LegStatus::Available,
            'base_price' => 600,
            'notes' => 'Cap-Haïtien shuttle',
        ]);

        FlightLeg::create([
            'flight_id' => $flight->id,
            'aircraft_id' => $aircraft->id,
            'sort_order' => 3,
            'origin' => 'CAP',
            'destination' => 'PAP',
            'departure_at' => now()->addDays(4)->setTime(16, 0),
            'visibility' => LegVisibility::Public,
            'status' => LegStatus::Available,
            'base_price' => 600,
            'is_return_leg' => true,
            'notes' => 'Return from Cap-Haïtien',
        ]);
    }
}
