<?php

namespace App\Filament\Resources\HeroMemberships\Pages;

use App\Filament\Resources\HeroMemberships\HeroMembershipResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHeroMembership extends EditRecord
{
    protected static string $resource = HeroMembershipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
