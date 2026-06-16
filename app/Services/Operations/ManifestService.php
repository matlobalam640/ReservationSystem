<?php

namespace App\Services\Operations;

use App\Models\FlightLeg;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ManifestService
{
    public function generate(int $aircraftId, Carbon $date)
    {
        $legs = FlightLeg::query()
            ->where('aircraft_id', $aircraftId)
            ->whereDate('departure_at', $date)
            ->with(['aircraft', 'bookings.bookingPassengers.passenger', 'bookings.bookingPassengers.seat'])
            ->orderBy('departure_at')
            ->get();

        return Pdf::loadView('pdf.manifest', compact('legs', 'date'));
    }
}
