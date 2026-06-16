<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Enums\PaymentMethod;
use App\Filament\Resources\Invoices\InvoiceResource;
use App\Services\Finance\InvoiceService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('recordPayment')
                ->label('Record Payment')
                ->icon('heroicon-o-banknotes')
                ->form([
                    TextInput::make('amount')->numeric()->prefix('$')->required(),
                    Select::make('method')->options(PaymentMethod::class)->required(),
                ])
                ->action(function (array $data): void {
                    app(InvoiceService::class)->recordPayment(
                        $this->record,
                        (float) $data['amount'],
                        PaymentMethod::from($data['method']),
                        auth()->id(),
                    );
                    $this->refreshFormData(['status', 'total']);
                }),
            Action::make('pdf')
                ->label('Download PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => app(InvoiceService::class)->generatePdf($this->record)->download($this->record->invoice_number.'.pdf')),
            DeleteAction::make(),
        ];
    }
}
