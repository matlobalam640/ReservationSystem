<?php

namespace App\Http\Controllers;

use App\Enums\LegStatus;
use App\Enums\LegVisibility;
use App\Models\FlightLeg;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $baseQuery = FlightLeg::query()
            ->where('visibility', LegVisibility::Public)
            ->where('status', LegStatus::Available)
            ->where('departure_at', '>', now());

        $selectedOrigin = $request->filled('origin') ? strtoupper($request->input('origin')) : null;
        $selectedDestination = $request->filled('destination') ? strtoupper($request->input('destination')) : null;
        $selectedDate = $request->input('date');

        $origins = (clone $baseQuery)
            ->distinct()
            ->orderBy('origin')
            ->pluck('origin');

        if ($selectedOrigin && ! $origins->contains($selectedOrigin)) {
            $origins = $origins->push($selectedOrigin)->sort()->values();
        }

        $destinations = (clone $baseQuery)
            ->when($selectedOrigin, fn ($q) => $q->where('origin', $selectedOrigin))
            ->distinct()
            ->orderBy('destination')
            ->pluck('destination');

        if ($selectedDestination && ! $destinations->contains($selectedDestination)) {
            $destinations = $destinations->push($selectedDestination)->sort()->values();
        }

        $legs = (clone $baseQuery)
            ->with(['aircraft', 'flight'])
            ->when($request->filled('origin'), fn ($q) => $q->where('origin', strtoupper($request->origin)))
            ->when($request->filled('destination'), fn ($q) => $q->where('destination', strtoupper($request->destination)))
            ->when($request->filled('date'), function ($q) use ($request) {
                $parsed = \Carbon\Carbon::parse($request->date);
                $q->whereDate('departure_at', $parsed->toDateString());

                if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($request->date))) {
                    $q->where('departure_at', '>=', $parsed);
                }
            })
            ->withCount(['seats as available_seats_count' => fn ($q) => $q->where('is_available', true)])
            ->orderBy('departure_at')
            ->get();

        $searched = $request->hasAny(['origin', 'destination', 'date']);

        $locations = config('hero.locations', []);
        $featuredRoutes = config('hero.featured_routes', []);
        $contact = config('hero.contact', []);

        return view('home', compact(
            'legs',
            'origins',
            'destinations',
            'searched',
            'locations',
            'featuredRoutes',
            'contact',
            'selectedOrigin',
            'selectedDestination',
            'selectedDate',
        ));
    }
}
