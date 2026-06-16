<?php

namespace App\Filament\Resources\LegTimeLogs\Pages;

use App\Filament\Resources\LegTimeLogs\LegTimeLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLegTimeLogs extends ListRecords
{
    protected static string $resource = LegTimeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
