<?php

namespace App\Filament\Resources\CreditAccounts\Pages;

use App\Filament\Resources\CreditAccounts\CreditAccountResource;
use App\Services\Finance\CreditAccountService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCreditAccount extends EditRecord
{
    protected static string $resource = CreditAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('monthlyStatement')
                ->label('Generate Monthly Statement')
                ->icon('heroicon-o-document-text')
                ->action(fn () => app(CreditAccountService::class)->generateMonthlyStatement($this->record, now()->subMonth())),
            DeleteAction::make(),
        ];
    }
}
