<?php

namespace App\Filament\Agency\Resources\AgencyFlightScheduleResource\Pages;

use App\Filament\Agency\Resources\AgencyBookingResource;
use App\Filament\Agency\Resources\AgencyFlightScheduleResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListAgencyFlightSchedule extends ListRecords
{
    protected static string $resource = AgencyFlightScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('newBooking')
                ->label('New Booking')
                ->url(AgencyBookingResource::getUrl('create')),
        ];
    }
}
