<?php

namespace App\Filament\Resources\PrepaidHourAccounts\Pages;

use App\Filament\Resources\PrepaidHourAccounts\PrepaidHourAccountResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPrepaidHourAccount extends EditRecord
{
    protected static string $resource = PrepaidHourAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
