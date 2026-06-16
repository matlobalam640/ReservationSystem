<?php

namespace App\Filament\Agency\Resources\AgencyBookingResource\Pages;

use App\Filament\Agency\Resources\AgencyBookingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAgencyBookings extends ListRecords
{
    protected static string $resource = AgencyBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->url(AgencyBookingResource::getUrl('create')),
        ];
    }
}
