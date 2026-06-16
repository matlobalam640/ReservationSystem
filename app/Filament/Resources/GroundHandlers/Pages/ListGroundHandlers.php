<?php

namespace App\Filament\Resources\GroundHandlers\Pages;

use App\Filament\Resources\GroundHandlers\GroundHandlerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGroundHandlers extends ListRecords
{
    protected static string $resource = GroundHandlerResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
