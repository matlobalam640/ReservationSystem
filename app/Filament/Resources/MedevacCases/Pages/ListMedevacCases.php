<?php

namespace App\Filament\Resources\MedevacCases\Pages;

use App\Filament\Resources\MedevacCases\MedevacCaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMedevacCases extends ListRecords
{
    protected static string $resource = MedevacCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
