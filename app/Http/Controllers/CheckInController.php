<?php

namespace App\Http\Controllers;

use App\Models\BookingPassenger;
use App\Models\FlightLeg;
use App\Services\Operations\CheckInService;
use App\Services\Operations\LoadCalculatorService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckInController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $legs = FlightLeg::query()
            ->whereDate('departure_at', '>=', today())
            ->orderBy('departure_at')
            ->get();

        return view('check-in.index', compact('legs'));
    }

    public function show(FlightLeg $leg, LoadCalculatorService $loadCalculator): View
    {
        $leg->load(['bookings.bookingPassengers.passenger', 'bookings.bookingPassengers.seat', 'aircraft']);
        $load = $loadCalculator->calculateForLeg($leg);

        return view('check-in.show', compact('leg', 'load'));
    }

    public function store(Request $request, BookingPassenger $passenger, CheckInService $checkInService): RedirectResponse
    {
        $data = $request->validate([
            'actual_weight_kg' => ['required', 'numeric', 'min:1'],
            'actual_baggage_weight_kg' => ['nullable', 'numeric', 'min:0'],
        ]);

        $checkInService->checkIn(
            $passenger,
            (float) $data['actual_weight_kg'],
            isset($data['actual_baggage_weight_kg']) ? (float) $data['actual_baggage_weight_kg'] : null,
        );

        return back()->with('success', 'Passenger checked in successfully.');
    }
}
