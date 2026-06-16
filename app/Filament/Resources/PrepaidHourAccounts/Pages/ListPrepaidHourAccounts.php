<?php

namespace App\Filament\Resources\PrepaidHourAccounts\Pages;

use App\Filament\Resources\PrepaidHourAccounts\PrepaidHourAccountResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPrepaidHourAccounts extends ListRecords
{
    protected static string $resource = PrepaidHourAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
