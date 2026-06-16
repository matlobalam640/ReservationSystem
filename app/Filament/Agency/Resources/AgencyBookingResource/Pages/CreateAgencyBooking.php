<?php

namespace App\Filament\Agency\Resources\AgencyBookingResource\Pages;

use App\Enums\BookingChannel;
use App\Enums\LegVisibility;
use App\Filament\Agency\Resources\AgencyBookingResource;
use App\Models\AddOn;
use App\Models\FlightLeg;
use App\Models\Seat;
use App\Services\Booking\BookingService;
use App\Services\Finance\CommissionService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use InvalidArgumentException;
use Livewire\Attributes\Computed;

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

    public ?string $membership_code = null;

    /** @var array<int> */
    public array $add_on_ids = [];

    public function getLegOptions(): array
    {
        return FlightLeg::query()
            ->bookableForAgency()
            ->orderBy('departure_at')
            ->get()
            ->mapWithKeys(fn ($l) => [$l->id => $l->routeLabel().' — '.$l->departure_at->format('M j g:i A').' ($'.number_format((float) $l->base_price, 0).')'])
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

    public function getAddOnOptions(): array
    {
        return AddOn::query()
            ->where('is_active', true)
            ->whereIn('visibility', [LegVisibility::Public, LegVisibility::Agency])
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }

    #[Computed]
    public function quote(): ?array
    {
        if (! $this->flight_leg_id) {
            return null;
        }

        $leg = FlightLeg::find($this->flight_leg_id);
        if (! $leg) {
            return null;
        }

        $bookingService = app(BookingService::class);
        $total = $bookingService->quotePrice(
            $leg,
            BookingChannel::Agency,
            $this->add_on_ids,
            $this->membership_code,
            [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
            ],
        );

        $agencyId = auth()->user()?->agency_id;
        $commission = $agencyId
            ? app(CommissionService::class)->estimateForAgency($agencyId, BookingChannel::Agency)
            : ['hero_amount' => 0, 'agency_amount' => 0, 'rule_name' => null];

        return [
            'total' => $total,
            'base_price' => (float) $leg->base_price,
            'commission' => $commission,
            'payment_model' => auth()->user()?->agency?->payment_model?->label(),
        ];
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
                $this->add_on_ids,
                $this->membership_code,
            );
        } catch (InvalidArgumentException $e) {
            Notification::make()->danger()->body($e->getMessage())->send();

            return;
        }

        Notification::make()->success()->body('Booking created.')->send();
        $this->redirect(AgencyBookingResource::getUrl('index'));
    }
}
