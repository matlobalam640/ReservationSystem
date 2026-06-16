<?php

namespace App\Services\Booking;

use App\Enums\BookingChannel;
use App\Enums\BookingStatus;
use App\Enums\LegVisibility;
use App\Enums\PaymentStatus;
use App\Enums\TicketStatus;
use App\Models\AddOn;
use App\Models\Booking;
use App\Models\FlightLeg;
use App\Models\Passenger;
use App\Models\Seat;
use App\Models\User;
use App\Notifications\BookingConfirmedNotification;
use App\Services\Finance\CommissionService;
use App\Services\Finance\InvoiceService;
use App\Services\Hero\HeroMembershipService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class BookingService
{
    public function createBooking(
        FlightLeg $leg,
        array $passengerData,
        int $seatId,
        BookingChannel $channel,
        ?User $bookedBy = null,
        ?int $agencyId = null,
        array $addOnIds = [],
        ?string $membershipCode = null,
    ): Booking {
        return DB::transaction(function () use ($leg, $passengerData, $seatId, $channel, $bookedBy, $agencyId, $addOnIds, $membershipCode) {
            $seat = Seat::query()
                ->where('flight_leg_id', $leg->id)
                ->where('id', $seatId)
                ->lockForUpdate()
                ->first();

            if (! $seat || ! $seat->is_available || $seat->bookingPassenger()->exists()) {
                throw new InvalidArgumentException('Selected seat is no longer available.');
            }

            $passengerName = trim(($passengerData['first_name'] ?? '').' '.($passengerData['last_name'] ?? ''));
            $membershipResult = null;
            if ($membershipCode) {
                $membershipResult = app(HeroMembershipService::class)->validateMember(
                    $membershipCode,
                    $passengerName,
                    $passengerData['email'] ?? null,
                    $passengerData['phone'] ?? null,
                );

                if (! $membershipResult['valid']) {
                    throw new InvalidArgumentException($membershipResult['reason'] ?? 'Invalid HERO membership code.');
                }
            }

            $passenger = Passenger::create([
                'first_name' => $passengerData['first_name'],
                'last_name' => $passengerData['last_name'],
                'email' => $passengerData['email'] ?? null,
                'phone' => $passengerData['phone'] ?? null,
                'passport_number' => $passengerData['passport_number'] ?? null,
            ]);

            $addOns = $this->resolveAddOns($addOnIds, $channel);
            $amount = $this->calculateTotal($leg, $addOns, $membershipResult);

            $booking = Booking::create([
                'flight_leg_id' => $leg->id,
                'agency_id' => $agencyId,
                'booked_by_user_id' => $bookedBy?->id,
                'booking_channel' => $channel,
                'status' => BookingStatus::Confirmed,
                'total_amount' => $amount,
                'payment_status' => PaymentStatus::Unpaid,
                'notes' => $membershipCode ? "HERO member: {$membershipCode}" : null,
            ]);

            $booking->bookingPassengers()->create([
                'passenger_id' => $passenger->id,
                'seat_id' => $seat->id,
                'weight_kg' => $passengerData['weight_kg'] ?? null,
                'baggage_weight_kg' => $passengerData['baggage_weight_kg'] ?? null,
                'ticket_status' => TicketStatus::Confirmed,
                'amount' => $amount,
            ]);

            foreach ($addOns as $addOn) {
                $unitPrice = (float) $addOn->price;
                $booking->bookingAddOns()->create([
                    'add_on_id' => $addOn->id,
                    'quantity' => 1,
                    'unit_price' => $unitPrice,
                    'total_price' => $unitPrice,
                ]);
            }

            $seat->update(['is_available' => false]);

            $booking->load(['bookingPassengers.passenger', 'bookingPassengers.seat', 'flightLeg.aircraft', 'bookingAddOns.addOn']);

            app(InvoiceService::class)->createFromBooking($booking->fresh(['bookingAddOns.addOn']));
            app(CommissionService::class)->calculateForBooking($booking);

            $passenger = $booking->bookingPassengers->first()?->passenger;
            if ($passenger?->email) {
                \Illuminate\Support\Facades\Notification::route('mail', $passenger->email)
                    ->notify(new BookingConfirmedNotification($booking));
            }

            return $booking;
        });
    }

    public function attachAddOn(Booking $booking, AddOn $addOn, int $quantity = 1): void
    {
        $unitPrice = (float) $addOn->price;
        $total = $unitPrice * $quantity;

        $booking->bookingAddOns()->create([
            'add_on_id' => $addOn->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $total,
        ]);

        $booking->update(['total_amount' => (float) $booking->total_amount + $total]);
    }

    public function getOverbookingWarning(FlightLeg $leg): ?string
    {
        $active = $leg->bookings()->where('status', '!=', BookingStatus::Cancelled)->count();
        $capacity = $leg->seats()->count();

        if ($capacity > 0 && $active >= $capacity) {
            return "Warning: {$active} active bookings on {$capacity} seats (overbooking).";
        }

        return null;
    }

    public function cancelBooking(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            foreach ($booking->bookingPassengers as $bp) {
                if ($bp->seat_id) {
                    Seat::where('id', $bp->seat_id)->update(['is_available' => true]);
                }
                $bp->update(['ticket_status' => TicketStatus::Cancelled]);
            }
            $booking->update(['status' => BookingStatus::Cancelled]);
        });
    }

    public function markNoShow(Booking $booking): void
    {
        $booking->update(['status' => BookingStatus::NoShow]);
        $booking->bookingPassengers()->update(['ticket_status' => TicketStatus::NoShow]);
    }

    private function resolveAddOns(array $addOnIds, BookingChannel $channel): Collection
    {
        if (empty($addOnIds)) {
            return collect();
        }

        $visibility = match ($channel) {
            BookingChannel::Agency => [LegVisibility::Public, LegVisibility::Agency],
            BookingChannel::Admin, BookingChannel::Phone, BookingChannel::Counter => LegVisibility::cases(),
            default => [LegVisibility::Public],
        };

        return AddOn::query()
            ->whereIn('id', $addOnIds)
            ->where('is_active', true)
            ->whereIn('visibility', array_map(fn ($v) => $v->value, $visibility))
            ->get();
    }

    private function calculateTotal(FlightLeg $leg, Collection $addOns, ?array $membershipResult): float
    {
        $total = (float) ($leg->base_price ?? 0);

        foreach ($addOns as $addOn) {
            $price = (float) $addOn->price;
            if ($membershipResult && $this->hasBaggageDiscount($membershipResult)) {
                $discount = $this->baggageDiscountPercent($membershipResult);
                $price = round($price * (1 - $discount / 100), 2);
            }
            $total += $price;
        }

        return round($total, 2);
    }

    private function hasBaggageDiscount(?array $membershipResult): bool
    {
        return collect($membershipResult['benefits'] ?? [])
            ->contains(fn ($b) => ($b['benefit_type'] ?? '') === 'baggage_discount');
    }

    private function baggageDiscountPercent(?array $membershipResult): float
    {
        $rule = collect($membershipResult['benefits'] ?? [])
            ->firstWhere('benefit_type', 'baggage_discount');

        return (float) ($rule['discount_percent'] ?? 0);
    }
}
