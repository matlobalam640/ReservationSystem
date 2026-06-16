<?php

namespace App\Filament\Resources\AddOns\Schemas;

use App\Enums\LegVisibility;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AddOnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
                TextInput::make('weight_kg')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('visibility')
                    ->options(LegVisibility::class)
                    ->default('public')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
