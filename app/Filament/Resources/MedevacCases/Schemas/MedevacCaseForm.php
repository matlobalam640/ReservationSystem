<?php

namespace App\Filament\Resources\MedevacCases\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MedevacCaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('flight_leg_id')
                    ->relationship('flightLeg', 'id')
                    ->required(),
                TextInput::make('patient_name')
                    ->required(),
                Textarea::make('condition')
                    ->columnSpanFull(),
                Textarea::make('vitals')
                    ->columnSpanFull(),
                TextInput::make('pickup_location'),
                TextInput::make('dropoff_location'),
                TextInput::make('category')
                    ->required()
                    ->default('paid'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
