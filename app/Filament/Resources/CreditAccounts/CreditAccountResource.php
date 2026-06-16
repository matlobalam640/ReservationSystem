<?php

namespace App\Filament\Resources\CreditAccounts;

use App\Filament\Resources\CreditAccounts\Pages\CreateCreditAccount;
use App\Filament\Resources\CreditAccounts\Pages\EditCreditAccount;
use App\Filament\Resources\CreditAccounts\Pages\ListCreditAccounts;
use App\Filament\Resources\CreditAccounts\Schemas\CreditAccountForm;
use App\Filament\Resources\CreditAccounts\Tables\CreditAccountsTable;
use App\Models\CreditAccount;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CreditAccountResource extends Resource
{
    protected static ?string $model = CreditAccount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|UnitEnum|null $navigationGroup = 'Finance';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return CreditAccountForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CreditAccountsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCreditAccounts::route('/'),
            'create' => CreateCreditAccount::route('/create'),
            'edit' => EditCreditAccount::route('/{record}/edit'),
        ];
    }
}
