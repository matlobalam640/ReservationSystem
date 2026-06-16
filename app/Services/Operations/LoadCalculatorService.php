<?php

namespace App\Services\Operations;

use App\Models\FlightLeg;

class LoadCalculatorService
{
    public function calculateForLeg(FlightLeg $leg): array
    {
        $leg->load(['bookingPassengers', 'aircraft']);

        $passengerWeight = $leg->bookingPassengers->sum(fn ($bp) => (float) ($bp->actual_weight_kg ?? $bp->weight_kg ?? 0));
        $baggageWeight = $leg->bookingPassengers->sum(fn ($bp) => (float) ($bp->actual_baggage_weight_kg ?? $bp->baggage_weight_kg ?? 0));
        $total = $passengerWeight + $baggageWeight;
        $max = (float) ($leg->aircraft->max_weight_kg ?? 0);
        $alerts = [];

        if ($max > 0 && $total > $max) {
            $alerts[] = 'Aircraft is overweight by '.number_format($total - $max, 1).' kg';
        }

        return [
            'passenger_weight' => $passengerWeight,
            'baggage_weight' => $baggageWeight,
            'total_weight' => $total,
            'max_weight' => $max,
            'is_overweight' => $max > 0 && $total > $max,
            'utilization_percent' => $max > 0 ? min(100, round(($total / $max) * 100, 1)) : 0,
            'alerts' => $alerts,
        ];
    }
}
