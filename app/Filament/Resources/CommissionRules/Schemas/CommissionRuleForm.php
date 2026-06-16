<?php

namespace App\Filament\Resources\CommissionRules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CommissionRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('agency_id')
                    ->relationship('agency', 'name'),
                TextInput::make('channel'),
                TextInput::make('split_type')
                    ->required()
                    ->default('fixed'),
                TextInput::make('hero_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('agency_amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('priority')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
