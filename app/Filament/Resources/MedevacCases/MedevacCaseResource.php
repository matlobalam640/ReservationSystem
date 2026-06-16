<?php

namespace App\Filament\Resources\MedevacCases;

use App\Filament\Resources\MedevacCases\Pages\CreateMedevacCase;
use App\Filament\Resources\MedevacCases\Pages\EditMedevacCase;
use App\Filament\Resources\MedevacCases\Pages\ListMedevacCases;
use App\Filament\Resources\MedevacCases\Schemas\MedevacCaseForm;
use App\Filament\Resources\MedevacCases\Tables\MedevacCasesTable;
use App\Models\MedevacCase;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MedevacCaseResource extends Resource
{
    protected static ?string $model = MedevacCase::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    protected static string|UnitEnum|null $navigationGroup = 'Special Operations';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'medical-dispatch', 'dispatch']) ?? false;
    }

    public static function form(Schema $schema): Schema
    {
        return MedevacCaseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MedevacCasesTable::configure($table);
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
            'index' => ListMedevacCases::route('/'),
            'create' => CreateMedevacCase::route('/create'),
            'edit' => EditMedevacCase::route('/{record}/edit'),
        ];
    }
}
