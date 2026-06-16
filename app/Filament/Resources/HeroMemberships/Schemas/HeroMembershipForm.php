<?php

namespace App\Filament\Resources\HeroMemberships\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class HeroMembershipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('passenger_id')
                    ->relationship('passenger', 'id'),
                TextInput::make('member_code')
                    ->required(),
                TextInput::make('plan_level')
                    ->required(),
                TextInput::make('member_name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                DatePicker::make('expires_at'),
                Toggle::make('is_active')
                    ->required(),
                Textarea::make('covered_members')
                    ->columnSpanFull(),
            ]);
    }
}
