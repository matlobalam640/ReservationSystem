<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('invoice_number')
                    ->required(),
                Select::make('invoice_type')
                    ->options(InvoiceType::class)
                    ->required(),
                Select::make('booking_id')
                    ->relationship('booking', 'id'),
                Select::make('agency_id')
                    ->relationship('agency', 'name'),
                TextInput::make('subtotal')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('tax')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('status')
                    ->options(InvoiceStatus::class)
                    ->default('unpaid')
                    ->required(),
                DatePicker::make('due_date'),
            ]);
    }
}
