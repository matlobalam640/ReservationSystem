<?php

namespace App\Filament\Resources\LegTimeLogs\Schemas;

use App\Enums\BillingType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LegTimeLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('flight_leg_id')
                    ->relationship('flightLeg', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->routeLabel().' — '.$record->departure_at->format('M j, Y g:i A'))
                    ->searchable()
                    ->required(),
                DateTimePicker::make('engine_start_at'),
                DateTimePicker::make('takeoff_at'),
                DateTimePicker::make('landing_at'),
                DateTimePicker::make('engine_shutdown_at'),
                TextInput::make('flight_time_hours')
                    ->numeric(),
                TextInput::make('block_time_hours')
                    ->numeric(),
                Select::make('billing_method')
                    ->options(BillingType::class),
                TextInput::make('calculated_cost')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('fixed_route_cost')
                    ->numeric()
                    ->prefix('$'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
