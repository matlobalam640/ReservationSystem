<?php

namespace App\Filament\Resources\MedevacCases\Pages;

use App\Filament\Resources\MedevacCases\MedevacCaseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMedevacCase extends EditRecord
{
    protected static string $resource = MedevacCaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
