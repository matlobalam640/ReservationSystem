<?php

namespace App\Filament\Resources\Bookings\RelationManagers;

use App\Models\AddOn;
use App\Models\BookingAddOn;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookingAddOnsRelationManager extends RelationManager
{
    protected static string $relationship = 'bookingAddOns';

    protected static ?string $title = 'Add-Ons';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('add_on_id')
                ->label('Add-On')
                ->options(AddOn::query()->where('is_active', true)->pluck('name', 'id'))
                ->required()
                ->live()
                ->afterStateUpdated(function ($state, callable $set): void {
                    if ($addOn = AddOn::find($state)) {
                        $set('unit_price', $addOn->price);
                        $set('total_price', $addOn->price);
                    }
                }),
            TextInput::make('quantity')->numeric()->default(1)->required(),
            TextInput::make('unit_price')->numeric()->prefix('$')->required(),
            TextInput::make('total_price')->numeric()->prefix('$')->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('addOn.name'),
                TextColumn::make('quantity'),
                TextColumn::make('unit_price')->money('usd'),
                TextColumn::make('total_price')->money('usd'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $qty = (int) ($data['quantity'] ?? 1);
                        $data['total_price'] = ($data['unit_price'] ?? 0) * $qty;

                        return $data;
                    })
                    ->after(function (BookingAddOn $record): void {
                        $booking = $record->booking;
                        $booking->update([
                            'total_amount' => (float) $booking->total_amount + (float) $record->total_price,
                        ]);
                    }),
            ])
            ->recordActions([DeleteAction::make()]);
    }
}
