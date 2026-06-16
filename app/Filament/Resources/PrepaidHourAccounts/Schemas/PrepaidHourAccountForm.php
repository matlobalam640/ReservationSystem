<?php

namespace App\Filament\Resources\PrepaidHourAccounts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PrepaidHourAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('client_name')
                    ->required(),
                TextInput::make('hours_purchased')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('hours_used')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('flight_hour_rate')
                    ->numeric(),
                TextInput::make('ground_hour_rate')
                    ->numeric(),
                TextInput::make('ferry_hour_rate')
                    ->numeric(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
