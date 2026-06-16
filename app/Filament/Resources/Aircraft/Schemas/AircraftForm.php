<?php

namespace App\Filament\Resources\Aircraft\Schemas;

use App\Enums\BillingType;
use App\Support\EnumOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AircraftForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Aircraft Details')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('operator_id')
                                ->relationship('operator', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('tail_number')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(20)
                                ->uppercase(),
                            TextInput::make('aircraft_type')
                                ->required(),
                            TextInput::make('seat_capacity')
                                ->numeric()
                                ->default(6)
                                ->required(),
                            TextInput::make('max_weight_kg')
                                ->numeric()
                                ->suffix('kg'),
                            Toggle::make('is_active')
                                ->default(true),
                        ]),
                    ]),
                Section::make('Billing')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('billing_type')
                                ->options(EnumOptions::from(BillingType::class))
                                ->default(BillingType::Hourly->value)
                                ->required(),
                            TextInput::make('hourly_rate')
                                ->numeric()
                                ->prefix('$'),
                            TextInput::make('minimum_monthly_hours')
                                ->numeric(),
                        ]),
                    ]),
            ]);
    }
}
