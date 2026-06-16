<?php

namespace App\Filament\Resources\Agencies\Schemas;

use App\Enums\AgencyPaymentModel;
use App\Support\EnumOptions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AgencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Agency Details')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->required(),
                            TextInput::make('code')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(20)
                                ->uppercase(),
                            TextInput::make('contact_email')
                                ->email(),
                            TextInput::make('contact_phone')
                                ->tel(),
                            Select::make('payment_model')
                                ->options(EnumOptions::from(AgencyPaymentModel::class))
                                ->default(AgencyPaymentModel::HeroCollects->value)
                                ->required(),
                            TextInput::make('default_commission_rate')
                                ->numeric()
                                ->prefix('$'),
                            Toggle::make('is_active')
                                ->default(true),
                        ]),
                    ]),
            ]);
    }
}
