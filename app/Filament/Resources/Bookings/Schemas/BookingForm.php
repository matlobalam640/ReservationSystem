<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Enums\BookingChannel;
use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\FlightLeg;
use App\Models\Seat;
use App\Support\EnumOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Passenger')
                    ->visibleOn('create')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('first_name')->required(),
                            TextInput::make('last_name')->required(),
                            TextInput::make('email')->email(),
                            TextInput::make('phone'),
                            TextInput::make('weight_kg')->numeric()->required()->suffix('kg'),
                            TextInput::make('baggage_weight_kg')->numeric()->default(0)->suffix('kg'),
                            Select::make('seat_id')
                                ->label('Seat')
                                ->options(fn (callable $get) => self::seatOptions($get('flight_leg_id')))
                                ->required()
                                ->searchable(),
                        ]),
                    ]),
                Section::make('Booking')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('reference_number')
                                ->disabled()
                                ->dehydrated(false)
                                ->visibleOn('edit'),
                            Select::make('flight_leg_id')
                                ->relationship('flightLeg', 'origin')
                                ->getOptionLabelFromRecordUsing(fn ($r) => $r->routeLabel().' — '.$r->departure_at->format('M j g:i A'))
                                ->searchable()
                                ->preload()
                                ->required()
                                ->live(),
                            Select::make('agency_id')
                                ->relationship('agency', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('booking_channel')
                                ->options(EnumOptions::from(BookingChannel::class))
                                ->default(BookingChannel::Admin->value)
                                ->required(),
                            Select::make('status')
                                ->options(EnumOptions::from(BookingStatus::class))
                                ->default(BookingStatus::Confirmed->value)
                                ->required()
                                ->visibleOn('edit'),
                            Select::make('payment_status')
                                ->options(EnumOptions::from(PaymentStatus::class))
                                ->default(PaymentStatus::Unpaid->value)
                                ->required()
                                ->visibleOn('edit'),
                            TextInput::make('total_amount')
                                ->numeric()
                                ->prefix('$')
                                ->default(0)
                                ->visibleOn('edit'),
                        ]),
                        Textarea::make('notes')->rows(2)->columnSpanFull(),
                    ]),
            ]);
    }

    private static function seatOptions(?int $legId): array
    {
        if (! $legId) {
            return [];
        }

        return Seat::query()
            ->where('flight_leg_id', $legId)
            ->where('is_available', true)
            ->orderBy('seat_number')
            ->pluck('seat_number', 'id')
            ->all();
    }
}
