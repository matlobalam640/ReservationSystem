<?php

namespace App\Filament\Resources\LegTimeLogs;

use App\Filament\Resources\LegTimeLogs\Pages\CreateLegTimeLog;
use App\Filament\Resources\LegTimeLogs\Pages\EditLegTimeLog;
use App\Filament\Resources\LegTimeLogs\Pages\ListLegTimeLogs;
use App\Filament\Resources\LegTimeLogs\Schemas\LegTimeLogForm;
use App\Filament\Resources\LegTimeLogs\Tables\LegTimeLogsTable;
use App\Models\LegTimeLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LegTimeLogResource extends Resource
{
    protected static ?string $model = LegTimeLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string|UnitEnum|null $navigationGroup = 'Reconciliation';

    protected static ?string $navigationLabel = 'Leg Time Logs';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return LegTimeLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LegTimeLogsTable::configure($table);
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
            'index' => ListLegTimeLogs::route('/'),
            'create' => CreateLegTimeLog::route('/create'),
            'edit' => EditLegTimeLog::route('/{record}/edit'),
        ];
    }
}
