<?php

namespace App\Filament\Resources\Operators\RelationManagers;

use App\Enums\BillingType;
use App\Support\EnumOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AircraftRelationManager extends RelationManager
{
    protected static string $relationship = 'aircraft';

    protected static ?string $title = 'Aircraft';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tail_number')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(20)
                    ->uppercase(),
                TextInput::make('aircraft_type')
                    ->required()
                    ->maxLength(100),
                TextInput::make('seat_capacity')
                    ->numeric()
                    ->default(6)
                    ->required(),
                TextInput::make('max_weight_kg')
                    ->numeric()
                    ->suffix('kg'),
                Select::make('billing_type')
                    ->options(EnumOptions::from(BillingType::class))
                    ->default(BillingType::Hourly->value)
                    ->required(),
                TextInput::make('hourly_rate')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('minimum_monthly_hours')
                    ->numeric(),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tail_number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('aircraft_type'),
                TextColumn::make('seat_capacity')
                    ->label('Seats'),
                TextColumn::make('billing_type')
                    ->badge(),
                TextColumn::make('hourly_rate')
                    ->money('usd'),
                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->defaultSort('tail_number');
    }
}
