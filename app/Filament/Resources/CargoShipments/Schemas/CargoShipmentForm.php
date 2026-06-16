<?php

namespace App\Filament\Resources\CargoShipments\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CargoShipmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('flight_leg_id')
                    ->relationship('flightLeg', 'id')
                    ->required(),
                TextInput::make('client_name')
                    ->required(),
                TextInput::make('weight_kg')
                    ->required()
                    ->numeric(),
                TextInput::make('origin')
                    ->required(),
                TextInput::make('destination')
                    ->required(),
                TextInput::make('invoice_amount')
                    ->numeric(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
