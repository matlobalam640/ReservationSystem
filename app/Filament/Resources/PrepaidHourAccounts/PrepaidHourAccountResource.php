<?php

namespace App\Filament\Resources\PrepaidHourAccounts;

use App\Filament\Resources\PrepaidHourAccounts\Pages\CreatePrepaidHourAccount;
use App\Filament\Resources\PrepaidHourAccounts\Pages\EditPrepaidHourAccount;
use App\Filament\Resources\PrepaidHourAccounts\Pages\ListPrepaidHourAccounts;
use App\Filament\Resources\PrepaidHourAccounts\Schemas\PrepaidHourAccountForm;
use App\Filament\Resources\PrepaidHourAccounts\Tables\PrepaidHourAccountsTable;
use App\Models\PrepaidHourAccount;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PrepaidHourAccountResource extends Resource
{
    protected static ?string $model = PrepaidHourAccount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string|UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Prepaid Hours';

    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return PrepaidHourAccountForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PrepaidHourAccountsTable::configure($table);
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
            'index' => ListPrepaidHourAccounts::route('/'),
            'create' => CreatePrepaidHourAccount::route('/create'),
            'edit' => EditPrepaidHourAccount::route('/{record}/edit'),
        ];
    }
}
