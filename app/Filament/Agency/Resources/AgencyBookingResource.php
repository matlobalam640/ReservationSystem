<?php

namespace App\Filament\Agency\Resources;

use App\Filament\Agency\Resources\AgencyBookingResource\Pages\CreateAgencyBooking;
use App\Filament\Agency\Resources\AgencyBookingResource\Pages\ListAgencyBookings;
use App\Filament\Agency\Resources\AgencyBookingResource\Pages\ViewAgencyBooking;
use App\Models\Booking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AgencyBookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static ?string $navigationLabel = 'My Bookings';

    protected static ?string $modelLabel = 'Booking';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        $agencyId = auth()->user()?->agency_id;

        return parent::getEloquentQuery()->when($agencyId, fn ($q) => $q->where('agency_id', $agencyId));
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('reference_number')->searchable(),
            TextColumn::make('flightLeg.routeLabel')->label('Route'),
            TextColumn::make('flightLeg.departure_at')->dateTime(),
            TextColumn::make('status')->badge(),
            TextColumn::make('total_amount')->money('usd'),
            TextColumn::make('commissionLedger.agency_amount')
                ->label('Commission')
                ->money('usd')
                ->placeholder('—'),
        ])
            ->defaultSort('created_at', 'desc')
            ->recordUrl(fn (Booking $record): string => static::getUrl('view', ['record' => $record]));
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAgencyBookings::route('/'),
            'create' => CreateAgencyBooking::route('/create'),
            'view' => ViewAgencyBooking::route('/{record}'),
        ];
    }
}
