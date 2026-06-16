<?php

namespace App\Filament\Agency\Resources\AgencyBookingResource\Pages;

use App\Filament\Agency\Resources\AgencyBookingResource;
use App\Models\Booking;
use Filament\Resources\Pages\Page;

class ViewAgencyBooking extends Page
{
    protected static string $resource = AgencyBookingResource::class;

    protected static ?string $title = 'Booking Details';

    protected string $view = 'filament.agency.pages.view-agency-booking';

    public Booking $record;

    public function mount(int|string $record): void
    {
        $this->record = AgencyBookingResource::getEloquentQuery()
            ->with([
                'flightLeg.aircraft',
                'bookingPassengers.passenger',
                'bookingPassengers.seat',
                'bookingAddOns.addOn',
                'commissionLedger',
            ])
            ->findOrFail($record);
    }
}
