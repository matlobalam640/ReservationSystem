<?php

namespace App\Filament\Resources\Aircraft\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AircraftTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tail_number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('operator.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('aircraft_type')
                    ->searchable(),
                TextColumn::make('seat_capacity')
                    ->label('Seats'),
                TextColumn::make('billing_type')
                    ->badge(),
                TextColumn::make('hourly_rate')
                    ->money('usd'),
                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->defaultSort('tail_number')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
