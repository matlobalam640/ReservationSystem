<?php

namespace Database\Seeders;

use App\Enums\BookingChannel;
use App\Enums\BookingStatus;
use App\Enums\LegStatus;
use App\Enums\LegVisibility;
use App\Enums\PaymentStatus;
use App\Enums\TicketStatus;
use App\Models\Booking;
use App\Models\FlightLeg;
use App\Models\LoyaltyPoint;
use App\Models\Passenger;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSampleSeeder extends Seeder
{
    public function run(): void
    {
        $passenger = Passenger::where('email', 'john@example.com')->first();

        if (! $passenger) {
            return;
        }

        $leg = FlightLeg::query()
            ->where('departure_at', '>', now())
            ->where('status', LegStatus::Available)
            ->where('visibility', LegVisibility::Public)
            ->first();

        if (! $leg) {
            return;
        }

        $seat = $leg->seats()->where('is_available', true)->first();

        if (! $seat) {
            return;
        }

        $alreadyBooked = Booking::query()
            ->where('flight_leg_id', $leg->id)
            ->whereHas('bookingPassengers', fn ($q) => $q->where('passenger_id', $passenger->id))
            ->exists();

        if (! $alreadyBooked) {
            $booking = Booking::create([
                'flight_leg_id' => $leg->id,
                'booked_by_user_id' => User::where('email', 'john@example.com')->value('id'),
                'booking_channel' => BookingChannel::Website,
                'status' => BookingStatus::Confirmed,
                'total_amount' => $leg->base_price,
                'payment_status' => PaymentStatus::Paid,
            ]);

            $booking->bookingPassengers()->create([
                'passenger_id' => $passenger->id,
                'seat_id' => $seat->id,
                'weight_kg' => 80,
                'ticket_status' => TicketStatus::Confirmed,
                'amount' => $leg->base_price,
            ]);

            $seat->update(['is_available' => false]);
        }

        LoyaltyPoint::firstOrCreate(
            ['passenger_id' => $passenger->id, 'source' => 'welcome_bonus'],
            ['points' => 150],
        );
    }
}
