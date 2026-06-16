<?php

namespace App\Filament\Agency\Resources;

use App\Filament\Agency\Resources\AgencyCommissionResource\Pages\ListAgencyCommissions;
use App\Models\CommissionLedger;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AgencyCommissionResource extends Resource
{
    protected static ?string $model = CommissionLedger::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'Commissions';

    protected static ?string $modelLabel = 'Commission';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        $agencyId = auth()->user()?->agency_id;

        return parent::getEloquentQuery()
            ->when($agencyId, fn (Builder $q) => $q->where('agency_id', $agencyId));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking.reference_number')->label('Booking')->searchable(),
                TextColumn::make('route')
                    ->label('Route')
                    ->state(fn (CommissionLedger $record): string => $record->booking?->flightLeg?->routeLabel() ?? '—'),
                TextColumn::make('agency_amount')->money('usd')->label('Your commission'),
                TextColumn::make('hero_amount')->money('usd')->label('HERO share'),
                TextColumn::make('status')->badge(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAgencyCommissions::route('/'),
        ];
    }
}
