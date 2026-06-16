<?php

namespace App\Filament\Resources\CargoShipments\Pages;

use App\Filament\Resources\CargoShipments\CargoShipmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCargoShipments extends ListRecords
{
    protected static string $resource = CargoShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
