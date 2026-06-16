<?php

namespace App\Filament\Schemas;

use App\Enums\LegStatus;
use App\Enums\LegVisibility;
use App\Enums\ReturnLegResale;
use App\Models\Aircraft;
use App\Support\EnumOptions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FlightLegForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Route & Schedule')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('aircraft_id')
                                ->label('Aircraft')
                                ->relationship('aircraft', 'tail_number')
                                ->getOptionLabelFromRecordUsing(fn (Aircraft $record) => "{$record->tail_number} ({$record->aircraft_type})")
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('sort_order')
                                ->numeric()
                                ->default(0)
                                ->required(),
                            TextInput::make('origin')
                                ->label('Origin')
                                ->placeholder('PVC')
                                ->maxLength(10)
                                ->required()
                                ->uppercase(),
                            TextInput::make('destination')
                                ->label('Destination')
                                ->placeholder('CAP')
                                ->maxLength(10)
                                ->required()
                                ->uppercase(),
                            DateTimePicker::make('departure_at')
                                ->required()
                                ->seconds(false),
                            DateTimePicker::make('arrival_at')
                                ->seconds(false),
                        ]),
                    ]),
                Section::make('Visibility & Pricing')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('visibility')
                                ->options(EnumOptions::from(LegVisibility::class))
                                ->default(LegVisibility::Public->value)
                                ->required(),
                            Select::make('status')
                                ->options(EnumOptions::from(LegStatus::class))
                                ->default(LegStatus::Planned->value)
                                ->required(),
                            Select::make('return_leg_resale')
                                ->label('Return Leg Resale')
                                ->options(EnumOptions::from(ReturnLegResale::class))
                                ->default(ReturnLegResale::Blocked->value)
                                ->required(),
                            TextInput::make('base_price')
                                ->numeric()
                                ->prefix('$'),
                            Toggle::make('is_return_leg')
                                ->label('Return Leg'),
                        ]),
                    ]),
                Section::make('Notes')
                    ->schema([
                        Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
