<?php

namespace App\Filament\Resources\GroundHandlers;

use App\Filament\Resources\GroundHandlers\Pages\ListGroundHandlers;
use App\Models\GroundHandler;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class GroundHandlerResource extends Resource
{
    protected static ?string $model = GroundHandler::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static string|UnitEnum|null $navigationGroup = 'Operations';

    protected static ?string $navigationLabel = 'Ground Handlers';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required(),
            TextInput::make('default_rate')->numeric()->prefix('$')->required(),
            Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable(),
            TextColumn::make('default_rate')->money('usd'),
            IconColumn::make('is_active')->boolean(),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => ListGroundHandlers::route('/')];
    }
}
