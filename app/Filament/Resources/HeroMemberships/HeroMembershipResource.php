<?php

namespace App\Filament\Resources\HeroMemberships;

use App\Filament\Resources\HeroMemberships\Pages\CreateHeroMembership;
use App\Filament\Resources\HeroMemberships\Pages\EditHeroMembership;
use App\Filament\Resources\HeroMemberships\Pages\ListHeroMemberships;
use App\Filament\Resources\HeroMemberships\Schemas\HeroMembershipForm;
use App\Filament\Resources\HeroMemberships\Tables\HeroMembershipsTable;
use App\Models\HeroMembership;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HeroMembershipResource extends Resource
{
    protected static ?string $model = HeroMembership::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static string|UnitEnum|null $navigationGroup = 'Sales';

    protected static ?string $navigationLabel = 'HERO Memberships';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return HeroMembershipForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HeroMembershipsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHeroMemberships::route('/'),
            'create' => CreateHeroMembership::route('/create'),
            'edit' => EditHeroMembership::route('/{record}/edit'),
        ];
    }
}
