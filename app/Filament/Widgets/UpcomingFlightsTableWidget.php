<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Flights\FlightResource;
use App\Models\FlightLeg;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class UpcomingFlightsTableWidget extends TableWidget
{
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 3,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->heading('Upcoming flights')
            ->description('Next scheduled legs across the fleet')
            ->query(
                FlightLeg::query()
                    ->where('departure_at', '>=', now())
                    ->with('aircraft')
                    ->withCount([
                        'seats as available_seats_count' => fn ($query) => $query->where('is_available', true),
                        'seats as total_seats_count',
                    ])
                    ->orderBy('departure_at')
                    ->limit(8)
            )
            ->columns([
                TextColumn::make('route')
                    ->label('Route')
                    ->state(fn (FlightLeg $record): string => $record->routeLabel())
                    ->searchable(['origin', 'destination']),
                TextColumn::make('departure_at')
                    ->label('Departure')
                    ->dateTime('M j, g:i A')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('aircraft.tail_number')
                    ->label('Aircraft'),
                TextColumn::make('available_seats_count')
                    ->label('Seats left')
                    ->formatStateUsing(fn (FlightLeg $record): string => $record->available_seats_count.' / '.$record->total_seats_count)
                    ->color(fn (FlightLeg $record): string => $record->available_seats_count <= 2 ? 'warning' : 'gray'),
                TextColumn::make('base_price')
                    ->label('From')
                    ->money('usd')
                    ->placeholder('—'),
            ])
            ->paginated(false)
            ->emptyStateHeading('No upcoming flights')
            ->emptyStateDescription('Schedule legs under Flights to populate this list.')
            ->emptyStateActions([
                \Filament\Actions\Action::make('viewFlights')
                    ->label('Manage flights')
                    ->url(FlightResource::getUrl('index')),
            ]);
    }
}
