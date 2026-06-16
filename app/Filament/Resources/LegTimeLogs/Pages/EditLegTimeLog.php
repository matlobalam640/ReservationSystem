<?php

namespace App\Filament\Resources\LegTimeLogs\Pages;

use App\Filament\Resources\LegTimeLogs\LegTimeLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLegTimeLog extends EditRecord
{
    protected static string $resource = LegTimeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
