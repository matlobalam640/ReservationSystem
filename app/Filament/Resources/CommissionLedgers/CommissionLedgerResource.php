<?php

namespace App\Filament\Resources\CommissionLedgers;

use App\Filament\Resources\CommissionLedgers\Pages\ListCommissionLedgers;
use App\Models\CommissionLedger;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CommissionLedgerResource extends Resource
{
    protected static ?string $model = CommissionLedger::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static string|UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Commission Ledger';

    protected static ?int $navigationSort = 4;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking.reference_number')->label('Booking')->searchable(),
                TextColumn::make('agency.name')->placeholder('—'),
                TextColumn::make('hero_amount')->money('usd')->label('HERO'),
                TextColumn::make('agency_amount')->money('usd')->label('Agency'),
                TextColumn::make('status')->badge(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCommissionLedgers::route('/'),
        ];
    }
}
