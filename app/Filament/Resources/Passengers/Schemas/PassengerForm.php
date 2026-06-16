<?php

namespace App\Filament\Resources\Passengers\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PassengerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Passenger')->schema([
                Grid::make(2)->schema([
                    TextInput::make('first_name')->required(),
                    TextInput::make('last_name')->required(),
                    TextInput::make('email')->email(),
                    TextInput::make('phone'),
                    TextInput::make('passport_number'),
                    TextInput::make('loyalty_status'),
                ]),
                Textarea::make('notes')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }
}
