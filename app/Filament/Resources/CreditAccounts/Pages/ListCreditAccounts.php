<?php

namespace App\Filament\Resources\CreditAccounts\Pages;

use App\Filament\Resources\CreditAccounts\CreditAccountResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCreditAccounts extends ListRecords
{
    protected static string $resource = CreditAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
