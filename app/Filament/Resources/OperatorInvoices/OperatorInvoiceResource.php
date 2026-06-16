<?php

namespace App\Filament\Resources\OperatorInvoices;

use App\Filament\Resources\OperatorInvoices\Pages\CreateOperatorInvoice;
use App\Filament\Resources\OperatorInvoices\Pages\EditOperatorInvoice;
use App\Filament\Resources\OperatorInvoices\Pages\ListOperatorInvoices;
use App\Models\OperatorInvoice;
use App\Services\Finance\ReconciliationService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class OperatorInvoiceResource extends Resource
{
    protected static ?string $model = OperatorInvoice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Reconciliation';

    protected static ?string $navigationLabel = 'Operator Invoices';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('operator_id')->relationship('operator', 'name')->required(),
            TextInput::make('invoice_reference')->label('Invoice Reference')->required(),
            DatePicker::make('period_start')->required(),
            DatePicker::make('period_end')->required(),
            TextInput::make('total_hours')->numeric()->required(),
            TextInput::make('total_cost')->numeric()->prefix('$')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('operator.name'),
                TextColumn::make('invoice_reference')->label('Reference')->searchable(),
                TextColumn::make('period_start')->date(),
                TextColumn::make('period_end')->date(),
                TextColumn::make('total_hours'),
                TextColumn::make('total_cost')->money('usd'),
            ])
            ->recordActions([
                Action::make('reconcile')
                    ->label('Reconcile')
                    ->icon('heroicon-o-scale')
                    ->action(fn (OperatorInvoice $record) => app(ReconciliationService::class)->compareOperatorInvoice($record)),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOperatorInvoices::route('/'),
            'create' => CreateOperatorInvoice::route('/create'),
            'edit' => EditOperatorInvoice::route('/{record}/edit'),
        ];
    }
}
