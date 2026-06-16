<?php

namespace App\Filament\Resources\CargoShipments;

use App\Filament\Resources\CargoShipments\Pages\CreateCargoShipment;
use App\Filament\Resources\CargoShipments\Pages\EditCargoShipment;
use App\Filament\Resources\CargoShipments\Pages\ListCargoShipments;
use App\Filament\Resources\CargoShipments\Schemas\CargoShipmentForm;
use App\Filament\Resources\CargoShipments\Tables\CargoShipmentsTable;
use App\Models\CargoShipment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CargoShipmentResource extends Resource
{
    protected static ?string $model = CargoShipment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static string|UnitEnum|null $navigationGroup = 'Special Operations';

    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'dispatch']) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return CargoShipmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CargoShipmentsTable::configure($table);
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
            'index' => ListCargoShipments::route('/'),
            'create' => CreateCargoShipment::route('/create'),
            'edit' => EditCargoShipment::route('/{record}/edit'),
        ];
    }
}
