<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Bookings\BookingResource;
use App\Models\Booking;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentBookingsTableWidget extends TableWidget
{
    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'lg' => 3,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent bookings')
            ->description('Latest reservations across all channels')
            ->query(
                Booking::query()
                    ->with(['flightLeg', 'agency'])
                    ->latest()
                    ->limit(8)
            )
            ->columns([
                TextColumn::make('reference_number')
                    ->label('Reference')
                    ->searchable(),
                TextColumn::make('flightLeg.route')
                    ->label('Route')
                    ->state(fn (Booking $record): string => $record->flightLeg?->routeLabel() ?? '—'),
                TextColumn::make('flightLeg.departure_at')
                    ->label('Departure')
                    ->dateTime('M j, g:i A'),
                TextColumn::make('booking_channel')
                    ->label('Channel')
                    ->badge(),
                TextColumn::make('payment_status')
                    ->badge(),
                TextColumn::make('total_amount')
                    ->money('usd'),
            ])
            ->recordUrl(fn (Booking $record): string => BookingResource::getUrl('edit', ['record' => $record]))
            ->paginated(false)
            ->emptyStateHeading('No bookings yet')
            ->emptyStateDescription('Bookings from the website, agency portal, or admin will appear here.')
            ->emptyStateActions([
                \Filament\Actions\Action::make('createBooking')
                    ->label('Create booking')
                    ->url(BookingResource::getUrl('create')),
            ]);
    }
}
