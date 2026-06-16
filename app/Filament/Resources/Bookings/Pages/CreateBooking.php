<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Enums\BookingChannel;
use App\Filament\Resources\Bookings\BookingResource;
use App\Models\FlightLeg;
use App\Services\Booking\BookingService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $leg = FlightLeg::findOrFail($data['flight_leg_id']);

        if ($warning = app(BookingService::class)->getOverbookingWarning($leg)) {
            Notification::make()->warning()->title('Overbooking')->body($warning)->send();
        }

        try {
            return app(BookingService::class)->createBooking(
                $leg,
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'weight_kg' => $data['weight_kg'],
                    'baggage_weight_kg' => $data['baggage_weight_kg'] ?? 0,
                ],
                (int) $data['seat_id'],
                BookingChannel::from($data['booking_channel']),
                auth()->user(),
                $data['agency_id'] ?? null,
            );
        } catch (InvalidArgumentException $e) {
            Notification::make()->danger()->title('Booking failed')->body($e->getMessage())->send();

            throw ValidationException::withMessages(['seat_id' => $e->getMessage()]);
        }
    }
}
