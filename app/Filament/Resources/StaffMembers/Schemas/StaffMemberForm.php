<?php

namespace App\Filament\Resources\StaffMembers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class StaffMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('role'),
                TextInput::make('per_flight_rate')
                    ->numeric(),
                TextInput::make('per_leg_rate')
                    ->numeric(),
                TextInput::make('per_day_rate')
                    ->numeric(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
