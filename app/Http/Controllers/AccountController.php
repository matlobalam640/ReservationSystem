<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        $passenger = $user->passenger;

        $upcomingBookings = collect();
        $loyaltyPoints = 0;
        $membership = null;

        if ($passenger) {
            $upcomingBookings = $passenger->bookingPassengers()
                ->whereHas('booking.flightLeg', fn ($q) => $q->where('departure_at', '>', now()))
                ->with(['booking.flightLeg', 'seat'])
                ->get();

            $loyaltyPoints = \App\Models\LoyaltyPoint::where('passenger_id', $passenger->id)->sum('points');
            $membership = \App\Models\HeroMembership::where('passenger_id', $passenger->id)->where('is_active', true)->first();
        }

        return view('account.index', compact('user', 'passenger', 'upcomingBookings', 'loyaltyPoints', 'membership'));
    }
}
