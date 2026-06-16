<?php

namespace App\Filament\Resources\Aircraft;

use App\Filament\Resources\Aircraft\Pages\CreateAircraft;
use App\Filament\Resources\Aircraft\Pages\EditAircraft;
use App\Filament\Resources\Aircraft\Pages\ListAircraft;
use App\Filament\Resources\Aircraft\Schemas\AircraftForm;
use App\Filament\Resources\Aircraft\Tables\AircraftTable;
use App\Models\Aircraft as AircraftModel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AircraftResource extends Resource
{
    protected static ?string $model = AircraftModel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperAirplane;

    protected static string|UnitEnum|null $navigationGroup = 'Fleet';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'tail_number';

    protected static ?string $modelLabel = 'Aircraft';

    public static function form(Schema $schema): Schema
    {
        return AircraftForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AircraftTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAircraft::route('/'),
            'create' => CreateAircraft::route('/create'),
            'edit' => EditAircraft::route('/{record}/edit'),
        ];
    }
}
