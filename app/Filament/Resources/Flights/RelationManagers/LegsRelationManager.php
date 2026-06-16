<?php

namespace App\Filament\Resources\Flights\RelationManagers;

use App\Enums\LegStatus;
use App\Enums\LegVisibility;
use App\Enums\ReturnLegResale;
use App\Filament\Schemas\FlightLegForm;
use App\Models\FlightLeg;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LegsRelationManager extends RelationManager
{
    protected static string $relationship = 'legs';

    protected static ?string $title = 'Flight Legs';

    public function form(Schema $schema): Schema
    {
        return FlightLegForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('route')
                    ->label('Route')
                    ->state(fn (FlightLeg $record): string => $record->routeLabel()),
                TextColumn::make('aircraft.tail_number')
                    ->label('Aircraft'),
                TextColumn::make('departure_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('visibility')
                    ->badge(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (FlightLeg $record): string => $record->status->color()),
                TextColumn::make('seats_count')
                    ->counts('seats')
                    ->label('Seats'),
            ])
            ->defaultSort('sort_order')
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $maxOrder = $this->getOwnerRecord()->legs()->max('sort_order') ?? -1;
                        $data['sort_order'] = $maxOrder + 1;

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('clone')
                    ->label('Clone')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (FlightLeg $record): void {
                        $maxOrder = $this->getOwnerRecord()->legs()->max('sort_order') ?? 0;
                        $record->cloneAsNewLeg($maxOrder + 1);
                    }),
                Action::make('returnLeg')
                    ->label('Create Return Leg')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->action(function (FlightLeg $record): void {
                        $maxOrder = $this->getOwnerRecord()->legs()->max('sort_order') ?? 0;
                        FlightLeg::create([
                            'flight_id' => $record->flight_id,
                            'aircraft_id' => $record->aircraft_id,
                            'sort_order' => $maxOrder + 1,
                            'origin' => $record->destination,
                            'destination' => $record->origin,
                            'departure_at' => $record->departure_at->copy()->addHours(4),
                            'visibility' => LegVisibility::Public,
                            'status' => LegStatus::Planned,
                            'is_return_leg' => true,
                            'parent_leg_id' => $record->id,
                            'return_leg_resale' => ReturnLegResale::Public,
                            'base_price' => $record->base_price,
                        ]);
                    }),
                Action::make('makePublic')
                    ->label('Make Public')
                    ->icon('heroicon-o-globe-alt')
                    ->requiresConfirmation()
                    ->action(fn (FlightLeg $record) => $record->update([
                        'visibility' => LegVisibility::Public,
                        'status' => LegStatus::Available,
                    ])),
                Action::make('makePrivate')
                    ->label('Make Private')
                    ->icon('heroicon-o-lock-closed')
                    ->requiresConfirmation()
                    ->action(fn (FlightLeg $record) => $record->update(['visibility' => LegVisibility::Private])),
                DeleteAction::make(),
            ])
            ->reorderable('sort_order');
    }
}
