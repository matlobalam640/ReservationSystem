<?php

namespace App\Filament\Resources\Passengers\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PassengersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('last_name')->searchable()->sortable(),
                TextColumn::make('first_name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('phone'),
                TextColumn::make('booking_passengers_count')->counts('bookingPassengers')->label('Bookings'),
            ])
            ->recordActions([EditAction::make()]);
    }
}
