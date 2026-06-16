<?php

namespace App\Filament\Resources\LegTimeLogs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LegTimeLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('flightLeg.id')
                    ->searchable(),
                TextColumn::make('engine_start_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('takeoff_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('landing_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('engine_shutdown_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('flight_time_hours')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('block_time_hours')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('billing_method')
                    ->badge()
                    ->searchable(),
                TextColumn::make('calculated_cost')
                    ->money()
                    ->sortable(),
                TextColumn::make('fixed_route_cost')
                    ->money()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
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
