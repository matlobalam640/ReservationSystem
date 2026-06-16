<?php

namespace App\Services;

use App\Enums\SeatType;
use App\Models\FlightLeg;
use App\Models\Seat;

class SeatGenerator
{
    public function generateForLeg(FlightLeg $leg): void
    {
        $aircraft = $leg->aircraft;

        if (! $aircraft) {
            return;
        }

        for ($i = 1; $i <= $aircraft->seat_capacity; $i++) {
            Seat::create([
                'flight_leg_id' => $leg->id,
                'seat_number' => (string) $i,
                'seat_type' => SeatType::Passenger,
                'is_available' => true,
            ]);
        }
    }

    public function regenerateForLeg(FlightLeg $leg): void
    {
        $assignedSeatIds = $leg->seats()
            ->whereHas('bookingPassenger')
            ->pluck('id');

        $leg->seats()->whereNotIn('id', $assignedSeatIds)->delete();

        $currentCount = $leg->seats()->count();
        $targetCapacity = $leg->aircraft->seat_capacity;

        if ($currentCount >= $targetCapacity) {
            if ($currentCount > $targetCapacity) {
                $leg->seats()
                    ->whereDoesntHave('bookingPassenger')
                    ->orderByDesc('seat_number')
                    ->limit($currentCount - $targetCapacity)
                    ->delete();
            }

            return;
        }

        $nextNumber = (int) $leg->seats()->max('seat_number') + 1;

        for ($i = $currentCount; $i < $targetCapacity; $i++) {
            Seat::create([
                'flight_leg_id' => $leg->id,
                'seat_number' => (string) $nextNumber,
                'seat_type' => SeatType::Passenger,
                'is_available' => true,
            ]);

            $nextNumber++;
        }
    }
}
