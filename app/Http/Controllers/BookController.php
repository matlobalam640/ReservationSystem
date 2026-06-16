<?php

namespace App\Http\Controllers;

use App\Enums\BookingChannel;
use App\Enums\LegStatus;
use App\Enums\LegVisibility;
use App\Http\Requests\StoreBookingRequest;
use App\Models\AddOn;
use App\Models\Booking;
use App\Models\FlightLeg;
use App\Services\Booking\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use InvalidArgumentException;

class BookController extends Controller
{
    public function index(): View
    {
        return redirect()->route('home');
    }

    public function show(FlightLeg $leg): View|RedirectResponse
    {
        if (! $this->isBookable($leg)) {
            return redirect()->route('home')->with('error', 'This flight is not available for booking.');
        }

        $leg->load(['aircraft', 'flight', 'seats' => fn ($q) => $q->where('is_available', true)->orderBy('seat_number')]);

        $addOns = AddOn::query()
            ->where('is_active', true)
            ->where('visibility', LegVisibility::Public)
            ->orderBy('name')
            ->get();

        return view('book.show', compact('leg', 'addOns'));
    }

    public function store(StoreBookingRequest $request, FlightLeg $leg, BookingService $bookingService): RedirectResponse
    {
        if (! $this->isBookable($leg)) {
            return redirect()->route('home')->with('error', 'This flight is no longer available.');
        }

        try {
            $booking = $bookingService->createBooking(
                $leg,
                $request->validated(),
                (int) $request->input('seat_id'),
                BookingChannel::Website,
                addOnIds: $request->input('add_on_ids', []),
                membershipCode: $request->input('membership_code'),
            );
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('book.confirm', $booking);
    }

    public function confirm(Booking $booking): View
    {
        $booking->load(['bookingPassengers.passenger', 'bookingPassengers.seat', 'flightLeg.aircraft', 'bookingAddOns.addOn']);

        return view('book.confirm', compact('booking'));
    }

    private function isBookable(FlightLeg $leg): bool
    {
        return $leg->visibility === LegVisibility::Public
            && $leg->status === LegStatus::Available
            && $leg->departure_at->isFuture();
    }
}
