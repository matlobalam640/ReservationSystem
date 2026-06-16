<?php

namespace App\Filament\Agency\Resources\AgencyBookingResource\Pages;

use App\Enums\BookingChannel;
use App\Filament\Agency\Resources\AgencyBookingResource;
use App\Models\FlightLeg;
use App\Models\Seat;
use App\Services\Booking\BookingService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use InvalidArgumentException;

class CreateAgencyBooking extends Page
{
    protected static string $resource = AgencyBookingResource::class;

    protected static ?string $title = 'New Booking';

    protected string $view = 'filament.agency.pages.create-agency-booking';

    public ?int $flight_leg_id = null;

    public ?int $seat_id = null;

    public string $first_name = '';

    public string $last_name = '';

    public ?string $email = null;

    public ?string $phone = null;

    public float $weight_kg = 75;

    public function getLegOptions(): array
    {
        return FlightLeg::query()
            ->whereIn('visibility', ['public', 'agency'])
            ->where('departure_at', '>', now())
            ->orderBy('departure_at')
            ->get()
            ->mapWithKeys(fn ($l) => [$l->id => $l->routeLabel().' — '.$l->departure_at->format('M j g:i A')])
            ->all();
    }

    public function getSeatOptions(): array
    {
        if (! $this->flight_leg_id) {
            return [];
        }

        return Seat::query()
            ->where('flight_leg_id', $this->flight_leg_id)
            ->where('is_available', true)
            ->pluck('seat_number', 'id')
            ->all();
    }

    public function submit(BookingService $bookingService): void
    {
        $leg = FlightLeg::findOrFail($this->flight_leg_id);

        try {
            $bookingService->createBooking(
                $leg,
                [
                    'first_name' => $this->first_name,
                    'last_name' => $this->last_name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'weight_kg' => $this->weight_kg,
                ],
                (int) $this->seat_id,
                BookingChannel::Agency,
                auth()->user(),
                auth()->user()?->agency_id,
            );
        } catch (InvalidArgumentException $e) {
            Notification::make()->danger()->body($e->getMessage())->send();

            return;
        }

        Notification::make()->success()->body('Booking created.')->send();
        $this->redirect(AgencyBookingResource::getUrl('index'));
    }
}
