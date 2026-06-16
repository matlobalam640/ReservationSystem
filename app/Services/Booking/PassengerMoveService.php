<?php

namespace App\Services\Booking;

use App\Enums\BookingStatus;
use App\Enums\TicketStatus;
use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\FlightLeg;
use App\Models\Seat;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class PassengerMoveService
{
    public function moveToLeg(BookingPassenger $bookingPassenger, FlightLeg $targetLeg, ?int $seatId = null): void
    {
        DB::transaction(function () use ($bookingPassenger, $targetLeg, $seatId) {
            $booking = $bookingPassenger->booking;
            $oldSeatId = $bookingPassenger->seat_id;

            if ($seatId) {
                $seat = Seat::query()
                    ->where('flight_leg_id', $targetLeg->id)
                    ->where('id', $seatId)
                    ->lockForUpdate()
                    ->first();

                if (! $seat || (! $seat->is_available && $seat->id !== $oldSeatId)) {
                    throw new InvalidArgumentException('Target seat is not available.');
                }

                if ($oldSeatId) {
                    Seat::where('id', $oldSeatId)->update(['is_available' => true]);
                }

                $seat->update(['is_available' => false]);
                $bookingPassenger->update(['seat_id' => $seat->id]);
            }

            $booking->update(['flight_leg_id' => $targetLeg->id]);
        });
    }

    public function rebook(Booking $booking, FlightLeg $newLeg, int $seatId): Booking
    {
        return DB::transaction(function () use ($booking, $newLeg, $seatId) {
            app(BookingService::class)->cancelBooking($booking);

            $bp = $booking->bookingPassengers->first();
            $passenger = $bp->passenger;

            return app(BookingService::class)->createBooking(
                $newLeg,
                [
                    'first_name' => $passenger->first_name,
                    'last_name' => $passenger->last_name,
                    'email' => $passenger->email,
                    'phone' => $passenger->phone,
                    'passport_number' => $passenger->passport_number,
                    'weight_kg' => $bp->weight_kg,
                    'baggage_weight_kg' => $bp->baggage_weight_kg,
                ],
                $seatId,
                $booking->booking_channel,
                $booking->bookedBy,
                $booking->agency_id,
            );
        });
    }
}
