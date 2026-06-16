<?php

namespace App\Filament\Pages;

use App\Enums\PaymentMethod;
use App\Models\Booking;
use App\Services\Finance\InvoiceService;
use BackedEnum;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class WalkInCheckout extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static string|UnitEnum|null $navigationGroup = 'Finance';

    protected static ?string $navigationLabel = 'Walk-In Checkout';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.pages.walk-in-checkout';

    public string $reference = '';

    public ?Booking $booking = null;

    public float $amount = 0;

    public string $method = 'cash';

    public function search(): void
    {
        $this->booking = Booking::query()
            ->where('reference_number', $this->reference)
            ->with(['invoices', 'bookingPassengers.passenger', 'flightLeg'])
            ->first();

        if ($this->booking) {
            $this->amount = (float) $this->booking->total_amount;
        } else {
            Notification::make()->warning()->body('Booking not found.')->send();
        }
    }

    public function recordPayment(): void
    {
        if (! $this->booking) {
            return;
        }

        $invoice = $this->booking->invoices()->latest()->first();
        if (! $invoice) {
            Notification::make()->danger()->body('No invoice found for this booking.')->send();

            return;
        }

        app(InvoiceService::class)->recordPayment(
            $invoice,
            $this->amount,
            PaymentMethod::from($this->method),
            auth()->id(),
        );

        $this->booking->refresh()->load('invoices');
        Notification::make()->success()->body('Payment recorded.')->send();
    }
}
