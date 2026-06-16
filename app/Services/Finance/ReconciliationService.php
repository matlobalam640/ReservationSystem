<?php

namespace App\Services\Finance;

use App\Enums\BillingType;
use App\Models\FlightLeg;
use App\Models\OperatorInvoice;
use App\Models\ReconciliationDiscrepancy;

class ReconciliationService
{
    public function calculateLegCost(FlightLeg $leg): float
    {
        $leg->load(['timeLog', 'aircraft.operator']);
        $aircraft = $leg->aircraft;
        $log = $leg->timeLog;

        if (! $aircraft) {
            return 0;
        }

        if ($aircraft->billing_type === BillingType::Fixed) {
            return (float) ($log?->fixed_route_cost ?? $leg->base_price ?? 0);
        }

        $hours = (float) ($log?->block_time_hours ?? $log?->flight_time_hours ?? 0);
        $rate = (float) ($aircraft->hourly_rate ?? 0);

        return round($hours * $rate, 2);
    }

    public function compareOperatorInvoice(OperatorInvoice $operatorInvoice): array
    {
        $internalHours = 0;
        $internalCost = 0;
        $discrepancies = [];

        $legs = FlightLeg::query()
            ->whereHas('aircraft', fn ($q) => $q->where('operator_id', $operatorInvoice->operator_id))
            ->whereBetween('departure_at', [$operatorInvoice->period_start, $operatorInvoice->period_end])
            ->with('timeLog')
            ->get();

        foreach ($legs as $leg) {
            $internalHours += (float) ($leg->timeLog?->block_time_hours ?? 0);
            $internalCost += $this->calculateLegCost($leg);
        }

        if (abs($internalHours - (float) $operatorInvoice->total_hours) > 0.1) {
            $discrepancies[] = ReconciliationDiscrepancy::create([
                'operator_invoice_id' => $operatorInvoice->id,
                'type' => 'hours',
                'internal_value' => $internalHours,
                'operator_value' => $operatorInvoice->total_hours,
                'description' => 'Hour mismatch between internal logs and operator invoice',
            ]);
        }

        if (abs($internalCost - (float) $operatorInvoice->total_cost) > 1) {
            $discrepancies[] = ReconciliationDiscrepancy::create([
                'operator_invoice_id' => $operatorInvoice->id,
                'type' => 'cost',
                'internal_value' => $internalCost,
                'operator_value' => $operatorInvoice->total_cost,
                'description' => 'Cost mismatch between internal calculation and operator invoice',
            ]);
        }

        return [
            'internal_hours' => $internalHours,
            'internal_cost' => $internalCost,
            'discrepancies' => $discrepancies,
        ];
    }
}
