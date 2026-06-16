<?php

namespace App\Filament\Agency\Resources;

use App\Filament\Agency\Resources\AgencyFlightScheduleResource\Pages\ListAgencyFlightSchedule;
use App\Models\FlightLeg;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AgencyFlightScheduleResource extends Resource
{
    protected static ?string $model = FlightLeg::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel = 'Schedules';

    protected static ?string $modelLabel = 'Flight';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->bookableForAgency();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('origin')
                    ->label('Route')
                    ->state(fn (FlightLeg $record): string => $record->routeLabel()),
                TextColumn::make('departure_at')->dateTime()->sortable(),
                TextColumn::make('aircraft.tail_number')->label('Aircraft'),
                TextColumn::make('visibility')->badge(),
                TextColumn::make('base_price')->money('usd'),
                TextColumn::make('available_seats_count')
                    ->label('Seats open')
                    ->state(fn (FlightLeg $record): int => $record->seats()->where('is_available', true)->count()),
            ])
            ->defaultSort('departure_at')
            ->recordUrl(null);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAgencyFlightSchedule::route('/'),
        ];
    }
}
