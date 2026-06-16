<?php

namespace App\Filament\Resources\CargoShipments\Pages;

use App\Filament\Resources\CargoShipments\CargoShipmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCargoShipment extends EditRecord
{
    protected static string $resource = CargoShipmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
