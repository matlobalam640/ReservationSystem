<?php

namespace App\Filament\Resources\Operators\Schemas;

use App\Enums\BillingTimeBasis;
use App\Enums\ContractType;
use App\Support\EnumOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OperatorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Operator Details')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            Toggle::make('is_active')
                                ->default(true),
                            TextInput::make('contact_email')
                                ->email(),
                            TextInput::make('contact_phone')
                                ->tel(),
                            Select::make('contract_type')
                                ->options(EnumOptions::from(ContractType::class))
                                ->default(ContractType::Hourly->value)
                                ->required(),
                            Select::make('billing_time_basis')
                                ->options(EnumOptions::from(BillingTimeBasis::class))
                                ->default(BillingTimeBasis::BlockTime->value)
                                ->required(),
                        ]),
                        Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
