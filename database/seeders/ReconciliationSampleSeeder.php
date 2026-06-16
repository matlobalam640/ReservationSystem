<?php

namespace Database\Seeders;

use App\Enums\LegStatus;
use App\Enums\LegVisibility;
use App\Models\Aircraft;
use App\Models\Flight;
use App\Models\FlightLeg;
use App\Models\LegTimeLog;
use App\Models\Operator;
use App\Models\OperatorInvoice;
use App\Models\ReconciliationDiscrepancy;
use Illuminate\Database\Seeder;

class ReconciliationSampleSeeder extends Seeder
{
    public function run(): void
    {
        if (OperatorInvoice::query()->where('invoice_reference', 'OP-INV-2026-001')->exists()) {
            return;
        }

        $operator = Operator::query()->first();
        $aircraft = Aircraft::query()->first();
        $flight = Flight::query()->first();

        if (! $operator || ! $aircraft || ! $flight) {
            return;
        }

        $completedLeg = FlightLeg::create([
            'flight_id' => $flight->id,
            'aircraft_id' => $aircraft->id,
            'sort_order' => 10,
            'origin' => 'PAP',
            'destination' => 'SDQ',
            'departure_at' => now()->subDays(2)->setTime(9, 0),
            'arrival_at' => now()->subDays(2)->setTime(10, 30),
            'visibility' => LegVisibility::Internal,
            'status' => LegStatus::Completed,
            'base_price' => 575,
            'notes' => 'Completed leg — reconciliation sample',
        ]);

        LegTimeLog::create([
            'flight_leg_id' => $completedLeg->id,
            'engine_start_at' => now()->subDays(2)->setTime(8, 45),
            'takeoff_at' => now()->subDays(2)->setTime(9, 5),
            'landing_at' => now()->subDays(2)->setTime(10, 20),
            'engine_shutdown_at' => now()->subDays(2)->setTime(10, 35),
            'flight_time_hours' => 1.25,
            'block_time_hours' => 1.83,
            'notes' => 'Sample time log for reconciliation dashboard',
        ]);

        $operatorInvoice = OperatorInvoice::create([
            'operator_id' => $operator->id,
            'invoice_reference' => 'OP-INV-2026-001',
            'period_start' => now()->subMonth()->startOfMonth(),
            'period_end' => now()->subMonth()->endOfMonth(),
            'total_hours' => 42.5,
            'total_cost' => 34000,
            'status' => 'pending',
        ]);

        ReconciliationDiscrepancy::create([
            'operator_invoice_id' => $operatorInvoice->id,
            'type' => 'hours',
            'internal_value' => 40.0,
            'operator_value' => 42.5,
            'description' => 'Hour mismatch between internal logs and operator invoice',
            'resolved' => false,
        ]);

        OperatorInvoice::create([
            'operator_id' => $operator->id,
            'invoice_reference' => 'OP-INV-2026-002',
            'period_start' => now()->startOfMonth(),
            'period_end' => now()->endOfMonth(),
            'total_hours' => 18.0,
            'total_cost' => 14400,
            'status' => 'reconciled',
        ]);
    }
}
