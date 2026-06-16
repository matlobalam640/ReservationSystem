<?php

namespace App\Filament\Resources\HeroMemberships\Pages;

use App\Filament\Resources\HeroMemberships\HeroMembershipResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHeroMemberships extends ListRecords
{
    protected static string $resource = HeroMembershipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
